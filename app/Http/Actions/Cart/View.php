<?php
namespace App\Http\Actions\Cart;

use Checkout;
use App\Http\Actions\AbstractAction;
use App\Services\Cart;
use Illuminate\Http\Request;

/**
 *
 */
class View extends AbstractAction
{
    /**
     *
     */
    public function __invoke(Cart $cart, Checkout $checkout, Request $request)
    {
        //$active_step = 'cart';
        $alt_products = $checkout->getProducts()->findSuggestions($cart);

        $firstItemCategory = false;
        if (count($cart->content()) > 0) {
            $firstItem = $cart->content()->first()->toArray();
            $firstItemCategory = $firstItem['options']['request_form'];
        }

        if ($request->cookie('couponCode')) {
            $checkout->setCouponCode($request->cookie('couponCode'));
        }

        if ($this->auth->check() && count($cart->content())) {
            $checkout->setCurrentStepToFirst();
            return $this->redirect($checkout->getNextStep('cart'));
        }


        return $this->render('pages.cart.index', 200, get_defined_vars());
    }
}
