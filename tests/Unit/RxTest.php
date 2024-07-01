<?php

namespace Tests\Unit;

use Consultation;
use Doctrine\Common\Collections\ArrayCollection;
use LineItem;
use Order;
use Prescription;
use Rx;
use Tests\TestCase;
use Tests\Traits\RefreshDoctrineDatabase;

class RxTest extends TestCase
{
    use RefreshDoctrineDatabase;

    private $prescription = null;
    private $order = null;
    private $lineItem = null;
    private $consultation = null;

    /** @test */
    public function it_can_determine_amount_to_bill_for_new_orders()
    {
        $rx = app()->make(Rx::class);
        $this->buildPrescription([], [], ['status' => 'Pending'], ['price' => 60, 'firstShipmentPrice' => 20]);
        $this->assertSame(20, $rx->getAmountToBill($this->prescription));
        
        $this->buildPrescription([], [], ['status' => 'Open'], ['price' => 60, 'firstShipmentPrice' => 20]);
        $this->assertSame(60, $rx->getAmountToBill($this->prescription));
    }

    private function buildPrescription(
        array $prescriptionAttributes = [],
        array $consultationAttributes = [],
        array $orderAttributes = [],
        array $lineItemAttributes = []
    )
    {
        $this->prescription = entity(Prescription::class)->make($prescriptionAttributes);
        $this->consultation = entity(Consultation::class)->make($consultationAttributes);
        $this->order = entity(Order::class)->make($orderAttributes);
        $this->lineItem = entity(LineItem::class)->make($lineItemAttributes);
        $this->lineItem->setOrder($this->order);
        $this->order->setLineItems(new ArrayCollection([$this->lineItem]));
        $this->consultation->setOrder($this->order);
        $this->prescription->setConsultation($this->consultation);
        $this->prescription->setLineItem($this->lineItem);
    }
    
}