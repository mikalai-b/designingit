<?php

/**
 *
 */
class Order extends Base\Order
{
    const STATUS_NEW     = 'New';     // Freshly created by a user
    const STATUS_PENDING = 'Pending'; // Waiting for Consultation to be Completed
    const STATUS_OPEN    = 'Open';    // Consultation Completed
    const STATUS_CLOSED  = 'Closed';

    /**
     * Instantiate a new Order
     */
    public function __construct()
    {
        $this->dateCreated = new \DateTime();

        return parent::__construct();
    }


    /**
     *
     */
    public function __toString()
    {
        if (!$this->getConsultation()) {
            return 'Incomplete Consultation';
        }

        $products = $this->getLineItems()->map(function($line_item) {
            return $line_item->getProduct()->getName();
        });

        return sprintf('Consultation for %s', implode(', ', $products->getValues()));
    }


    /**
     *
     */
    public function getProducts()
    {
        return $this->getLineItems()->map(function($line_item) {
            return $line_item->getProduct();
        });
    }


    /**
     *
     */
    public function getFirstProduct()
    {
        $lineItem = $this->getLineItems()->first();
        if ($lineItem) {
            return $lineItem->getProduct();
        }
        return new Product;
    }


    /**
     *
     */
    public function getProductNames()
    {
        return $this->getLineItems()->map(function($line_item) {
            return $line_item->getProduct()->getName();
        });
    }


    /**
     *
     */
    public function isCreditCard(CreditCard $card)
    {
        return $this->getCreditCard() === $card;
    }


    /**
     *
     */
    public function isOpen()
    {
        return $this->getStatus() == self::STATUS_OPEN;
    }


    /**
     *
     */
    public function isClosed()
    {
        return $this->getStatus() == self::STATUS_CLOSED;
    }


    /**
     *
     */
    public function isState(State $state, State $default = NULL)
    {
        if ($this->getState()) {
            return $state === $this->getState();
        } elseif ($default) {
            return $state === $default;
        } else {
            return FALSE;
        }
    }


    /**
     *
     */
    public function setClosed()
    {
        $this->setStatus(static::STATUS_CLOSED);
    }


    /**
     *
     */
    public function setOpen()
    {
        $this->setStatus(static::STATUS_OPEN);
    }


    /**
     *
     */
    public function setPending()
    {
        $this->setStatus(static::STATUS_PENDING);
    }


    /**
     *
     */
    public function codeIsValidForLineItems(CouponCode $code)
    {
        $campaign = $code->getCampaign();
        if ($campaign) {
            $offers = $campaign->getOffers();
            if ($offers) {
                foreach ($this->getLineItems() as $lineItem) {
                    foreach ($offers as $offer) {
                        if ($lineItem->getProduct()->getId() === $offer->getProduct()->getId()) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }


    /**
     *
     */
    public function getProductType()
    {
        foreach ($this->getLineItems() as $lineItem) {
            $product = $lineItem->getProduct();
            if ($product) {
                return $product->getType();
            }
        }
    }

    public function getProductCategorySlug()
    {
        foreach ($this->getLineItems() as $lineItem) {
            $product = $lineItem->getProduct();
            if ($product) {
                return $product->getCategory()->getSlug();
            }
        }
    }

    public function loadPricingForCampaign(Campaign $campaign)
    {
        foreach ($this->getLineItems() as $lineItem) {
            $lineItem->setPrice($campaign->getPriceForProduct($lineItem->getProduct()));
            $lineItem->setFirstShipmentPrice($campaign->getFirstShipmentPriceForProduct($lineItem->getProduct()));
            $lineItem->setSecondShipmentPrice($campaign->getSecondShipmentPriceForProduct($lineItem->getProduct()));
        }
    }


    /**
     *
     */
    public function loadGrouponPricing()
    {
        foreach ($this->getLineItems() as $lineItem) {
            $lineItem->setPrice($lineItem->getProduct()->getGrouponPrice());
        }
    }


    /**
     *
     */
    public function loadNormalPricing()
    {
        foreach ($this->getLineItems() as $lineItem) {
            $lineItem->setPrice($lineItem->getProduct()->getPrice());
            $lineItem->setFirstShipmentPrice($lineItem->getProduct()->getPrice());
            $lineItem->setSecondShipmentPrice($lineItem->getProduct()->getPrice());
        }
    }


    /**
     *
     */
    public function hasChildren()
    {
        return $this->getChildren()->count() > 0;
    }


    /**
     *
     */
    public function getFirstShipmentPrice()
    {
        $lineItem = $this->getLineItems()->first();
        if ($lineItem) {
            return $lineItem->getFirstShipmentPrice();
        }
    }

    /**
     *
     */
    public function getRefillPrice()
    {
        $lineItem = $this->getLineItems()->first();
        if ($lineItem) {
            return $lineItem->getPrice();
        }
    }

    /**
     * @return \CouponCode | null
     */
    public function getCouponCodeObject()
    {
        if ($this->getCouponCode()) {
            $couponCodeRepository = app()->make(CouponCodes::class);
            return $couponCodeRepository->find($this->getCouponCode());
        }
        return null;
    }
}
