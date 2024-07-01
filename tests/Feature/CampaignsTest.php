<?php

namespace Tests\Feature;

use Campaign;
use DateTime;
use Offer;
use Product;
use ProductType;
use Tests\TestCase;
use Tests\Traits\RefreshDoctrineDatabase;

class CampaignsTest extends TestCase
{
    use RefreshDoctrineDatabase;

    /** @test */
    public function index_requires_authentication()
    {
        $this->get('/dashboard/campaigns')
            ->assertStatus(302);

        $this->actingAsPersonWithRole('Provider');

        $this->get('/dashboard/campaigns')
            ->assertStatus(200);
    }

    /** @test */
    public function only_providers_may_access_index()
    {
        $this->get('/dashboard/campaigns')
            ->assertStatus(302);

        $this->actingAsPersonWithRole('Foo');

        $this->get('/dashboard/campaigns')
            ->assertStatus(403);

        $this->actingAsPersonWithRole('Provider');

        $this->em->flush();


        $this->get('/dashboard/campaigns')
            ->assertStatus(200);
    }
    
    /** @test */
    public function it_can_get_a_list_of_campaigns()
    {
        $campaign = entity(Campaign::class)->create();
        $this->em->flush();
        $this->actingAsPersonWithRole('Provider');

        $this->get('/dashboard/campaigns')
            ->assertStatus(200)
            ->assertSee($campaign->getTitle());
    }
    
    /** @test */
    public function it_can_create_a_campaign()
    {
        $this->actingAsPersonWithRole('Provider');

        $data = [
            'title' => 'My campaign',
            'start_date' => '2022-01-01',
            'end_date' => '2023-01-01',
        ];

        $this->withoutExceptionHandling();
        $this->post('/dashboard/campaigns', $data);
        $this->em->flush();

        $this->assertDatabaseHas('campaigns', [
            'title' => 'My campaign',
        ]);
    }

    /** @test */
    public function it_can_update_a_campaign()
    {
        $campaign = entity(Campaign::class)->create();
        $this->em->flush();
        $this->actingAsPersonWithRole('Provider');

        $this->post(sprintf('/dashboard/campaigns/%s', $campaign->getId()), [
            'title' => 'My Updated Campaign'
        ]);

        $this->em->flush();
        $this->assertDatabaseHas('campaigns', [
            'title' => 'My Updated Campaign',
        ]);
    }

    /** @test */
    public function it_can_add_offers_to_a_campaign()
    {
        $campaign = entity(Campaign::class)->create();
        $productType = entity(ProductType::class)->create();
        $product = entity(Product::class)->create([
            'productType' => $productType,
            'dateCreated' => new DateTime
        ]);
        $this->actingAsPersonWithRole('Provider');

        $this->post('/api/v1/offers', [
            'campaignId' => $campaign->getId(),
            'productId' => $product->getId(),
        ]);
        $this->em->flush();

        $this->assertDatabaseHas('offers', [
            'campaign_id' => $campaign->getId(),
            'product_id' => $product->getId(),
        ]);
    }

    /** @test */
    public function adding_an_offer_for_a_product_already_associated_will_update_it()
    {
        $campaign = entity(Campaign::class)->create();
        $productType = entity(ProductType::class)->create();
        $product = entity(Product::class)->create([
            'productType' => $productType,
            'dateCreated' => new DateTime
        ]);
        $offer = entity(Offer::class)->create([
            'campaign' => $campaign,
            'product' => $product,
            'price' => 20,
        ]);
        $this->actingAsPersonWithRole('Provider');

        $this->post('/api/v1/offers', [
            'campaignId' => $campaign->getId(),
            'productId' => $product->getId(),
            'price' => 30
        ]);
        $this->em->flush();

        $this->assertDatabaseHas('offers', [
            'campaign_id' => $campaign->getId(),
            'product_id' => $product->getId(),
            'price' => 30,
        ]);

        $this->assertDatabaseMissing('offers', [
            'campaign_id' => $campaign->getId(),
            'product_id' => $product->getId(),
            'price' => 20,
        ]);
    }

    /** @test */
    public function an_offer_can_be_removed()
    {
        $campaign = entity(Campaign::class)->create();
        $productType = entity(ProductType::class)->create();
        $product = entity(Product::class)->create([
            'productType' => $productType,
            'dateCreated' => new DateTime
        ]);
        $offer = entity(Offer::class)->create([
            'campaign' => $campaign,
            'product' => $product,
            'price' => 20,
        ]);
        $this->actingAsPersonWithRole('Provider');

        $this->assertDatabaseHas('offers', [
            'campaign_id' => $campaign->getId(),
            'product_id' => $product->getId(),
        ]);

        $this->delete(sprintf('/api/v1/offers/%s', $offer->getId()));
        $this->em->flush();

        $this->assertDatabaseMissing('offers', [
            'campaign_id' => $campaign->getId(),
            'product_id' => $product->getId(),
        ]);
    }
    
    
    
    
    
}
