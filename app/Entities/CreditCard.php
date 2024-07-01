<?php

class CreditCard extends Base\CreditCard
{

	/**
	 * Instantiate a new CreditCard
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
}
