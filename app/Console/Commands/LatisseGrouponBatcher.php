<?php

namespace App\Console\Commands;

use CouponCodes;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Products;
use ProductTypes;

class LatisseGrouponBatcher extends Command
{
    const LATISSE_3ML_PRICE = 155;
    const LATISSE_3ML_GROUPON_PRICE = 109;
    const LATISSE_5ML_PRICE = 199;
    const LATISSE_5ML_GROUPON_PRICE = 144;
    const LATISSE_5ML_NDC = '00023-3616-05';
    const LATISSE_5ML_ID = '6f57b2fa-9251-4096-ba46-81dee92d5b18';
    const LATISSE_5ML_THUMBNAIL = '/images/thumbnails/latisse-005-square.jpg';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:latisse-groupon-batcher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batch process for setting up Latisse 5ml and groupon updates';

    /**
     * 
     */
    protected $productTypes;

    /**
     * 
     */
    protected $products;

    /**
     * 
     */
    protected $couponCodes;

    /**
     * 
     */
    protected $entityManager;

    /**
     * 
     */
    protected $latisse5mlType;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ProductTypes $productTypes, Products $products, CouponCodes $couponCodes, EntityManager $entityManager)
    {
        $this->productTypes = $productTypes;
        $this->products = $products;
        $this->couponCodes = $couponCodes;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->addNewProduct();
        $this->updateCouponCodes();
        $this->setDefaultProductExpirations();
    }

    /**
     *
     */
    private function addNewProduct()
    {
        $latisse3mlType = $this->productTypes->query(function($query) {
            $query
                ->where('this.name = ?1')
                ->andWhere('this.defaultPeriod = ?2')
                ->setParameter(1, 'Bimatoprost Ophthalmic Solution')
                ->setParameter(2, 30)
            ;
        })->getResult()[0];

        $latisse5ml = $this->products->query(function($query) {
            $query
                ->where('this.name = ?1')
                ->andWhere('this.quantity = ?2')
                ->setParameter(1, 'Latisse')
                ->setParameter(2, '5ml')
            ;
        })->getResult();
        
        if ($latisse5ml) {
            $this->error('Latisse 5ml already exists.');
            return;
        }

        $latisse3ml = $this->products->query(function($query) use ($latisse3mlType) {
            $query
                ->where('this.type = ?1')
                ->setParameter(1, $latisse3mlType->getId())
            ;
        })->getResult()[0];
        $latisse3ml->setPrice(static::LATISSE_3ML_PRICE);
        $latisse3ml->setGrouponPrice(static::LATISSE_3ML_GROUPON_PRICE);
        $this->products->store($latisse3ml, true);

        $latisse5ml = $this->products->create();
        $latisse5ml->setType($latisse3mlType);
        $latisse5ml->setPrescriptionOnly($latisse3ml->getPrescriptionOnly());
        $latisse5ml->setName($latisse3ml->getName());
        $latisse5ml->setStrength($latisse3ml->getStrength());
        $latisse5ml->setQuantity('5ml');
        $latisse5ml->setInfo($latisse3ml->getInfo());
        $latisse5ml->setNdcNumber(static::LATISSE_5ML_NDC);
        $latisse5ml->setThumbnail(static::LATISSE_5ML_THUMBNAIL);
        $latisse5ml->setPrice(static::LATISSE_5ML_PRICE);
        $latisse5ml->setGrouponPrice(static::LATISSE_5ML_GROUPON_PRICE);
        $latisse5ml->setDefaultRefills(3);
        $latisse5ml->setDefaultExpiration(12);
        $latisse5ml->setDefaultPeriod(90);
        $latisse5ml->setAvailablePeriods([90]);
        $latisse5ml->setDefaultAutoRenewal(0);
        $latisse5ml->setRequireAutoRenewal(1);
        $this->products->store($latisse5ml, true);
        $latisse5ml->setId(static::LATISSE_5ML_ID);
        $this->entityManager->flush();
        $latisse5ml = $this->products->find(static::LATISSE_5ML_ID);
        $latisse5ml->setQuestions($latisse3ml->getQuestions());
        $latisse5ml->setPhotoTypes($latisse3ml->getPhotoTypes());
        $this->entityManager->flush();

        $tretinoinType = $this->productTypes->query(function($query) {
            $query
                ->where('this.name = ?1')
                ->setParameter(1, 'Tretinoin cream')
            ;
        })->getResult()[0];
        $this->productTypes->store($tretinoinType, true);
    }

    /**
     *
     */
    private function updateCouponCodes()
    {
        $tretType = $this->productTypes->query(function($query) {
            $query
                ->where('this.name = ?1')
                ->setParameter(1, 'Tretinoin cream')
            ;
        })->getResult()[0];
        $tretProducts = $tretType->getProducts();
        $couponCodes = $this->couponCodes->findAll();
        foreach ($couponCodes as $couponCode) {
            $couponCode->setProducts($tretProducts);
        }
        $this->entityManager->flush();
    }

    /**
     * 
     */
    private function setDefaultProductExpirations()
    {
        $conn = $this->entityManager->getConnection();
        $sql = 'UPDATE product_types SET default_expiration = :expiration';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['expiration'=>12]);
    }
}
