<?php

namespace App\Http\Actions\Cart;

use Consultations;
use ConsultationFormInspector;
use DateInterval;
use DateTime;
use Prescription;
use Products;
use Prescriptions;
use Checkout;
use App\Exceptions;

use App\Http\Actions\AbstractAction;
use App\Services\Cart;

/**
 *
 */
class AddConsultation extends AbstractAction
{
    /**
     *
     */
    public function __invoke(Cart $cart, Checkout $checkout, Consultations $consultations, ConsultationFormInspector $inspector)
    {
        $order        = $checkout->getCurrentOrder();
        $active_step  = 'cart.consultation';

        if (!count($cart->content())) {
            return $this->redirect('cart');
        }

        if ($checkout->getCurrentStep() != $active_step && !$checkout->hasCompletedStep($active_step)) {
            return $this->redirect($checkout->getCurrentStep());
        }

        if (!$consultation = $order->getConsultation()) {
            $consultation = $consultations->createFromCart($cart);
        }

        if ($this->request->getMethod() == 'POST') {
            try {
                $answers = $this->request->input('answers', array());
                $notes   = $this->request->input('notes', array());

                foreach ($consultation->getAnswers() as $answer) {
                    if (isset($answers[$answer->getKey()])) {
                        $answer->setContent($answers[$answer->getKey()]);

                        if (isset($notes[$answer->getKey()])) {
                            $answer->setNote($notes[$answer->getKey()]);
                        }
                    }
                }

                $inspector->run($this->request->all(), $consultation);

                $consultation->setOrder($order);
                $consultations->store($consultation, TRUE);

                return $this->redirect($checkout->getNextStep($active_step));

            } catch (Exceptions\ValidationException $e){
                $errors = $e->getMessages();

                $this->session->flash('error', $e->getMessage());

                return  $this->render('pages.cart.consultation', 400, get_defined_vars());
            }
        }

        return $this->render('pages.cart.consultation', 200, get_defined_vars());
    }

    public static function addMentalHealthConsultation(Cart $cart, Checkout $checkout, Consultations $consultations, $request)
    {
        if($request->mental_health_form) {
            $mentalHealthFormDataAsnwers = json_decode($request->mental_health_form);

            $order = $checkout->getCurrentOrder();
            if (!$consultation = $order->getConsultation()) {
                $consultation = $consultations->createFromCartMentalHealth($cart, $mentalHealthFormDataAsnwers);
            }
            $consultation->setOrder($order);

            $entityManager = app('Doctrine\ORM\EntityManagerInterface');
            $entityManager->persist($consultation);
            $entityManager->flush();
            if ($consultation->getId() !== null) {
                return [
                    "result" => true,
                    "redirect_url" => "cart.photos"
                ];
            }
        }
    }

    public static function addWeightConsultation(Cart $cart, Checkout $checkout, Consultations $consultations, $request)
    {
        if($request->weight_form) {
            $weightFormDataAsnwers = json_decode($request->weight_form);

            $order = $checkout->getCurrentOrder();
            if (!$consultation = $order->getConsultation()) {
                $consultation = $consultations->createFromCartWeight($cart, $weightFormDataAsnwers);
            }
            $consultation->setOrder($order);

            $entityManager = app('Doctrine\ORM\EntityManagerInterface');
            $entityManager->persist($consultation);
            $entityManager->flush();
            if ($consultation->getId() !== null) {
                return [
                    "result" => true,
                    "redirect_url" => "cart.photos",
                    'consultation' => $consultation
                ];
            }
        }
    }

    public static function addWeightConsultationDefaultPrescriptionSet($saveHealthConsultation, $consultation)
    {
        $prescriptionDayStep = 28;
        $prescriptionRepository = app()->make(Prescriptions::class);
        $consultation = $saveHealthConsultation['consultation'];
        $selectedProduct = $consultation->getOrder()->getProducts()->first();

        $orderCreatedDate = $consultation->getOrder()->getDateCreated();
        $instruction = trim($consultation->getOrder()->getProductType()->getApprovedTemplate());

        if ($selectedProduct->getName() === "Semaglutide") {
            $line_items = $consultation->getOrder()->getLineItems();
            foreach ($line_items as $line_item) {
                $expireDate = ($orderCreatedDate)->add(new DateInterval('P' . $prescriptionDayStep . 'D'))->format('Y-m-d H:i:s');

                $consultation->getPrescriptions()->add($prescriptionRepository->createFromLineItem(
                    $line_item,
                    1,
                    $instruction,
                    $expireDate,
                    $consultation
                ));
            }
        }
    }
}
