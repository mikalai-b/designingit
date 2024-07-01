<?php

/**
 *
 */
class Message extends Base\Message
{
    /**
     * Instantiate a new Message
     */
    public function __construct()
    {
        $this->dateCreated = new \DateTime();

        return parent::__construct();
    }


    /**
     *
     */
    public function getRoot()
    {
        $message = $this;

        while ($message->getParent()) {
            $message = $message->getParent();
        }

        return $message;
    }


    /**
     *
     */
    public function markSeenBy(Person $person)
    {
        foreach ($this->getReceipts() as $receipt) {
            if ($receipt->getRecipient() !== $person) {
                continue;
            }

            if ($receipt->getDateSeen()) {
                continue;
            }

            $receipt->setDateSeen(new \DateTime());
        }
    }
}
