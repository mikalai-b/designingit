<?php

namespace Tests\Unit\Entities;

use CouponCode;
use Tests\TestCase;
use Tests\Traits\RefreshDoctrineDatabase;

class CouponCodeTest extends TestCase
{
    use RefreshDoctrineDatabase;

    /** @test */
    public function it_can_be_redeemed()
    {
        $couponCode = entity(CouponCode::class)->make([
            'redeemed' => 0,
            'unlimited' => 0,
        ]);

        $this->assertEquals(0, $couponCode->getRedeemed());
        $this->assertNull($couponCode->getDateRedeemed());
        $couponCode->redeem();

        $this->assertEquals(1, $couponCode->getRedeemed());
        $this->assertNotNull($couponCode->getDateRedeemed());
    }

    /** @test */
    public function unlimited_codes_do_not_get_set_as_redeemed()
    {
        $couponCode = entity(CouponCode::class)->make([
            'redeemed' => 0,
            'unlimited' => 1,
        ]);

        $this->assertEquals(0, $couponCode->getRedeemed());
        $this->assertNull($couponCode->getDateRedeemed());
        $couponCode->redeem();

        $this->assertEquals(0, $couponCode->getRedeemed());
        $this->assertNull($couponCode->getDateRedeemed());
    }

    /** @test */
    public function it_has_a_closed_accessor()
    {
        $couponCode = entity(CouponCode::class)->make([
            'redemptionLimit' => null,
            'redemptionCount' => 1,
        ]);
        $this->assertFalse($couponCode->isClosed());

        $couponCode->setRedemptionLimit(1);
        $this->assertTrue($couponCode->isClosed());
    }
    
    
}
