<?php

class ProductCategory extends Base\ProductCategory
{
    /**
     * Instantiate a new ProductCategory
     */
    public function __construct()
    {
        $this->dateCreated = new \DateTime();

        return parent::__construct();
    }
}
