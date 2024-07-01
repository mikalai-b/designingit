<?php

/**
 *
 */
class LineItem extends Base\LineItem
{
    /**
     * Instantiate a new LineItem
     */
    public function __construct()
    {
        return parent::__construct();
    }

    /**
     * 
     * @return float|null 
     */
    public function getFirstShipmentPriceWithFallback()
    {
        return $this->getFirstShipmentPrice() ?? $this->getPrice();
    }
}
