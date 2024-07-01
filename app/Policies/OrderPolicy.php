<?php

namespace App\Policies;

use Person;
use Order;

class OrderPolicy extends AbstractPolicy
{
    /**
     *
     */
    public function select(Person $user, Order $order)
    {
        return $user === $order->getProvider() || $user === $order->getPerson();
    }

    /**
     *
     */
    public function manage(Person $user, Order $order)
    {
        return $user->hasRoleByName('Provider');
    }
}
