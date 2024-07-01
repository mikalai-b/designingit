<?php

namespace App\Http\Requests;

use Campaigns;
use Checkout;
use CouponCodes;
use Illuminate\Contracts\Container\BindingResolutionException;
use Orders;
use Illuminate\Foundation\Http\FormRequest;
use LogicException;
use Order;
use Product;

class ValidateCouponCodeRequest extends FormRequest
{
    const MSG_INVALID_PRODUCT  = 'The code you entered is not valid for the product in your cart.';
    const MSG_INVALID_CODE     = 'The code you entered is invalid. <strong>Please ensure that you have entered the <em>Redemption Code</em> listed in your Groupon voucher</strong>, and not the Groupon number.  If you are still encountering difficulties, please email our support team at <a href="mailto:support@cosmeticrx.com">support@cosmeticrx.com</a>, and we will be happy to assist you.';
    const MSG_ALREADY_REDEEMED = 'The code you entered has already been redeemed. Please contact us at <a href="mailto:support@cosmeticrx.com">support@cosmeticrx.com</a> if you believe this is an error.';
    const MSG_EXPIRED_CODE     = 'The code you entered has expired. Please contact us at <a href="mailto:support@cosmeticrx.com">support@cosmeticrx.com</a> if you believe this is an error.';
    const MSG_FUTURE_CODE      = 'The code you entered is not yet valid. Please contact us at <a href="mailto:support@cosmeticrx.com">support@cosmeticrx.com</a> if you believe this is an error.';

    const MSG_INVALID_CODE_PRESCRIBER = 'The code you entered is not valid.';
    const MSG_INVALID_PRODUCT_PRESCRIBER = 'The code you entered is not valid for any products associated with this consultation.';

    protected $couponCodeRepository; 

    protected $campaignRepository;

    protected $checkout;

    public function __construct(CouponCodes $couponCodeRepository, Campaigns $campaignRepository, Checkout $checkout)
    {
        $this->couponCodeRepository = $couponCodeRepository;
        $this->campaignRepository = $campaignRepository;
        $this->checkout = $checkout;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required'
        ];
    }

    /**
     * Get the order. If the validation is being run on the prescriber
     * side, we'll also have an order_id and we'll get the order from
     * the orders table. Otherwise, we should have an active checkout
     * so we'll grab it from there.
     * 
     * @return Order | null
     */
    public function getOrder()
    {
        if ($this->input('mode') === 'prescriber') {
            return app()->make(Orders::class)->find($this->route('id'));
        }
        return app()->make(Checkout::class)->getCurrentOrder();
    }

    /**
     * @return Product | null
     */
    private function getProduct()
    {
        $order = $this->getOrder();
        if ($order) {
            return $order->getFirstProduct();
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $code = $this->couponCodeRepository->find($this->input('code'));
            $order = $this->getOrder();

            if (!$code) {
                $validator->errors()->add('code', $this->getInvalidCodeMessage());
                return;
            }
            if ($code->getRedeemed()) {
                $validator->errors()->add('code', static::MSG_ALREADY_REDEEMED);
                return;
            }
            if (!$order->codeIsValidForLineItems($code)) {
                $validator->errors()->add('code', $this->getInvalidProductMessage());
                return;
            }
            $campaign = $code->getCampaign();
            if ($campaign) {
                if ($campaign->isExpired()) {
                    $validator->errors()->add('code', static::MSG_EXPIRED_CODE);
                } elseif ($campaign->isNotYetActive()) {
                    $validator->errors()->add('code', static::MSG_FUTURE_CODE);
                }
            }
        });
    }

    /**
     * @return bool 
     */
    private function prescriberMode()
    {
        if ($this->input('mode') === 'prescriber') {
            return true;
        }
        return false;
    }

    private function getInvalidCodeMessage()
    {
        if ($this->prescriberMode()) {
            return static::MSG_INVALID_CODE_PRESCRIBER;
        }
        $product = $this->getProduct();
        
        if ($product) {
            return $product->getInvalidCodeMessage() ?: static::MSG_INVALID_CODE;
        }
        return static::MSG_INVALID_CODE;
    }

    private function getInvalidProductMessage()
    {
        return $this->prescriberMode() ? static::MSG_INVALID_PRODUCT_PRESCRIBER : static::MSG_INVALID_PRODUCT;
    }
}
