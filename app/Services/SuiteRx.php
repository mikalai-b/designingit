<?php

use App\Notifications\BillingIssue;
use Omnipay\Common\GatewayInterface as Gateway;
use Illuminate\Log\Writer as Logger;
use Illuminate\Mail\Mailer;
use App\Mail;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Person;
use Prescriptions;

class SuiteRx
{
    protected $httpClient;
    protected $apiHost;
    protected $storeId = 11;

    public function __construct(TwigBridge\Bridge $twig, Logger $logger, Providers $providers, Symptoms $symptoms, Gateway $gateway, Mailer $mailer, Client $httpClient)
    {
        $this->twig      = $twig;
        $this->logger    = $logger;
        $this->providers = $providers;
        $this->symptoms  = $symptoms;
        $this->gateway   = $gateway;
        $this->mailer    = $mailer;
        $this->httpClient = $httpClient; // Inject HTTP client
        $this->apiHost = env('APP_ENV') === 'dev' ? env('SUITE_RX_API_STAGE_HOST') : env('SUITE_RX_API_PROD_HOST');
    }

    public function createFromConsultation(Consultation $consultation)
    {
        $prescriptionRepository = app()->make(Prescriptions::class);
        $prescriptions = $prescriptionRepository->findBy(['consultation' => $consultation->getId()]);

        if (count($prescriptions)) {
            $this->sendPrescription($consultation, $prescriptions);
            $consultation->getOrder()->setOpen();
        } else {
            $consultation->getOrder()->setClosed();
        }
        $consultation->setCompleted();
    }

    // Post
    public function createPatient(Person $person)
    {
        $endpointUrl = $this->apiHost.'/api/V3/IPS';
        $bodyData = [
            "storeId" => $this->storeId,
            "entity" => "patient",
            "field" => [
                "PATIENTFIRSTNAME:{$person->getFirstName()}",
                "PATIENTLASTNAME:{$person->getLastName()}",
                "PATIENTMIDDLENAME:{$person->getMiddleName()}",
                "PATIENTDOB:{$person->getDateOfBirth()->format('Y-m-d')}",
                "PATIENTGENDER:{$person->getGenderForSuiteRx()}",
                "PATIENTLANGUAGE:English",
                "FACILITYID:2395",
                "PATIENTROOMBEDID:5",
                "PATIENTEXTERNALID:{$person->getId()}",
                "PATIENTADDRESS:{$person->getAddressLine1()}",
                "PATIENTADDRESS2:{$person->getAddressLine2()}",
                "PATIENTZIPCODE:{$person->getPostalCode()}",
                "PATIENTCITY:{$person->getCity()}",
                "PATIENTSTATE:{$person->getState()}",
                "PATIENTHOMEPHONE:{$person->getPhone()}",
                "PATIENTEMAIL:{$person->getEmail()}",
                "PRIMARYCHARGEACCOUNTID:{$person->getId()}",
                "PATIENTCOURIERID:{$person->getId()}",
                "PATIENTDELIVERYFLAG:Y",
            ]
        ];

        $response = self::sendPostRequest($endpointUrl, $bodyData);

        return $response['result'];
    }

    public function createPrescriber(Person $person)
    {
        $endpointUrl = $this->apiHost.'/api/V3/IPS';
        $bodyData = [
            "storeId" => $this->storeId,
            "entity" => "PRESCRIBER",
            "field" => [
                "PRESCRIBERFIRSTNAME:{$person->getFirstName()}",
                "PRESCRIBERLASTNAME:{$person->getLastName()}",
                "PRESCRIBERZIPCODE:{$person->getPostalCode()}",
                "PRESCRIBERCITY:{$person->getCity()}",
                "PRESCRIBERSTATE:{$person->getState()}",
                "PRESCRIBEROFFICEPHONE1:{$person->getPhone()}",
                "PRESCRIBERUPIN:{$person->getId()}",
                "PRESCRIBERGENDER:{$person->getGender()}",
                "PRESCRIBERNPINUMBER:{$person->getId()}"
            ]
        ];

        $response = self::sendPostRequest($endpointUrl, $bodyData);

        return $response['result'];
    }

    protected function getPatient($patient)
    {
        $endpointUrl = $this->apiHost.'/api/V3/IPS';
        $getParams = [
            'storeid' => $this->storeId,
            'entity' => 'patient',
            'pagenumber' => 0,
            'pagesize' => 10,
            'field' => [
                'PATIENTID',
                'PATIENTFIRSTNAME',
                'PATIENTLASTNAME',
                'PATIENTMIDDLENAME',
                'PATIENTEXTERNALID',
                'PATIENTACTIVE',
                'PATIENTDOB',
                'PRIMARYPRESCRIBERID'
            ],
            'where' => "(PATIENTEXTERNALID = '{$patient->getId()}')"
        ];

        return self::sendGetRequest($endpointUrl, $getParams);
    }

