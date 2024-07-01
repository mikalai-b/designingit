<?php

namespace App\Console\Commands;

use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Products;
use ProductTypes;

class FillDashboardPeriods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:fill-dashboard-periods';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill product/product type dashboard periods with starting data.';

    protected $productTypes;
    protected $products;
    protected $entityManager;

    /**
     * 
     */
    const DASHBOARD_PERIODS = [
        'products' => [
            // latisse 5ml...
            '00023-3616-05' => [2, 3, 4, 5, 6],
            // latisse 3ml...
            '00023-3616-70' => [1, 2],
        ],
        'productTypes' => [
            'Tretinoin cream' => [2, 3, 4, 5, 6]
        ],
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ProductTypes $productTypes, Products $products, EntityManager $entityManager)
    {
        parent::__construct();
        $this->productTypes = $productTypes;
        $this->products = $products;
        $this->entityManager = $entityManager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (static::DASHBOARD_PERIODS['products'] as $ndc => $periods) {
            
            $product = $this->products->query(function($query) use ($ndc) {
                $query
                    ->where('this.ndcNumber = ?1')
                    ->setParameter(1, $ndc)
                ;
            })->getResult()[0];
            $product->setAvailableDashboardPeriods($this->convertPeriodsToDays($periods));
        }
        foreach (static::DASHBOARD_PERIODS['productTypes'] as $name => $periods) {
            $productType = $this->productTypes->query(function($query) use ($name) {
                $query
                    ->where('this.name = ?1')
                    ->setParameter(1, $name)
                ;
            })->getResult()[0];
            $productType->setAvailableDashboardPeriods($this->convertPeriodsToDays($periods));
        }
        $this->entityManager->flush();
    }

    private function convertPeriodsToDays($periods = [])
    {
        return array_map(function($period) {
            return $period *= 30;
        }, $periods);
    }
}
