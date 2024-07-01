<?php

namespace App\Http\Actions;

use Checkout;
use CreditCards;
use Orders;
use Prescriptions;
use Exception;
use App\Exceptions\ValidationException;

/**
 *
 */
class EditBilling extends AbstractAction
{
    const MSG_SUCCESS = 'Your billing information has been successfully updated.';
    const MSG_ERROR   = 'We were unable to save your card at this time, please try again later.';

    /**
     *
     */
    public function __invoke(CreditCards $creditCards, Checkout $checkout, Prescriptions $prescriptions, Orders $orders)
    {
        $person  = $this->auth->user();
        $account = $person->getAccount();
        $cards = $creditCards->findActiveForPerson($person);

        if ($this->request->getMethod() == 'POST') {

            try {
                $card = $checkout->charge($this->request->all(), $person);

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

                    $this->session->flash('success', static::MSG_SUCCESS);

                } else {
                    throw new Exception();
                }

                return $this->redirect('dashboard');

            } catch (ValidationException $e) {
                $this->session->flash('error', $e->getMessage());
            } catch (Exception $e) {
                $this->session->flash('error', static::MSG_ERROR);
            }
        }

        return $this->render('pages/dashboard/billing', 200, get_defined_vars());
    }
}
