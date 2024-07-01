<?php

namespace Tests\Unit\Entities;

use Campaign;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Tests\TestCase;
use Offer;
use Product;

class CampaignTest extends TestCase
{
    /** @test */
    public function it_can_determine_if_product_is_eligible_for_campaign()
    {
        $campaign = entity(Campaign::class)->make();
        $product = entity(Product::class)->make([
            'dateCreated' => new DateTime('now'),
        ]);
        
        $this->assertFalse($campaign->includesProduct($product));
        $offer = entity(Offer::class)->make([
            'product' => $product
        ]);

        $campaign->setOffers(new ArrayCollection([$offer]));

        $this->assertTrue($campaign->includesProduct($product));
    }

    /** @test */
    public function it_can_get_an_offer_for_a_product()
    {
        $campaign = entity(Campaign::class)->make();
        $product = entity(Product::class)->make([
            'dateCreated' => new DateTime('now'),
        ]);

        $this->assertEmpty($campaign->getOfferForProduct($product));

        $offer = entity(Offer::class)->make([
            'product' => $product
        ]);
        $campaign->setOffers(new ArrayCollection([$offer]));

        $this->assertSame($offer, $campaign->getOfferForProduct($product));
    }

    /** @test */
    public function it_can_get_an_offerered_price_for_a_product()
    {
        $campaign = entity(Campaign::class)->make();
        $product = entity(Product::class)->make([
            'dateCreated' => new DateTime('now'),
        ]);

        $this->assertEmpty($campaign->getOfferedPriceForProduct($product));

        $offer = entity(Offer::class)->make([
            'product' => $product,
            'price' => 123,
        ]);
        $campaign->setOffers(new ArrayCollection([$offer]));

        $this->assertEquals(123, $campaign->getOfferedPriceForProduct($product));
    }

    /** @test */
    public function it_can_get_an_offerered_first_shipment_price_for_a_product()
    {
        $campaign = entity(Campaign::class)->make();
        $product = entity(Product::class)->make([
            'dateCreated' => new DateTime('now'),
        ]);

        $this->assertEmpty($campaign->getOfferedFirstShipmentPriceForProduct($product));

        $offer = entity(Offer::class)->make([
            'product' => $product,
            'firstShipmentPrice' => 123,
        ]);
        $campaign->setOffers(new ArrayCollection([$offer]));

        $this->assertEquals(123, $campaign->getOfferedFirstShipmentPriceForProduct($product));
    }

    /** @test */
    public function it_can_have_a_percentage_based_discount()
    {
        $campaign = entity(Campaign::class)->make([
            'effects' => [
                [
                    'type' => Campaign::EFFECT_TYPE_PERCENT_DISCOUNT,
                    'value' => 50,
                    'context' => Campaign::EFFECT_CONTEXT_ALL_SHIPMENTS,
                ]
            ]
        ]);
        $product = entity(Product::class)->make([
            'price' => 100
        ]);
        $this->withoutExceptionHandling();
        $offer = entity(Offer::class)->make([
            'product' => $product,
            'firstShipmentPrice' => null,
            'price' => null,
        ]);
        $campaign->setOffers(new ArrayCollection([$offer]));

        $this->assertEquals(50, $campaign->getPriceForProduct($product));
    }

    /** @test */
    public function it_can_have_a_percentage_based_discount_for_first_shipment()
    {
        $campaign = entity(Campaign::class)->make([
            'effects' => [
                [
                    'type' => Campaign::EFFECT_TYPE_PERCENT_DISCOUNT,
                    'value' => 50,
                    'context' => Campaign::EFFECT_CONTEXT_FIRST_SHIPMENT,
                ]
            ]
        ]);
        $product = entity(Product::class)->make([
            'price' => 100
        ]);
        $this->withoutExceptionHandling();
        $offer = entity(Offer::class)->make([
            'product' => $product,
            'firstShipmentPrice' => null,
            'price' => null,
        ]);
        $campaign->setOffers(new ArrayCollection([$offer]));

        $this->assertEquals(50, $campaign->getFirstShipmentPriceForProduct($product));
    }

    /** @test */
    public function ipercentage_based_discount_for_first_shipment_falls_back_to_all_shipments()
    {
        $campaign = entity(Campaign::class)->make([
            'effects' => [
                [
                    'type' => Campaign::EFFECT_TYPE_PERCENT_DISCOUNT,
                    'value' => 50,
                    'context' => Campaign::EFFECT_CONTEXT_ALL_SHIPMENTS,
                ]
            ]
        ]);
        $product = entity(Product::class)->make([
            'price' => 100
        ]);
        $this->withoutExceptionHandling();
        $offer = entity(Offer::class)->make([
            'product' => $product,
            'firstShipmentPrice' => null,
            'price' => null,
        ]);
        $campaign->setOffers(new ArrayCollection([$offer]));

        $this->assertEquals(50, $campaign->getFirstShipmentPriceForProduct($product));
    }

