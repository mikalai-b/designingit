<?php
namespace App\Http\Actions\Cart;

use Orders;
use Checkout;
use Prescriptions;
use BillingFormInspector;
use App\Exceptions;

use Omnipay\Common\CreditCard;
use App\Http\Actions\AbstractAction;
use App\Services\Cart;
use Consultations;
use SuiteRx;

/**
 *
 */
class AddBilling extends AbstractAction
{
    /**
     *
     */
    public function __invoke(Cart $cart, CreditCard $card, Checkout $checkout, BillingFormInspector $inspector, Prescriptions $prescriptions, Orders $orders, Consultations $consultations, SuiteRx $suiteRx)
    {
        $order       = $checkout->getCurrentOrder();
        $cards       = $checkout->getCards();
        $person      = $this->auth->user();
        $active_step = 'cart.billing';
        $selectedProductInfo = self::getSelectedProduct($cart);

        $firstItemCategory = false;
        if (count($cart->content()) > 0) {
            $firstItem = $cart->content()->first()->toArray();
            $firstItemCategory = $firstItem['options']['request_form'];
        }

        if (!count($cart->content())) {
            return $this->redirect('cart');
        }

        if ($checkout->getCurrentStep() != $active_step && !$checkout->hasCompletedStep($active_step)) {
            return $this->redirect($checkout->getCurrentStep());
        }

        if ($this->request->getMethod() == 'POST') {
            try {

                // Insert for mental_health form
                if ($selectedProductInfo['options']['request_form'] === "mental_health") {
                    if (!count($cart->content())) {
                        return $this->redirect('cart');
                    }
                    $saveHealthConsultation = AddConsultation::addMentalHealthConsultation($cart, $checkout, $consultations, $this->request);

                    // User info
                    $checkout->fillAddressInfo($cart, $this->request->all());

                    $card = $checkout->bill($cart, $this->request->all());
                    if ($card && !$cards->contains($card)) {
                        $cards->add($card);
                    }
                    $inspector->run($this->request->all(), $card);

                    if ($card) {
                        $prescriptions = $prescriptions->findOpenForPerson($person);

                        foreach ($prescriptions as $prescription) {
                            $prescription->setCreditCard($card);
                            $prescription->resetResupplyAttempts();
                        }

                        $orders = $orders->findAllNotClosedForPerson($person);

                        foreach ($orders as $order) {
                            $order->setCreditCard($card);
                        }
                    }

                    if ($saveHealthConsultation['result'] === true) {
                        $checkout->setCurrentStepToPhotos();
                        return $this->redirect($saveHealthConsultation['redirect_url']);
                    }
                }

                // Insert for weight form
                if ($selectedProductInfo['options']['request_form'] === "weight") {

                    if (!count($cart->content())) {
                        return $this->redirect('cart');
                    }

                    // User info
                    $checkout->fillAddressInfo($cart, $this->request->all());

                    $card = $checkout->bill($cart, $this->request->all());
                    if ($card && !$cards->contains($card)) {
                        $cards->add($card);
                    }
                    $inspector->run($this->request->all(), $card);

                    if ($card) {
                        $prescriptions = $prescriptions->findOpenForPerson($person);

                        foreach ($prescriptions as $prescription) {
                            $prescription->setCreditCard($card);
                            $prescription->resetResupplyAttempts();
                        }

                        $orders = $orders->findAllNotClosedForPerson($person);

                        foreach ($orders as $order) {
                            $order->setCreditCard($card);
                        }
                    }

                    $saveHealthConsultation = AddConsultation::addWeightConsultation($cart, $checkout, $consultations, $this->request);
                    // Save new Patient in SuiteRx
                    $suiteRx->createPatient($person);
                    // Create Prescriber
                    $suiteRx->createPrescriber($person);

                    if ($saveHealthConsultation['result'] === true) {
                        AddConsultation::addWeightConsultationDefaultPrescriptionSet($saveHealthConsultation, $saveHealthConsultation['consultation']);

                        $checkout->setCurrentStepToPhotos();
                        return $this->redirect($saveHealthConsultation['redirect_url']);
                    }
                }

                $checkout->fillAddressInfo($cart, $this->request->all());

                $card = $checkout->bill($cart, $this->request->all());

                if ($card && !$cards->contains($card)) {
                    $cards->add($card);
                }

                $inspector->run($this->request->all(), $card);

                if ($card) {
                    $prescriptions = $prescriptions->findOpenForPerson($person);

                    foreach ($prescriptions as $prescription) {
                        $prescription->setCreditCard($card);
                        $prescription->resetResupplyAttempts();
                    }

                    $orders = $orders->findAllNotClosedForPerson($person);

                    foreach ($orders as $order) {
                        $order->setCreditCard($card);
                    }
                }

                return $this->redirect($checkout->getNextStep($active_step));

            } catch (Exceptions\ValidationException $e) {
                $errors = $e->getMessages();

                $this->session->flash('error', $e->getMessage());

                return $this->render('pages.cart.billing', 400, get_defined_vars());
            }
        }

        return $this->render('pages.cart.billing', 200, get_defined_vars());
    }

    private static function getSelectedProduct(Cart $cart)
    {
        if ($cart->content()) {
            if ($cart->content()->first()) {
                return $cart->content()->first()->toArray();
            }
        }

        return false;
    }
}
