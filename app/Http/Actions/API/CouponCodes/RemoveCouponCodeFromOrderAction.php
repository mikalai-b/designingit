<?php

namespace App\Http\Actions\API\CouponCodes;

use App\Http\Actions\AbstractAction;
use CouponCodes;
use Illuminate\Http\Request;
use Orders;

class RemoveCouponCodeFromOrderAction extends AbstractAction
{

    /**
     * 
     */
    public function __invoke($id, Orders $orderRepository)
    {
        $order = $orderRepository->find($id);
        $couponCode = $order->getCouponCodeObject();
        if ($couponCode) {
            $couponCode->unRedeem();
            $order->setCouponCode(null);
            $order->loadNormalPricing();
        }
        return response()->json([
            'success' => true
        ]);
    }
}