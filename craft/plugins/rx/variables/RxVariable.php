<?php

namespace Craft;

use CouponCodes;
use Exception;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Cookie\CookieValuePrefix;

class RxVariable
{
    /**
     * So, on the Laravel side, the cookie values get encrypted, so we're
     * running a little bit of logic here to decrypt the value of our
     * couponCode cookie, since the EncryptCookies middleware will not
     * get to run on routes handled by Craft.
     * 
     * @return string|void
     */
    public function getUnlimitedCouponCodeFromCookie()
    {
        $request = app()->make(HttpRequest::class);
        $path = Craft::app()->request->getPath();
        if (in_array($path, ['tretinoin'])) {
            if ($request->hasCookie('couponCode')) {
                $encrypter = app()->make(Encrypter::class);
                try {
                    $couponCode = CookieValuePrefix::remove($encrypter->decrypt($request->cookie('couponCode'), false));
                    $couponCodeObject = app()->make(CouponCodes::class)->find($couponCode);

                    if ($couponCodeObject && $couponCodeObject->getUnlimited()) {
                        return $couponCode;
                    }
                } catch (Exception $e) {

                }
            }
        }
    }
}