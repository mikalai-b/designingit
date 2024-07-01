<?php

/**
 *
 */
class State extends Base\State
{
    /**
     * Instantiate a new State
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
