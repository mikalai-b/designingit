<?php

use App\Notifications\BillingIssue;
use Omnipay\Common\GatewayInterface as Gateway;
use Illuminate\Log\Writer as Logger;
use Illuminate\Mail\Mailer;
use App\Mail;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class Rx
{
    /**
     *
     */
    public function __construct(TwigBridge\Bridge $twig, Logger $logger, Providers $providers, Symptoms $symptoms, Gateway $gateway, Mailer $mailer)
    {
        $this->twig      = $twig;
        $this->logger    = $logger;
        $this->providers = $providers;
        $this->symptoms  = $symptoms;
        $this->gateway   = $gateway;
        $this->mailer    = $mailer;
    }


    /**
     *
     */
    public function createFromConsultation(Consultation $consultation)
    {
        if (count($consultation->getPrescriptions())) {
            foreach ($consultation->getPrescriptions() as $prescription) {
                $this->fill($prescription);
            }
            $consultation->getOrder()->setOpen();
        } else {
            $consultation->getOrder()->setClosed();
        }

        // $this->mailer
        //     ->to($consultation->getOrder()->getPerson()->toEmail())
        //     ->send(new Mail\ConsultationCompleted([
        //         'person' => $consultation->getOrder()->getPerson()
        //     ]))
        // ;

        $consultation->setCompleted();
    }


    /**
     *
     */
    public function getLetter(Consultation $consultation)
    {
        return $this->twig->load('messages/consultation')->render(get_defined_vars());
    }


    /**
     *
     */
    public function getPhysicalExam(Consultation $consultation)
    {
        return $this->symptoms->findForConsultation($consultation)->map(function($symptom) {
            return (string) $symptom;
        })->toArray();
    }


    /**
     *
     */
    public function getDiagnosis(Consultation $consultation)
    {
        return $this->symptoms->findForConsultation($consultation)->map(function($symptom) {
            return (string) $symptom;
        })->toArray();
    }


    /**
     *
     */
    public function getAmountToBill(Prescription $prescription)
    {
        $order = $prescription->getConsultation()->getOrder();
        // if order is open, this is a refill.
        if ($order->isOpen()) {
            return $prescription->getLineItem()->getPrice();

        // If the order is not open, that means that a provider is attempting to fill
        // it for the first time.
        } else {
            return $prescription->getLineItem()->getFirstShipmentPrice();
        }

    }


    /**
     *
     */
    public function fill(Prescription $prescription)
    {
        $order = $prescription->getConsultation()->getOrder();

        try {
            if ($amount = $this->getAmountToBill($prescription)) {
                $request = $this->gateway->purchase([
                    'currency'          => 'USD',
                    'amount'            => $amount,
                    'cardReference'     => json_encode([
                        'customerPaymentProfileId' => $prescription->getCreditCard()->getToken(),
                        'customerProfileId' => $prescription->getCreditCard()->getCustomer(),
                    ]),
                ]);

                if (!$response = $request->send()) {
                    throw new \RuntimeException('Failed to connect to payment gateway', 100);
                }

                if (!$response->isSuccessful()) {
                    throw new \RuntimeException(sprintf('Failed to charge card: %s', $response->getMessage()), 200);
                }

                //
                // Reset resupply attempts if we were successful
                //

                $prescription->resetResupplyAttempts();
            }

            $prescription->setFilled($prescription->getFilled() + 1);
            $prescription->setDateLastRefilled(new \DateTime('now'));

            if (!$order->isOpen()) {
                $this->send('rx/new', $prescription);
            } else {
                $this->send('rx/refill', $prescription);
            }

        } catch (\RuntimeException $e) {
            if (!$order->isOpen()) {
                //
                // If the order is not yet open, that means that a provider is attempting to fill
                // it for the first time.  We re-throw the exception which can be caught upstream
                // so they can see the immediate error.
                //


                throw $e;

            } elseif ($e->getCode() > 100) {

                //
                // Otherwise, if the code deals failure in sending the prescription request or
                // with declined / card problems (not gateway problems), we're going to increment
                // resupply attempts and send e-mails.
                //

                $prescription->setResupplyAttempts($prescription->getResupplyAttempts() + 1);

                try {
                    $this->sendResupplyErrorEmails($prescription, $e);
                } catch (Exception $notificationException) {
                    Log::error($notificationException->getMessage());
                }

            } else {
                //
                // Do nothing, as this was a problem connecting with the payment gateway and we
                // should just try again tomorrow.
                //
            }
        }
    }


    /**
     *
     */
    protected function send($template, Prescription $prescription)
    {
        $user       = getenv('QS1_USER');
        $pass       = getenv('QS1_PASS');
        $api_url    = getenv('QS1_URL');

        $created    = date('c');
        $nonce      = md5(uniqid());
        $digest     = base64_encode(sha1($nonce . $created . $pass, TRUE));
        $msg_id     = strtoupper(md5('newrx' . $prescription->getId()));
        $providers  = $this->providers;
        $content    = $this->twig->load($template)->render(get_defined_vars());
        $context    = stream_context_create([
            'http' => [
                'ignore_errors' => TRUE,
                'method'        => 'POST',
                'content'       => $content,
                'header'        => implode("\r\n", [
                    'Content-Type: application/xml',
                    'Content-Length: ' . strlen($content)
                ]),
            ],
            'ssl' => [
                'verify_peer'      => TRUE,
                'verify_peer_name' => TRUE
            ]
        ]);

        $handle    = @fopen($api_url, 'rb', FALSE, $context);
        $response  = stream_get_contents($handle);
        $meta_data = stream_get_meta_data($handle);
        $data      = simplexml_load_string($response);

        $this->logger->info('Message sent to QS1:');
        $this->logger->info($content);
        $this->logger->info($response);

        if ($data->Body->Status->Code != '000') {
            throw new \RuntimeException('Failed sending prescription.', 300);
        }
    }


    /**
     *
     */
    protected function sendResupplyErrorEmails(Prescription $prescription, Exception $e)
    {
        //
        // Send all resupply errors except failure to connet to the payment gateway (which we
        // assume is temporary) to admins and errors
        //

        if ($e->getCode() > 200 || $prescription->getResupplyAttempts() % 4 == 0) {
            $admin_addresses = [];

            if (getenv('ADMIN_EMAIL')) {
                $admin_addresses[] = getenv('ADMIN_EMAIL');
            }

            if (getenv('ERROR_EMAIL')) {
                $admin_addresses[] = getenv('ERROR_EMAIL');
            }

            $this->mailer
                ->to($admin_addresses)
                ->send(new Mail\RxError([
                    'prescription' => $prescription,
                    'error'        => $e->getmessage()
                ]));
        }

        //
        // Send any resupply errors caused by a failed card/charge attempt to the customer as this
        // likely represents bad or expired card info.
        //

        if ($e->getCode() == 200 && $prescription->getResupplyAttempts() % 4 == 0) {
            $prescription->getConsultation()->getOrder()->getPerson()->notify(
                new BillingIssue($prescription)
            );
        }
    }
}
