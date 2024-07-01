<?php
namespace App\Http\Actions;

use CouponCodes;
use Illuminate\Http\Request;

class RedeemCouponCodeAction extends AbstractAction
{
    const SUCCESS_MESSAGE = 'Welcome to CosmeticRx! Your coupon code will be automatically applied during the checkout process.';

    const INVALID_MESSAGE = 'This coupon code is invalid.';

    const ALREADY_REDEEMED_MESSAGE = 'This coupon code has already been redeemed.';

    public function __invoke(string $code, Request $request, CouponCodes $couponCodes)
    {
        $couponCode = $couponCodes->find($code);
        $message = static::INVALID_MESSAGE;
        $messageType = 'error';
        $redirect = $request->input('redirect', 'cart');
        if ($couponCode) {
            if ($couponCode->getRedeemed()) {
                $message = static::ALREADY_REDEEMED_MESSAGE;
            } else {
                $messageType = 'success';
                return redirect($redirect)->withCookie(cookie('couponCode', $code, 120))->with($messageType, static::SUCCESS_MESSAGE);
            }
        }
        return redirect($redirect)->with($messageType, $message);
    }
}