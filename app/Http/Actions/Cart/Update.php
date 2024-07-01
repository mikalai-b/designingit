<?php
namespace App\Http\Actions\Cart;

use Products;
use App\Http\Actions\AbstractAction;
use Checkout;
use App\Services\Cart;

/**
 *
 */
class Update extends AbstractAction
{
    /**
     * @var string
     */
    const MSG_REMOVE_SUCCESS = '%s was successfully removed from your cart.';

    /**
     *
     */
    public function __invoke(Cart $cart, Products $products, Checkout $checkout, $item)
    {
        $action  = $this->request->query('action', 'remove');
        $product = $products->find($cart->get($item)->id);

        if ($action == 'remove') {
            $checkout->reset($cart);
            $this->session->flash('success', sprintf(static::MSG_REMOVE_SUCCESS, $product));
        }

        return $this->redirect('cart');
    }
}
