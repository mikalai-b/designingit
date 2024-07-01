<?php

namespace App\Policies;

use Person;
use Prescription;

class PrescriptionPolicy extends AbstractPolicy
{
    /**
     *
     */
    public function autorefill(Person $user, Prescription $prescription)
    {
        $order = $this->getOrder($prescription);

        return $user === $order->getProvider() || $user === $order->getPerson();
    }


    /**
     *
     */
    public function edit(Person $user, Prescription $prescription)
    {
        $order = $this->getOrder($prescription);

        return $user === $order->getProvider() || $user === $order->getPerson();
    }


    /**
     *
     */
    public function refill(Person $user, Prescription $prescription)
    {
        $order = $this->getOrder($prescription);

        return $user === $order->getProvider() || $user === $order->getPerson();
    }


    /**
     *
     */
    public function view(Person $user, Prescription $prescription)
    {
        $order = $this->getOrder($prescription);

        return $user === $order->getProvider() || $user === $order->getPerson();
    }


    /**
     *
     */
    protected function getOrder(Prescription $prescription)
    {
        return $prescription->getLineItem()->getOrder();
    }
}
