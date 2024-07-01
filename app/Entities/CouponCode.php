<?php

/**
 *
 */
class CouponCode extends Base\CouponCode
{
    /**
     * Instantiate a new CouponCode
     */
    public function __construct()
    {
        $this->redeemed = FALSE;

        return parent::__construct();
    }

    /**
     * 
     */
    public function addProduct(Product $product)
    {
        if ($this->products->contains($product)) {
            return;
        }
        $this->products->add($product);
    }
    
    /**
     * 
     */
    public function redeem()
    {
        if (!$this->getUnlimited()) {
            $this->setRedeemed(1);
            $this->setDateRedeemed(new \DateTime());
        }
    }
    
    /**
     * 
     */
    public function unRedeem()
    {
        if (!$this->getUnlimited()) {
            $this->setRedeemed(0);
            $this->setDateRedeemed(null);
        }
    }

    /**
     * 
     * @return bool 
     */
    public function isClosed(): bool
    {
        if (is_null($this->getRedemptionLimit())) {
            return false;
        }
        return $this->getRedemptionCount() >= $this->getRedemptionLimit();
    }

}