    /** @test */
    public function it_can_have_a_value_based_discount()
    {
        $campaign = entity(Campaign::class)->make([
            'effects' => [
                [
                    'type' => Campaign::EFFECT_TYPE_VALUE_DISCOUNT,
                    'value' => 20,
                    'context' => Campaign::EFFECT_CONTEXT_ALL_SHIPMENTS,
                ]
            ]
        ]);
        $product = entity(Product::class)->make([
            'price' => 100
        ]);
        $this->withoutExceptionHandling();
        $offer = entity(Offer::class)->make([
            'product' => $product,
            'firstShipmentPrice' => null,
            'price' => null,
        ]);
        $campaign->setOffers(new ArrayCollection([$offer]));

        $this->assertEquals(80, $campaign->getPriceForProduct($product));
    }

    /** @test */
    public function it_can_have_a_value_based_discount_for_first_shipment()
    {
        $campaign = entity(Campaign::class)->make([
            'effects' => [
                [
                    'type' => Campaign::EFFECT_TYPE_VALUE_DISCOUNT,
                    'value' => 20,
                    'context' => Campaign::EFFECT_CONTEXT_FIRST_SHIPMENT,
                ]
            ]
        ]);
        $product = entity(Product::class)->make([
            'price' => 100
        ]);
        $this->withoutExceptionHandling();
        $offer = entity(Offer::class)->make([
            'product' => $product,
            'firstShipmentPrice' => null,
            'price' => null,
        ]);
        $campaign->setOffers(new ArrayCollection([$offer]));

        $this->assertEquals(80, $campaign->getFirstShipmentPriceForProduct($product));
    }

    /** @test */
    public function it_can_set_arbitrary_pricing()
    {
        $campaign = entity(Campaign::class)->make([
            'effects' => [
                [
                    'type' => Campaign::EFFECT_TYPE_ARBITRARY_PRICING,
                    'value' => 70,
                    'context' => Campaign::EFFECT_CONTEXT_ALL_SHIPMENTS,
                ],
                [
                    'type' => Campaign::EFFECT_TYPE_ARBITRARY_PRICING,
                    'value' => 20,
                    'context' => Campaign::EFFECT_CONTEXT_FIRST_SHIPMENT,
                ]
            ]
        ]);
        $product = entity(Product::class)->make([
            'price' => 100
        ]);
        $this->withoutExceptionHandling();
        $offer = entity(Offer::class)->make([
            'product' => $product,
            'firstShipmentPrice' => null,
            'price' => null,
        ]);
        $campaign->setOffers(new ArrayCollection([$offer]));

        $this->assertEquals(70, $campaign->getPriceForProduct($product));
        $this->assertEquals(20, $campaign->getFirstShipmentPriceForProduct($product));
    }

    /** @test */
    public function arbitrary_pricing_can_be_zero()
    {
        $campaign = entity(Campaign::class)->make([
            'effects' => [
                [
                    'type' => Campaign::EFFECT_TYPE_ARBITRARY_PRICING,
                    'value' => 0,
                    'context' => Campaign::EFFECT_CONTEXT_ALL_SHIPMENTS,
                ],
                [
                    'type' => Campaign::EFFECT_TYPE_ARBITRARY_PRICING,
                    'value' => 0,
                    'context' => Campaign::EFFECT_CONTEXT_FIRST_SHIPMENT,
                ]
            ]
        ]);
        $product = entity(Product::class)->make([
            'price' => 100
        ]);
        $this->withoutExceptionHandling();
        $offer = entity(Offer::class)->make([
            'product' => $product,
            'firstShipmentPrice' => null,
            'price' => null,
        ]);
        $campaign->setOffers(new ArrayCollection([$offer]));

        $this->assertEquals(0, $campaign->getPriceForProduct($product));
        $this->assertEquals(0, $campaign->getFirstShipmentPriceForProduct($product));
    }

    /** @test */
    public function it_has_an_is_expired_accessor()
    {
        $campaign = entity(Campaign::class)->make([
            'startDate' => null,
            'endDate' => null,
        ]);
        $this->assertFalse($campaign->isExpired());

        $campaign->setEndDate(new DateTime('tomorrow'));
        $this->assertFalse($campaign->isExpired());

        $campaign->setEndDate(new DateTime());
        $this->assertFalse($campaign->isExpired());

        $campaign->setEndDate(new DateTime('yesterday'));
        $this->assertTrue($campaign->isExpired());
    }
    
}
