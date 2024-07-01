<?php

namespace App\Console\Commands;

use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class PopulateSecondShipmentPriceColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brx:populate-second-shipment-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate second shipment price. Intented to be run only once, after campaign management system is deployed.';

    protected $entityManager;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EntityManager $entityManager)
    {
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
        $conn = $this->entityManager->getConnection();
        $sql = 'UPDATE line_items LEFT JOIN orders ON line_items.order_id = orders.id SET second_shipment_price = price WHERE orders.coupon_code IS NULL';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
}
