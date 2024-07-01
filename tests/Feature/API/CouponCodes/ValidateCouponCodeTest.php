<?php

namespace Tests\Feature\API\CouponCodes;

use Campaign;
use CouponCode;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Tests\TestCase;
use Offer;
use Product;
use ProductType;
use State;
use Tests\Traits\RefreshDoctrineDatabase;

class ValidateCouponCodeTest extends TestCase
{
    use RefreshDoctrineDatabase;

    protected $campaign = null;
    protected $couponCode = null;
    protected $product = null;
    protected $provider = null;

    /** @test */
    public function coupon_code_can_be_validated()
    {
        $this->createCampaignWithProductAndCode();
        $this->actingAsEndUser();
        $this->createCartForProduct($this->product);

        $this->em->flush();

        $this->json('post', '/api/v1/coupon-code/validate', [
                'code' => $this->couponCode->getCode(),
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'valid' => true
            ]);
    }

    /** @test */
    public function redeemed_coupon_codes_fail_validation()
    {
        $this->createCampaignWithProductAndCode([], ['redeemed' => 1]);
        $this->actingAsEndUser();
        $this->createCartForProduct($this->product);

        $this->em->flush();

        $this->json('post', '/api/v1/coupon-code/validate', [
                'code' => $this->couponCode->getCode(),
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('code');
    }

    /** @test */
    public function coupon_codes_for_expired_campaigns_fail_validation()
    {
        $this->createCampaignWithProductAndCode();
        $this->actingAsEndUser();
        $this->createCartForProduct($this->product);

        $this->em->flush();

        $this->campaign->setStartDate(null);
        $this->campaign->setEndDate(new DateTime('yesterday'));

        $this->em->flush();

        $this->json('post', '/api/v1/coupon-code/validate', [
                'code' => $this->couponCode->getCode(),
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('code');
    }

    /** @test */
    public function coupon_codes_for_future_campaigns_fail_validation()
    {
        $this->createCampaignWithProductAndCode();
        $this->actingAsEndUser();
        $this->createCartForProduct($this->product);

        $this->em->flush();

        $this->campaign->setStartDate(new DateTime('tomorrow'));
        $this->campaign->setEndDate(null);

        $this->em->flush();

        $this->json('post', '/api/v1/coupon-code/validate', [
                'code' => $this->couponCode->getCode(),
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('code');
    }

    private function createCartForProduct(Product $product)
    {
        $this->get(sprintf('/cart/products/%s', (string) $product->getId()));
        $this->post('/cart/order', [
            'state' => 'CA',
            'provider' => $this->provider->getPerson()->getId(),
            'periods' => [
                (string) $this->product->getId() => 30
            ],
            'consent' => [
                (string) $product->getId() => 1
            ]
        ])
        ->assertRedirect();
    }

    private function createCampaignWithProductAndCode(
        $productAttributes = [],
        $codeAttributes = ['redeemed' => 0],
        $offerAttributes = []
    )
    {
        $productType = entity(ProductType::class)->create();

        $product = entity(Product::class)->create([
            'dateCreated' => new DateTime(),
            'availablePeriods' => [30, 60],
            'defaultPeriod' => 30,
        ] + $productAttributes);
        $product->setType($productType);

        $provider = $this->createProvider();
        $state = entity(State::class)->create([
            'id' => 'CA',
            'name' => 'California'
        ]);

        $provider->setStates(new ArrayCollection([$state]));

        $campaign = entity(Campaign::class)->create();
        $couponCode = entity(CouponCode::class)->create([
            'campaign' => $campaign,
        ] + $codeAttributes);

        $offer = entity(Offer::class)->create([
            'product' => $product,
            'campaign' => $campaign,
        ] + $offerAttributes);
        $campaign->setOffers(new ArrayCollection([$offer]));

        $this->campaign = $campaign;
        $this->couponCode = $couponCode;
        $this->product = $product;
        $this->provider = $provider;
    }
    
}
