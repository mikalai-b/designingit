<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Products;

class PopulateGrouponContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:populate-groupon-content';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate the product groupon content column.';

    const CONTENT = [
        'Retin-A' => 'Retin-A (Tretinoin) is designed to last 3 months. Your first order is pre-paid with a Groupon. While future Groupons are not accepted, your future refill price is the lowest of any major US telehealth company - $59 - and free shipping is always included, and there are never any other charges.',
        'Renova' => 'Renova (Tretinoin) is designed to last 3 months. Your first order is pre-paid with a Groupon. While future Groupons are not accepted, your future refill price is the lowest of any major US telehealth company - $59 - and free shipping is always included, and there are never any other charges.',
        'Altreno' => 'Altreno (Tretinoin) is designed to last 3 months. Your first order is pre-paid with a Groupon. While future Groupons are not accepted, your future refill price is the lowest of any major US telehealth company - $59 - and free shipping is always included, and there are never any other charges.',
        'Valacyclovir' => 'Valacyclovir is designed to last 3 months. Your first order is pre-paid with a Groupon.  While future Groupons are not accepted, your future refill price is the lowest of any major US telehealth company, and free shipping is always included, and there are never any other charges.',
        'Finasteride' => 'Finasteride is designed to last 3 months. Your first order is pre-paid with a Groupon.  While future Groupons are not accepted, your future refill price is the lowest of any major US telehealth company, and free shipping is always included, and there are never any other charges.',
    ];

    protected $products;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Products $products)
    {
        $this->products = $products;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (static::CONTENT as $productName => $content) {
            $this->info(sprintf('Working on %s', $productName));
            $products = $this->products->findBy(['name' => $productName]);
            if ($products) {
                foreach ($products as $product) {
                    $product->setGrouponContent($content);
                    $this->products->store($product, true);
                }
            } else {
                $this->error('Not found.');
            }
        }
    }
}
