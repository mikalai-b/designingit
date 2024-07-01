<?php
namespace App\Http\Actions\Cart;

use Products;
use Orders;
use Checkout;
use App\Exceptions;
use App\Http\Actions\AbstractAction;
use App\Services\Cart;

/**
 *
 */
class Resume extends AbstractAction
{

    /**
     *
     */
    public function __invoke(Cart $cart, Orders $orders, Checkout $checkout, $order)
    {
        try {
            $order = $orders->findIncompleteForPerson($this->auth->user(), $order);
            
            if (!$order) {
                abort(404);
            }
            $checkout->resume($order, $cart);
            return $this->redirect($checkout->getNextStep('cart'));

        } catch (\Exception $e) {
            $this->session->flash('error', $e->getMessage());
        }

        return $this->redirect('cart');
    }
}
