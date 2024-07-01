<?php

/**
 *
 */
class ProductType extends Base\ProductType
{
    /**
     * Instantiate a new ProductType
     */
    public function __construct()
    {
        return parent::__construct();
    }


    /**
     *
     */
    public function __toString()
    {
        return $this->getName();
    }


    /**
     *
     */
    public function getDefaultRefills()
    {
        return parent::getDefaultRefills() ?? 6;
    }


    /**
     *
     */
    public function getDefaultExpiration()
    {
        return parent::getDefaultExpiration() ?? 12;
    }
}
