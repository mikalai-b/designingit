<?php

/**
 *
 */
class MessageReceipts extends Repository
{
    /**
     *
     */
    static protected $entity = 'MessageReceipt';


    /**
     *
     */
    static protected $order = ['dateCreated' => 'ASC'];
}
