<?php

namespace App\Exceptions;

use Illuminate\Support\MessageBag;

/**
 *
 */
class ValidationException extends \Exception
{
    /**
     *
     */
    protected $messages = NULL;


    /**
     *
     */
    public function getMessages()
    {
        return $this->messages;
    }


    /**
     *
     */
    public function setMessages(MessageBag $messages)
    {
        $this->messages = $messages;
    }
}
