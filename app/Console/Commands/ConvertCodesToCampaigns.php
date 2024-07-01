<?php

namespace App\Console\Commands;

use Campaign;
use Campaigns;
use CouponCodes;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Offer;
use Offers;
use Product;

class ConvertCodesToCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:convert-codes-to-campaigns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert all coupon codes to new campaigns';

    protected $entityManager;
    protected $couponCodeRepository;
    protected $campaignRepository;
    protected $offerRepository;
    protected $map = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EntityManager $entityManager, CouponCodes $couponCodeRepository, Campaigns $campaignRepository, Offers $offerRepository)
    {
        $this->entityManager = $entityManager;
        $this->couponCodeRepository = $couponCodeRepository;
        $this->campaignRepository = $campaignRepository;
        $this->offerRepository = $offerRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->deleteLegacy();
        $conn = $this->entityManager->getConnection();
        $sql = 'SELECT DISTINCT COUNT(*) AS count, campaign_description FROM coupon_codes WHERE campaign_description != "Adwords Campaign" GROUP BY campaign_description';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $campaigns = collect($results)
            ->filter(function($result) {
                return $result['campaign_description'] !== null;
            })
            ->where('count', '>', 1)
            ->values();

        $singles = collect($results)
            ->filter(function($result) {
                return $result['campaign_description'] !== null;
            })
            ->where('count', '1')
            ->values();

        $this->processCampaigns($campaigns);
        $this->processSingles($singles);
        $this->processNulls();
        $this->processAdwords();
    }

    private function deleteLegacy()
    {
        $this->info('Deleting legacy campaigns.');
        $conn = $this->entityManager->getConnection();
        $sql = 'DELETE FROM offers';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sql = 'DELETE FROM campaigns';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    private function processCampaigns($campaignsFromCodes)
    {
        $this->info('Converting real campaigns');
        foreach ($campaignsFromCodes as $campaignFromCode) {
            $description = $campaignFromCode['campaign_description'];
            $campaign = $this->campaignRepository->findOneBy(['title'=>$description]);
            if (!$campaign) {
                $campaign = $this->createCampaign($description ?: 'Untitled Campaign');
            }

            $code = $this->couponCodeRepository->findOneBy(['campaignDescription'=>$description]);

            if (count($code->getProducts())) {
                foreach ($code->getProducts() as $product) {
                    $this->createOffer($product, $campaign, $product->getGrouponContent());
                }
            }

            $sql = 'UPDATE coupon_codes SET campaign_id = :campaignId WHERE campaign_description = :description';
            $conn = $this->entityManager->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute(['campaignId' => $campaign->getId(), 'description' => $description]);
        }
    }

    private function processSingles($singles)
    {
        $this->info('Converting one-off codes.');
        foreach ($singles as $single) {
            $code = $this->couponCodeRepository->findOneBy(['campaignDescription'=>$single['campaign_description']]);
            if (count($code->getProducts())) {
                $joinedProducts = $this->joinProductsForMap($code->getProducts());
                if ($this->map[$joinedProducts] ?? false) {
                    $campaign = $this->map[$joinedProducts];
                } else {
                    $campaign = $this->createCampaign(sprintf('Generic: %s', $joinedProducts));
                    $this->map[$joinedProducts] = $campaign;
                    foreach ($code->getProducts() as $product) {
                        $this->createOffer($product, $campaign, $product->getGrouponContent());
                    }
                }
                $code->setCampaign($campaign);
                $this->couponCodeRepository->store($code, true);
            } else {
                $this->error(sprintf('Code %s does not have any products.', $code->getCode()));
            }
        }
    }

    private function processNulls()
    {
        $this->info('Converting null campaigns.');
        $codes = $this->couponCodeRepository->findBy(['campaignDescription'=>null]);
        foreach ($codes as $code) {
            if (count($code->getProducts())) {
                $joinedProducts = $this->joinProductsForMap($code->getProducts());
                if ($this->map[$joinedProducts] ?? false) {
                    $campaign = $this->map[$joinedProducts];
                } else {
                    $campaign = $this->createCampaign(sprintf('Singles: %s', $joinedProducts));
                    $this->map[$joinedProducts] = $campaign;
                    foreach ($code->getProducts() as $product) {
                        $this->createOffer($product, $campaign, $product->getGrouponContent());
                    }
                }
                $code->setCampaign($campaign);
                $this->couponCodeRepository->store($code, true);
            } else {
                $this->error(sprintf('Code %s does not have any products.', $code->getCode()));
            }
        }
    }

    private function joinProductsForMap($products)
    {
        return collect($products)
            ->sortBy(function($product) {
                return $product->__toString();
            })
            ->map(function($product) {
                return $this->getStringForProduct($product);
            })
            ->implode(', ');
    }

    private function getStringForProduct(Product $product)
    {
        return sprintf('%s (%s/%s)', $product->getName(), $product->getStrength(), $product->getQuantity());
    }

    private function createCampaign($title, $successMessage = null)
    {
        $campaign = new Campaign();
        $campaign->setTitle($title ?: 'Untitled Campaign');
        $campaign->setSuccessMessage($successMessage);
        $campaign->setEffects([
            [
                'type' => Campaign::EFFECT_TYPE_ARBITRARY_PRICING,
                'context' => Campaign::EFFECT_CONTEXT_FIRST_SHIPMENT,
                'value' => 0
            ]
        ]);
        $this->campaignRepository->store($campaign, true);
        return $campaign;
    }

    private function createOffer(Product $product, Campaign $campaign, $successMessage = null)
    {
        $offer = new Offer();
        $offer->setProduct($product);
        $offer->setPrice($product->getGrouponPrice());
        $offer->setCampaign($campaign);
        $offer->setSuccessMessage($successMessage);
        $this->offerRepository->store($offer, true);
    }

    private function processAdwords()
    {
        $this->info('Converting adwords campaign.');
        $message = 'Your promo code {{coupon_code.code}} has been applied, and your first order ships for free. A credit/debit card is required on file - and your future refills (which can be adjusted once the order is placed among every 1, 2, 3, 4, 5 or 6 months, or paused or cancelled at any time) is lowered to the lowest price of any major US telehealth company.';
        $campaign = $this->createCampaign('Adwords 2022', $message);
        $code = $this->couponCodeRepository->find('FREE');
        if ($code) {
            foreach ($code->getProducts() as $product) {
                $this->createOffer($product, $campaign);
            }
            $code->setCampaign($campaign);
            $this->couponCodeRepository->store($code, true);
        }
    }
}
