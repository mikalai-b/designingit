<?php

namespace App\Http\Actions\API\CouponCodes;

use App\Http\Actions\AbstractAction;
use App\Http\Requests\ValidateCouponCodeRequest;
use Checkout;
use CouponCodes;

class ApplyCouponCodeToCartAction extends AbstractAction
{
    /**
     *
     */
    public function __invoke(ValidateCouponCodeRequest $request, CouponCodes $couponCodes, Checkout $checkout)
    {
        $trimmedCode = trim($request->input('code'));
        $code = $couponCodes->find($trimmedCode);
        $order = $checkout->getCurrentOrder();

        $isUnlimited = $code->getUnlimited();
        $product = $order->getProducts()->first();
        $campaign = $code->getCampaign();

        $messageTemplate = $campaign->getSuccessMessageForProduct($product);
        
        $message = fill_template($messageTemplate, [
            'product.name' => $product->getCustomerFacingName(),
            'product.firstPeriodInMonths' => $product->getFirstPeriodInMonths(),
            'product.grouponPrice' => '$'.sprintf("%01.2f", $product->getGrouponPrice()),
            'product.firstShipmentPrice' => '$'.sprintf('%01.2f', $campaign->getFirstShipmentPriceForProduct($product)),
            'product.price' => '$'.sprintf('%01.2f', $campaign->getPriceForProduct($product)),
            'product.strength' => $product->getStrength(),
            'coupon_code.code' => $code->getCode(),
        ]);

        $order->setCouponCode($trimmedCode);
        $order->loadPricingForCampaign($campaign);
        
        return response()->json([
            'valid' => true,
            'message' => $message,
            'isUnlimited' => $isUnlimited,
        ]);
    }
}