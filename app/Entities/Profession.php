<?php

/**
 *
 */
class Profession extends Base\Profession
{
    /**
     * Instantiate a new Professions
     */
    public function __construct()
    {
        $this->dateCreated = new DateTime();
    }

}
