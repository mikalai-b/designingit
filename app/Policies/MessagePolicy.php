<?php

namespace App\Policies;

use Person;
use Message;

class MessagePolicy extends AbstractPolicy
{
    /**
     *
     */
    public function view(Person $user, Message $message)
    {
        if ($message->getSender() === $user) {
            return TRUE;
        }

        foreach ($message->getReceipts() as $receipt) {
            if ($receipt->getRecipient() === $user) {
                return TRUE;
            }
        }

        return FALSE;
    }
}
