<?php

namespace Tests\Feature;

use App\Http\Actions\RedeemCouponCodeAction;
use CouponCode;
use Tests\TestCase;
use Tests\Traits\RefreshDoctrineDatabase;

class RedeemCouponCodeTest extends TestCase
{
    use RefreshDoctrineDatabase;
    
    /** @test */
    public function coupon_code_can_be_redeemed()
    {
        $couponCode = entity(CouponCode::class)->create([
            'redeemed' => 0,
        ]);

        $this->get(sprintf('/coupon/%s', $couponCode->getCode()))
            ->assertCookie('couponCode')
            ->assertRedirect('/cart');

        $this->followingRedirects()
            ->get(sprintf('/coupon/%s', $couponCode->getCode()))
            ->assertSee(RedeemCouponCodeAction::SUCCESS_MESSAGE);
    }
    
    /** @test */
    public function invalid_coupon_code_cannot_be_redeemed()
    {
        $this->followingRedirects()
            ->get('/coupon/FOO')
            ->assertSee(RedeemCouponCodeAction::INVALID_MESSAGE);
    }
    
    /** @test */
    public function redeemed_coupon_code_cannot_be_redeemed()
    {
        $couponCode = entity(CouponCode::class)->create([
            'redeemed' => true,
        ]);

        $this->followingRedirects()
        ->get(sprintf('/coupon/%s', $couponCode->getCode()))
            ->assertSee(RedeemCouponCodeAction::ALREADY_REDEEMED_MESSAGE);
    }

    /** @test */
    public function coupon_redemption_can_have_custom_redirect()
    {
        $couponCode = entity(CouponCode::class)->create([
            'redeemed' => 0,
        ]);

        $this->get(sprintf('/coupon/%s?redirect=/free', $couponCode->getCode()))
            ->assertCookie('couponCode')
            ->assertRedirect('/free');
    }
    
    
}
