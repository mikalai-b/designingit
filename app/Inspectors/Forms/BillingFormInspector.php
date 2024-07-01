<?php

use Doctrine\ORM\EntityManagerInterface;

use Illuminate\Support\MessageBag;

use Illuminate\Validation;
use Illuminate\Auth\AuthManager;

/**
 *
 */
class BillingFormInspector extends AbstractInspector
{
    /**
     * 
     */
    protected $couponCodes = NULL;

    static protected $rules = [
        'firstName'     => 'required',
        'lastName'      => 'required',
        'dateOfBirth'   => 'required|date',
        'gender'        => 'required',
        'addressLine1'  => 'required',
        'city'          => 'required',
        'state'         => 'required',
        'postalCode'    => 'required'
    ];

    /**
     * 
     */
    public function __construct(Validation\Factory $validator_factory, MessageBag $messages, EntityManagerInterface $em, AuthManager $auth, CouponCodes $couponCodes) {
        $this->couponCodes = $couponCodes;
        parent::__construct($validator_factory, $messages, $em, $auth);
    }


    /**
     *
     */
    public function validate($data, $card = NULL)
    {
        if ($card === NULL) {
            $this->messages->add('card', 'We had a problem submitting your payment information, please try again later.');
        }

        if ($card === FALSE) {
            $this->messages->add('card', 'We had a problem validating your card at this time, please verify your information and try again.');
        }

        if (($data['couponCode'] ?? null) && !$this->couponCodeIsValid($data['couponCode'])) {
            $this->messages->add('couponCode', 'The coupon code you entered is invalid.');
        }
    }


    /**
     *
     */
    public function couponCodeIsValid($code)
    {
        $code = $this->couponCodes->find($code);
        return ($code && !$code->getRedeemed());
    }
}
