<?php

namespace App\Http\Actions\API\CouponCodes;

use App\Http\Actions\AbstractAction;
use Checkout;

class RemoveCouponCodeFromCartAction extends AbstractAction
{
    public function __invoke(Checkout $checkout)
    {
        $order = $checkout->getCurrentOrder();
        $order->setCouponCode(null);
        $checkout->loadNormalPricing();
        return response()->json([
            'success' => true
        ]);
    }
}