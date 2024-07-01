<?php
namespace App\Http\Actions\Cart;

use Checkout;
use App\Http\Actions\AbstractAction;
use App\Services\Cart;

/**
 *
 */
class Disabled extends AbstractAction
{
    /**
     *
     */
    public function __invoke(Cart $cart, Checkout $checkout)
    {
        if (env('DISABLE_CART')) {
            return $this->render('pages.cart.disabled', 200, get_defined_vars());
        }
        return redirect()->route('cart');
    }
}
