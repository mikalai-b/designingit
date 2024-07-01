<?php
namespace App\Http\Actions\Cart;

use Products;
use Checkout;
use Providers;
use OrderFormInspector;
use App\Exceptions;
use App\Http\Actions\AbstractAction;
use App\Services\Cart;
use Illuminate\Http\Request;

/**
 *
 */
class AddOrder extends AbstractAction
{
    const MSG_BAD_PROVIDER = 'We were unable to find a provider for your location.';

    /**
     *
     */
    public function __invoke(Cart $cart, Checkout $checkout, Providers $providers, OrderFormInspector $inspector, Request $request)
    {
        $person       = $this->auth->user();
        $order        = $checkout->getCurrentOrder();
        $alt_products = $checkout->getProducts()->findSuggestions($cart);
        $active_step  = 'cart.order';
        $selectedProductInfo = self::getSelectedProduct($cart);

        if ($request->cookie('couponCode')) {
            $order->setCouponCode($request->cookie('couponCode'));
        }

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
                $inspector->setCheckout($checkout);
                $inspector->run($this->request->all());
                $checkout->begin($person, $cart, $this->request->input());

                return $this->redirect($checkout->getNextStep($active_step));

            } catch (Exceptions\ValidationException $e) {
                $errors = $e->getMessages();

                $this->session->flash('error', $e->getMessage());

                return $this->render('pages.cart.order', 400, get_defined_vars());
            }
        }

        return $this->render('pages.cart.order', 200, get_defined_vars());
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
