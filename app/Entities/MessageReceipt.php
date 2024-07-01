<?php

/**
 *
 */
class MessageReceipt extends Base\MessageReceipt
{
    /**
     * Instantiate a new MessageReceipt
     */
    public function __construct()
    {
        $this->dateCreated = new \DateTime();
        $this->review      = FALSE;

        return parent::__construct();
    }


    /**
     *
     */
    public function see()
    {
        if ($this->getDateSeen()) {
            return FALSE;
        }

        $this->setDateSeen(new \DateTime());

        return TRUE;
    }

}
