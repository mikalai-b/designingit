<?php

namespace Tests\Unit\Entities;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LineItem;

class LineItemTest extends TestCase
{
    /** @test */
    public function it_can_get_first_shipment_price_with_fallback()
    {
        $lineItem = entity(LineItem::class)->make([
            'firstShipmentPrice' => 10,
            'price' => 20,
        ]);

        $this->assertSame($lineItem->getFirstShipmentPriceWithFallback(), 10);

        $lineItem->setFirstShipmentPrice(0);
        $this->assertSame($lineItem->getFirstShipmentPriceWithFallback(), 0);

        $lineItem->setFirstShipmentPrice(null);
        $this->assertSame($lineItem->getFirstShipmentPriceWithFallback(), 20);
    }
    
}
