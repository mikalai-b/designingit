<?php

namespace App\Policies;

use Person;
use Consultation;

class ConsultationPolicy extends AbstractPolicy
{
    /**
     *
     */
    public function view(Person $user, Consultation $consultation)
    {
        $order = $consultation->getOrder();

        return $user === $order->getProvider() || $user === $order->getPerson();
    }


    /**
     *
     */
    public function complete(Person $user, Consultation $consultation)
    {
        if ($consultation->isCompleted()) {
            return FALSE;
        }

        return $user->hasRoleByName('Provider') && $consultation->getOrder()->getProvider() === $user;
    }
}