    protected function getPrescriber($patient)
    {
        $endpointUrl = $this->apiHost.'/api/V3/IPS';
        $getParams = [
            'storeid' => $this->storeId,
            'entity' => 'PRESCRIBER',
            'pagenumber' => 0,
            'pagesize' => 10,
            'field' => [
                'PRESCRIBERID',
                'PRESCRIBERFIRSTNAME',
                'PRESCRIBERLASTNAME',
                'PRESCRIBERGENDER',
                'PRESCRIBERUPIN',
                'PRESCRIBERACTIVE',
            ],
            'where' => "(PRESCRIBERUPIN = '{$patient->getId()}')"
        ];

        return self::sendGetRequest($endpointUrl, $getParams);
    }

    protected function getDrug($prescription)
    {
        $drugName = $prescription->getLineItem()->getProduct()->getName();

        $drugStrengthInfo = trim($prescription->getLineItem()->getProduct()->getStrength());
        preg_match('/(\d+(?:\.\d+)?)(\s*\D+)/', $drugStrengthInfo, $matches);

        $drugStrength = isset($matches[1]) ? trim($matches[1]) : ''; // Numeric part
        $drugStrengthUnit = isset($matches[2]) ? trim($matches[2]) : ''; // Unit part


        $endpointUrl = $this->apiHost.'/api/V3/IPS';
        $getParams = [
            'storeid' => $this->storeId,
            'entity' => 'drug',
            'pagenumber' => 0,
            'pagesize' => 10,
            'field' => [
                'drugid',
                'drugname',
                'drugstrength',
                'drugstrengthunit'
            ],
            'where' => "(DRUGNAME = '{$drugName}')",
            'where' => "(DRUGSTRENGTH = '{$drugStrength}')"
        ];

        return self::sendGetRequest($endpointUrl, $getParams);
    }

    protected function sendPrescription(Consultation $consultation, $prescriptions)
    {
        $endpointUrl = $this->apiHost.'/api/V3/IPS';

        $patient = self::getPatient($consultation->getOrder()->getPerson());
        $prescriber = self::getPrescriber($consultation->getOrder()->getPerson());
        if (!$patient && !$prescriber) {
            throw new \Exception('Patient and Prescriber not found!');
        }

        foreach ($prescriptions as $prescription) {
            $product = $prescription->getLineItem()->getProduct();
            $drug = self::getDrug($prescription);

            $bodyData = [
                "storeId" => $this->storeId,
                "entity" => "prescription",
                "field" => [
                    "PRESCRIPTIONTRANDATE:2024-01-12",
                    "PATIENTID:{$patient['PATIENTID']}",
                    "PRESCRIBERID:{$prescriber['PRESCRIBERID']}",
                    "DRUGID:{$drug['DRUGID']}",
                    "PRESCRIPTIONTYPEID: O",
                    "PRESCRIPTIONDAYSSUPPLY:{$product->getDefaultPeriod()}",
                    "PRESCRIPTIONFILLID:{$drug['DRUGID']}",
                    "PRESCRIPTONORIGINALQTY: 1",
                    "PRESCRIPTIONFILLQTY: 1",
                    "PRESCRIPTIONSIGCODE:{$product->getInfo()}"
                ]
            ];
            try {
                $response = self::sendPostRequest($endpointUrl, $bodyData);
                if ($response['status'] === 200) {
                    $order = $prescription->getConsultation()->getOrder();
                    // Payment process
                    if ($amount = $this->getAmountToBill($prescription)) {
                        $request = $this->gateway->purchase([
                            'currency' => 'USD',
                            'amount' => $amount,
                            'cardReference' => json_encode([
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
                }
            } catch (RuntimeException $e) {
                throw $e;
//                $this->session->flash('error', $e->getMessage());
//
//                return $this->render('pages.consultations.select', 400, get_defined_vars());
            }
        }
    }

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
                ->send(new Mail\SuiteRxError([
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

    private function sendPostRequest(string $endpointUrl, array $bodyData)
    {
        try {
            $response = $this->httpClient->post($endpointUrl, [
                'json' => $bodyData,
                'headers' => [
                    'Authorization' => 'Bearer '.env('SUITE_RX_API_TOKEN'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            return [
                'status' => $response->getStatusCode(),
                'result' => json_decode($response->getBody()->getContents(), true)
            ];

        } catch (\RuntimeException $e) {
            throw $e;
        }
    }

    private function sendGetRequest(string $endpointUrl, array $getParams)
    {
        try {
            $queryString = http_build_query($getParams);
            $urlWithParams = $endpointUrl . '?' . $queryString;

            $response = $this->httpClient->get($urlWithParams, [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('SUITE_RX_API_TOKEN'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);
            if (isset($response['Data'][0])) {
                return $response['Data'][0];
            } else {
                return false;
            }
        } catch (\RuntimeException $e) {
            throw $e;
        }
    }
}