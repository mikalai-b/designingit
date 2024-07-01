<?php

/**
 *
 */
class OrderFormInspector extends AbstractInspector
{
    /**
     * @var \Checkout
     */
    protected $checkout;

    static protected $rules = [
        'state' => 'required'
    ];


    /**
     *
     */
    public function setCheckout(Checkout $checkout)
    {
        $this->checkout = $checkout;
    }


    /**
     *
     */
    public function validate($data)
    {
        $states = $this->em->getRepository(State::class);

        if ($this->checkout->canChooseState()) {
            if (!empty($data['state']) && !$states->find($data['state'])) {
                $this->messages->add('state', 'Please select one of the listed states.');
    
            } elseif (empty($data['provider'])) {
                $this->messages->add('state', 'We do not currently have any doctors serving this state, please try again later.');
            }
        }

        foreach ($data['consent'] ?? [] as $id => $value) {
            if (!$value) {
                $this->messages->add('consent-' . $id, 'Please confirm you have read and understand');
            }
        }

        
    }
}
