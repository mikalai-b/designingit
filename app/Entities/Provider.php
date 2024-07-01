<?php

/**
 *
 */
class Provider extends Base\Provider
{
    /**
     * Instantiate a new Provider
     */
    public function __construct()
    {
        $this->dateCreated = new DateTime();

        return parent::__construct();
    }


    /**
     *
     */
    public function hasState(State $state)
    {
        return $this->getStates()->contains($state);
    }
}
