<?php
namespace App\Http\Actions\Cart;

use App\Http\Actions\AbstractAction;
use Checkout;
use App\Services\Cart;
use Order;

/**
 *
 */
class Thanks extends AbstractAction
{

    /**
     *
     */
    public function __invoke(Cart $cart, Checkout $checkout)
    {
        if ($checkout->getCurrentOrder()->getStatus() !== Order::STATUS_PENDING) {
            abort(401);
        }
        $checkout->complete($cart);

        $order = $checkout->getCurrentOrder();
        $product = $order->getProducts()->first();
        $category = $product->getCategory();
        if ($category->getSlug() === "mental-health") {
            return $this->render('pages.cart.mental-health-product-thanks', 200, [
                'order' => $checkout->getCurrentOrder()
            ]);
        } else {
            return $this->render('pages.cart.thanks', 200, [
                'order' => $checkout->getCurrentOrder()
            ]);
        }

    }
}
