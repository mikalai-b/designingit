<?php

use Illuminate\Session\SessionManager;
use App\Exceptions\ValidationException;
use App\Notifications\OrderConfirmation;
use App\Services\Cart;
use Omnipay\Common\GatewayInterface as Gateway;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class Checkout
{
    /**
     *
     */
    static protected $steps = [
        // 'cart'              => 'Create Account',
        'cart.order'        => 'Prescription Info',
        'cart.billing'      => 'Shipping / Billing',
        'cart.consultation' => 'Complete Profile',
        'cart.photos'       => 'Submit Photo'
    ];


    /**
     *
     */
    protected $cards = NULL;


    /**
     *
     */
    protected $order = NULL;


    /**
     *
     */
    protected $orders = NULL;


    /**
     *
     */
    protected $session = NULL;


    /**
     *
     */
    protected $states = NULL;


    /**
     *
     */
    protected $couponCodes = NULL;

    /**
     *
     */
    protected $couponCode = NULL;


    /**
     *
     */
    public function __construct(Gateway $gateway, SessionManager $session, Accounts $accounts, Products $products, Providers $providers, Orders $orders, States $states, CreditCards $cards, CouponCodes $couponCodes)
    {
        $this->gateway   = $gateway;
        $this->session   = $session;
        $this->accounts  = $accounts;
        $this->products  = $products;
        $this->providers = $providers;
        $this->orders    = $orders;
        $this->states    = $states;
        $this->cards     = $cards;
        $this->couponCodes = $couponCodes;
    }


    /**
     *
     */
    public function begin(Person $person, Cart $cart, $input = null)
    {
        $provider = NULL;
        $order    = $this->getCurrentOrder();

        //
        // Associate State
        //

        if (!empty($input['state'])) {
            $state = $this->getStates()->findOneById($input['state']);

            if (!empty($input['provider'])) {
                $provider = $this->providers->findOneByPerson($input['provider']);
            } else {
                $provider = $this->providers->findForState($state)->first();
            }
        }

        $order->setProvider($provider ? $provider->getPerson() : NULL);
        $order->setPerson($person);
        $order->setState($state);

        //
        // Remove all line items
        //

        foreach ($order->getLineItems() as $line_item) {
            $order->getLineItems()->removeElement($line_item);
        }

        foreach ($cart->content() as $item) {
            $product   = $this->getProducts()->find($item->id);
            $line_item = $this->orders->getLineItems()->create();

            $line_item->setPeriod($input['periods'][$product->getId()->toString()] ?: NULL);
            $line_item->setProduct($product);
            $line_item->setOrder($order);
            $line_item->setPrice($item->price);
            $line_item->setFirstShipmentPrice($item->price);
            $line_item->setSecondShipmentPrice($item->price);

            $order->getLineItems()->add($line_item);
            // If quantity is greater than 1, add additional line items
            if ($item->qty > 1) {
                for ($i = 1; $i < $item->qty; $i++) {
                    $additional_line_item = $this->orders->getLineItems()->create();
                    $additional_line_item->setPeriod($input['periods'][$product->getId()->toString()] ?: null);
                    $additional_line_item->setProduct($product);
                    $additional_line_item->setOrder($order);
                    $additional_line_item->setPrice($item->price);
                    $additional_line_item->setFirstShipmentPrice($item->price);
                    $additional_line_item->setSecondShipmentPrice($item->price);

                    // Add the additional line item to the order
                    $order->getLineItems()->add($additional_line_item);
                }
            }
        }

        $this->orders->store($order, TRUE);
        $this->session->put('cart.order', $order->getId());
    }


    /**
     *
     */
    public function resume(Order $order, Cart $cart, $step = null)
    {
        $cart->destroy();
        $this->session->put('cart.order', $order->getId());
        $this->session->put('cart.step', $step ?: $this->getNextStepForOrder($order));

        foreach ($order->getLineItems() as $lineItem) {
            $product = $lineItem->getProduct();
            if ($lineItem->getPrice() && $lineItem->getPrice() < $product->getPrice()) {
                $product->setOverridePrice($lineItem->getPrice());
            }
            $cart->add($product);
        }
    }


    /**
     *
     */
    public function getItemsForCart(Cart $cart)
    {
        $items = [];
        foreach ($cart->content() as $item) {
            $product = $this->getProducts()->find($item->id);
            $item->product = $product->toArray();
            $items[] = $item;
        }
        return $items;
    }


    /**
     *
     */
    private function getNextStepForOrder($order)
    {
        if (!$order->getCreditCard()) {
            return 'cart.billing';
        }
        if (!$order->getConsultation()) {
            return 'cart.consultation';
        }
        return 'cart.photos';
    }


    /**
     *
     */
    public function fillAddressInfo(Cart $cart, $input)
    {
        $order  = $this->getCurrentOrder();
        $person = $order->getPerson();

        $person->setFirstName($input['firstName']);
        $person->setLastName($input['lastName']);
        $person->setAddressLine1($input['addressLine1']);
        $person->setAddressLine2($input['addressLine2']);
        $person->setPhone($input['phone'] ?? null);
        $person->setCity($input['city']);
        $person->setGender($input['gender']);

        if (!empty($input['dateOfBirth']) && strtotime($input['dateOfBirth'])) {
            $person->setDateOfBirth(new \DateTime($input['dateOfBirth']));
        }

        if (!empty($input['state'])) {
            $person->setState($this->states->find($input['state']));
        }

        $person->setPostalCode($input['postalCode']);
    }

    /**
     *
     */
    public function bill(Cart $cart, $input)
    {
        $order  = $this->getCurrentOrder();
        $card   = $this->charge($input);

        if ($card) {
            $order->setCreditCard($card);
        }

        $this->orders->store($order, TRUE);

        return $card;
    }


    /**
     * Create a credit card from stripe input
     *
     * @param array $input The form input from stripe
     * @param Person $person The person we're charging (if not provided will use current order)
     * @return CreditCard The credit card from stripe input (existing if id is not new), NULL if no response, FALSE on failure
     */
    public function charge(array $input, Person $person = NULL)
    {
        if (empty($input['card']['id'])) {
            return NULL;
        }

        if ($input['card']['id'] != 'new') {
            return $this->cards->findIfAuthorized($input['card']['id']);
        }

        if (empty($input['card']['token'])) {
            return FALSE;
        }

        if (!$person) {
            $person = $this->getCurrentOrder()->getPerson();
        }

        $card = $this->cards->create();

        try {
            if (!$person->getAccount()->getCustomer() || preg_match('/^cus_/', $person->getAccount()->getCustomer())) {
                return $this->createCustomerProfileWithCard($input, $person, $card);

            } else {
                return $this->createOrUpdateCard($input, $person, $card);

            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }


    /**
     *
     */
    private function createCustomerProfileWithCard(array $input, Person $person, CreditCard $card)
    {
        $request = $this->gateway->createCard([
            'opaqueDataDescriptor' => $input['card']['descriptor'],
            'opaqueDataValue'      => $input['card']['token'],
            'name'                 => $person->getFirstName(),
            'email'                => $person->getEmail(),
            'customerId'           => $person->getId(),
            'customerType'         => 'individual',
            'forceCardUpdate'      => TRUE,
            'card' => [
                'billingLastName'  => $person->getLastName(),
                'billingFirstName' => $person->getFirstName(),
                'billingPostcode'  => $person->getPostalCode(),
                'billingAddress1'  => $person->getAddressLine1(),
                'billingCity'      => $person->getCity(),
                'billingState'     => $person->getState() ? $person->getState()->getId() : null,
            ]
        ]);

        if (!$response = $request->send()) {
            throw new \Exception('Could not connect to payment gateway.');
        }

        if (!$response->isSuccessful()) {
            if ($response->getReasonCode() == 'E00039' && preg_match('#ID\s+([0-9]+)\s+#', $response->getMessage(), $matches)) {
                //
                // Update the person's customer profile based on failed response ID cause auth.net
                // is dumb.
                //

                $person->getAccount()->setCustomer($matches[1]);
                $this->accounts->store($person->getAccount(), TRUE);
            }

            throw new \Exception(sprintf(
                'Response error %s: %s',
                $response->getCode(),
                $response->getMessage()
            ));
        }

        $data = $response->getData();

        $this->setCardAttributesFromPaymentProfile($card, $data['paymentProfile']);
        $person->getAccount()->setCustomer($data['paymentProfile']['customerProfileId']);

        return $card;
    }


    /**
     *
     */
    private function createOrUpdateCard(array $input, Person $person, CreditCard $card)
    {
        $profile = $person->getAccount()->getCustomer();
        $request = $this->gateway->getCustomerProfile([
            'customerProfileId' => $profile
        ]);

        if (!$response = $request->send()) {
            throw new \Exception('Could not connect to payment gateway.');
        }

        if (!$response->isSuccessful()) {
            throw new \Exception(sprintf(
                'Response error %s: %s',
                $response->getCode(),
                $response->getMessage()
            ));
        }

        $payment = $response->getMatchingPaymentProfileId(substr($input['card']['name'], -4));

        if ($payment) {
            $request = $this->gateway->updateCard([
                'opaqueDataDescriptor'     => $input['card']['descriptor'],
                'opaqueDataValue'          => $input['card']['token'],
                'customerId'               => $person->getId(),
                'customerType'             => 'individual',
                'forceCardUpdate'          => TRUE,
                'customerProfileId'        => $person->getAccount()->getCustomer(),
                'customerPaymentProfileId' => $payment,
                'card' => [
                    'billingLastName'  => $person->getLastName(),
                    'billingFirstName' => $person->getFirstName(),
                    'billingPostcode'  => $person->getPostalCode(),
                    'billingAddress1'  => $person->getAddressLine1(),
                    'billingCity'      => $person->getCity(),
                    'billingState'     => $person->getState() ? $person->getState()->getId() : null,
                ]
            ]);

            if (!$response = $request->send()) {
                throw new \Exception('Could not connect to payment gateway.');
            }

            if (!$response->isSuccessful()) {
                throw new \Exception(sprintf(
                    'Response error %s: %s',
                    $response->getCode(),
                    $response->getMessage()
                ));
            }

            return $this->cards->findOneBy(['token'=>$payment]);

        } else {
            $request = $this->gateway->createAdditionalCard([
                'opaqueDataDescriptor' => $input['card']['descriptor'],
                'opaqueDataValue'      => $input['card']['token'],
                'customerId'           => $person->getId(),
                'customerType'         => 'individual',
                'forceCardUpdate'      => TRUE,
                'customerProfileId'    => $person->getAccount()->getCustomer(),
                'card' => [
                    'billingLastName'  => $person->getLastName(),
                    'billingFirstName' => $person->getFirstName(),
                    'billingPostcode'  => $person->getPostalCode(),
                    'billingAddress1'  => $person->getAddressLine1(),
                    'billingCity'      => $person->getCity(),
                    'billingState'     => $person->getState() ? $person->getState()->getId() : null,
                ]
            ]);

            if (!$response = $request->send()) {
                throw new \Exception('Could not connect to payment gateway.');
            }

            if (!$response->isSuccessful()) {
                throw new \Exception(sprintf(
                    'Response error %s: %s',
                    $response->getCode(),
                    $response->getMessage()
                ));
            }

            $card_data = $response->getData();
            $payment   = $card_data['customerPaymentProfileId'];
        }

        $request = $this->gateway->getPaymentProfile([
            'customerProfileId'        => $profile,
            'customerPaymentProfileId' => $payment,
            "includeIssuerInfo"        => true
        ]);

        if (!$response = $request->send()) {
            throw new \Exception('Could not connect to payment gateway.');
        }

        if (!$response->isSuccessful()) {
            throw new \Exception(sprintf(
                'Response error %s: %s',
                $response->getCode(),
                $response->getMessage()
            ));
        }

        $this->setCardAttributesFromPaymentProfile($card, $response->getData()['paymentProfile']);

        return $card;
    }


    /**
     *
     */
    private function setCardAttributesFromPaymentProfile($card, $paymentProfile)
    {
        $card->setCustomer($paymentProfile['customerProfileId']);
        $card->setToken($paymentProfile['customerPaymentProfileId']);
        $card->setName(sprintf(
            '%s Ending In %s',
            $paymentProfile['payment']['creditCard']['cardType'],
            $paymentProfile['payment']['creditCard']['cardNumber']
        ));
        return $card;
    }


    /**
     *
     */
    public function reset(Cart $cart)
    {
        $cart->destroy();
        $this->session->put('cart.order', NULL);
        $this->session->put('cart.step', NULL);
    }


    /**
     *
     */
    public function complete(Cart $cart)
    {
        $person = $this->getCurrentOrder()->getPerson();
        if ($person) {
            $person->notify(new OrderConfirmation());
        }
        if ($code = $this->getCurrentOrder()->getCouponCode()) {
            $couponCode = $this->couponCodes->find($code);
            if ($couponCode) {
                $couponCode->redeem();
            }
        }
        $cart->destroy();
        $this->session->put('cart.order', NULL);
        $this->session->put('cart.step', NULL);
    }


    /**
     *
     */
    public function getCards(Person $person = NULL)
    {
        if (!$person) {
            $person = $this->getCurrentOrder()->getPerson();
        }

        return $this->cards->findForPerson($person);
    }


    /**
     *
     */
    public function getCurrentOrder()
    {
        if (!$this->order) {
            $current_order_id = $this->session->get('cart.order');

            if ($current_order_id) {
                $this->order = $this->orders->findOneById($current_order_id);
            }

            if (!$this->order) {
                $this->order = $this->orders->create();
                $this->order->setCouponCode($this->couponCode);
            }
        }

        return $this->order;
    }

    /**
     *
     */
    public static function getStepNames()
    {
        return array_keys(static::$steps);
    }

    /**
     *
     */
    public function getCurrentStep()
    {
        $stepNames = static::getStepNames();
        if (!$this->session->has('cart.step')) {
            $this->session->put('cart.step', reset($stepNames));
        }

        return $this->session->get('cart.step');
    }


    /**
     *
     */
    public function getNextStep($active_step)
    {
        $stepNames = static::getStepNames();
        if ($active_step != $this->getCurrentStep()) {
            return $this->getCurrentStep();
        }

        foreach ($stepNames as $i => $step) {
            if (isset($stepNames[$i + 1]) && $step == $this->getCurrentStep()) {
                $this->session->put('cart.step', $stepNames[$i + 1]);

                return $stepNames[$i + 1];
            }
        }

        return 'dashboard';
    }


    /**
     *
     */
    public function setCurrentStepToFirst()
    {
        $stepNames = static::getStepNames();
        $this->session->put('cart.step', $stepNames[0]);
    }

    public function setCurrentStepToPhotos()
    {
        $stepNames = static::getStepNames();
        $this->session->put('cart.step', $stepNames[3]);
    }

    /**
     *
     */
    public function getProducts()
    {
        return $this->products;
    }


    /**
     *
     */
    public function getStates()
    {
        return $this->states;
    }


    /**
     *
     */
    public function getSteps()
    {
        return static::$steps;
    }


    /**
     *
     */
    public function hasCompletedStep($step)
    {
        $stepNames = static::getStepNames();
        $step_pos = array_search($step, $stepNames);
        $curr_pos = array_search($this->getCurrentStep(), $stepNames);

        return $step_pos < $curr_pos;
    }


    /**
     *
     */
    public function couponCodeIsValid()
    {
        if ($code = $this->getCouponCode()) {
            $code = $this->couponCodes->find($code);
            return ($code && !$code->getRedeemed());
        }
    }


    /**
     *
     */
    public function setCouponCode($v)
    {
        return $this->couponCode = $v;
    }


    /**
     *
     */
    public function getCouponCode()
    {
        return $this->getCurrentOrder() ? $this->getCurrentOrder()->getCouponCode() : $this->couponCode;
    }


    /**
     *
     */
    public function getCouponCodeObject()
    {
        $couponCode = $this->getCouponCode();

        if ($couponCode) {
            return $this->couponCodes->find($couponCode);
        }
    }


    /**
     *
     */
    public function couponCodeIsUnlimited()
    {
        $couponCode = $this->getCouponCodeObject();
        if ($couponCode) {
            return $couponCode->getUnlimited();
        }
    }


    /**
     *
     */
    public function loadGrouponPricing()
    {
        $this->getCurrentOrder()->loadGrouponPricing();
    }


    /**
     *
     */
    public function loadNormalPricing()
    {
        $this->getCurrentOrder()->loadNormalPricing();
    }

    /**
     *
     */
    public function isReorder(): bool
    {
        return $this->getCurrentOrder()->getParent() !== null;
    }

    /**
     *
     */
    public function isEligibleForCoupon(): bool
    {
        return ! $this->isReorder();
    }

    /**
     *
     */
    public function canChooseState(): bool
    {
        if ($this->isReorder()) {
            return $this->getCurrentOrder()->getState() === null;
        }
        return true;
    }
}
