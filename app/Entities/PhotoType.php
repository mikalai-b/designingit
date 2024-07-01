<?php

/**
 *
 */
class PhotoType extends Base\PhotoType
{

    /**
     * Instantiate a new PhotoType
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
        return $this->getName();
    }
}
