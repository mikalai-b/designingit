<?php

namespace App\Http\Actions;

use App\Services\Reorder;
use App\Services\Cart;
use Checkout;
use Prescriptions;

class StartReorder extends AbstractAction
{
    const INVALID_PRESCRIPTION_ERROR = 'An error has occurred.';


    /**
     *
     */
    public function __invoke($id, Prescriptions $prescriptions, Reorder $reorderService, Checkout $checkout, Cart $cart)
    {
        $prescription = $prescriptions->find($id);
        if ($prescription->getPerson()->getId() !== $this->auth->user()->getId()) {
            abort(403, static::INVALID_PRESCRIPTION_ERROR);
        }
        $newOrder = $reorderService->createForPrescription($prescription);
        $checkout->resume($newOrder, $cart, 'cart.billing');
        return $this->redirect('cart.billing');
    }
}