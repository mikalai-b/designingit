<?php
namespace App\Http\Actions\API\CouponCodes;

use App\Http\Actions\AbstractAction;
use App\Http\Requests\ValidateCouponCodeRequest;
use CouponCodes;

class ApplyCouponCodeToOrderAction extends AbstractAction
{
    public function __invoke(ValidateCouponCodeRequest $request, CouponCodes $couponCodesRepository)
    {
        $order = $request->getOrder();
        $code = $couponCodesRepository->find($request->input('code'));
        $campaign = $code->getCampaign();
        $order->setCouponCode($code->getCode());
        $order->loadPricingForCampaign($campaign);
        $code->redeem();
        return response()->json([
            'valid' => true,
        ]);
    }
}