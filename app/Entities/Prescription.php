<?php

use Illuminate\Support\Carbon;

/**
 *
 */
class Prescription extends Base\Prescription
{
    const STATUS_ACTIVE   = 'Active';
    const STATUS_PAUSED   = 'Paused';
    const STATUS_CANCELED = 'Canceled';
    const STATUS_EXPIRED  = 'Expired';

    /**
     * Instantiate a new Prescription
     */
    public function __construct()
    {
        $this->filled           = 0;
        $this->paused           = 0;
        $this->resupplyAttempts = 0;
        $this->dateCreated      = new \DateTime();
        $this->dateStart        = new \DateTime();

        return parent::__construct();
    }


    /**
     *
     */
    public function __toString()
    {
        $product = $this->getLineItem()->getProduct();

        return sprintf(
            '%s %s %s',
            $product->getName(),
            $product->getQuantity(),
            $product->getStrength()
        );
    }


    /**
     *
     */
    public function getOrderNumber()
    {
        return md5('CRX::' . __CLASS__ . '::' . $this->getId());
    }


    /**
     *
     */
    public function getPeriod()
    {
        return $this->getLineItem()->getPeriod();
    }


    /**
     *
     */
    public function getProduct()
    {
        return $this->getLineItem()->getProduct();
    }


    /**
     *
     */
    public function getProductType()
    {
        return $this->getProduct()->getType();
    }


    /**
     * 
     */
    public function getStateId(): string
    {
        return $this->getLineItem()->getOrder()->getState()->getId() ?? null;
    }


    /**
     * Get the number of remaining refills
     *
     * @return int Prescribed refills + initial fill, minus the number of times filled
     */
    public function getRemainingRefills()
    {
        return ($this->getRefills() + 1) - $this->getFilled();
    }


    /**
     * Get the next refill date.
     *
     * @return mixed NULL if no more refills are available, DateTime containing next refill date otherwise
     */
    public function getNextRefillDate()
    {
        if ($this->getRemainingRefills() == 0) {
            return NULL;
        }

        $today       = new \DateTime('today');
        $period      = $this->getPeriod();
        $refill_date = $this->getDateLastRefilled()
            ? $this->getDateLastRefilled()
            : $this->getDateStart();

        while ($today->format('Y-m-d') >= $refill_date->format('Y-m-d')) {
            $refill_date->modify('+' . $period . ' days');
        }

        if ($refill_date >= $this->getDateEnd()) {
            return $this->getDateEnd();
        }

        return $refill_date;
    }


    /**
     * Determine if a card is the card used on the prescription
     *
     * @param CreditCard $card The card to check
     * @return bool TRUE if the $card is the card on the prescription, FALSE otherwise
     */
    public function isCreditCard(CreditCard $card)
    {
        return $this->getCreditCard() === $card;
    }


    /**
     *
     */
    public function getPaused()
    {
        return $this->getStatus() === static::STATUS_PAUSED;
    }


    /**
     *
     */
    public function getCanceled()
    {
        return $this->getStatus() === static::STATUS_CANCELED;
    }


    /**
     * This method gets the status field, but will return "Expired"
     * if we're past the prescription's expiration date.
     */
    public function getStatusIncludingExpired()
    {
        $status = $this->getStatus();
        if ($status === static::STATUS_ACTIVE && $this->isExpired()) {
            return static::STATUS_EXPIRED;
        }
        return $status;
    }


    /**
     *
     */
    public function isExpired()
    {
        $today = new \DateTime('today');
        $dateEnd = $this->getDateEnd();
        return ($today->format('Y-m-d') > $dateEnd->format('Y-m-d'));
    }

    /**
     * 
     */
    public function isEligibleForReorder(): bool
    {
        $activeStates = (app()->make('States'))->getActiveStatesAsArray();
        if (!in_array($this->getStateId(), $activeStates)) {
            return false;
        }
        if ($this->getOrder()->hasChildren()) {
            return false;
        }
        if ($this->getFilled() === $this->getRefills() + 1) {
            return Carbon::instance($this->getDateLastRefilled())
                ->addDays($this->getLineItem()->getPeriod())
                ->lte(Carbon::today());
        } elseif ($this->isExpired()) {
            return Carbon::instance($this->getDateEnd())
                ->addDays($this->getLineItem()->getPeriod())
                ->lte(Carbon::today());
        }
        return false;
    }


    /**
     *
     */
    public function resetResupplyAttempts()
    {
        $this->setResupplyAttempts(0);
    }

    /**
     * 
     */
    public function getOrder(): Order
    {
        return $this->getLineItem()->getOrder();
    }

    /**
     * 
     */
    public function getPerson(): Person
    {
        return $this->getLineItem()->getOrder()->getPerson();
    }
}
