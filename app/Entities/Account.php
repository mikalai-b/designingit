<?php

/**
 *
 */
class Account extends Base\Account
{
    /**
     * Instantiate a new Account
     */
    public function __construct()
    {
        $this->dateCreated = new DateTime();

        return parent::__construct();
    }

}
