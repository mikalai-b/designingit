<?php

namespace App\Services;

use Doctrine\Common\Collections\ArrayCollection;
use LineItem;
use LineItems;
use Order;
use Orders;
use Prescription;

class Reorder
{
    /**
     * @var Orders
     */
    protected $orders;

    /**
     * @var LineItems
     */
    protected $lineItems;

    /**
     *
     */
    public function __construct(Orders $orders, LineItems $lineItems)
    {
        $this->orders = $orders;
        $this->lineItems = $lineItems;
    }

    /**
     *
     */
    public function createForPrescription(Prescription $prescription): Order
    {
        $originalOrder = $prescription->getOrder();
        $originalLineItems = $originalOrder->getLineItems();

        $newOrder = new Order;
        $newOrder->setPerson($originalOrder->getPerson());
        $newOrder->setProvider($originalOrder->getProvider());
        $newOrder->setCreditCard($originalOrder->getCreditCard());
        $newOrder->setState($originalOrder->getState());
        $newOrder->setStatus(Order::STATUS_NEW);
        $newOrder->setParent($originalOrder);

        $this->orders->store($newOrder, true);

        $newLineItems = new ArrayCollection();

        foreach ($originalLineItems as $originalLineItem) {
            $newLineItem = new LineItem;
            $newLineItem->setOrder($newOrder);
            $newLineItem->setProduct($originalLineItem->getProduct());
            $newLineItem->setPeriod($originalLineItem->getPeriod());
            $newLineItem->setPrice($originalLineItem->getPrice());
            $newLineItem->setFirstShipmentPrice($originalLineItem->getPrice());
            $newLineItem->setSecondShipmentPrice($originalLineItem->getPrice());
            $this->lineItems->store($newLineItem, true);

            $newLineItems->add($newLineItem);
        }

        $newOrder->setLineItems($newLineItems);
        return $newOrder;
    }

}