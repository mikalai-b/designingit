<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Orders;
use Doctrine\ORM\EntityManager;

class SetupCustomerIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:setup-customer-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Orders $ordersRepository, EntityManager $em)
    {
        $orders = $ordersRepository->query(function($query) {
            $query->where('this.creditCard IS NOT NULL');
        })->getResult();
        foreach ($orders as $order) {
            if (!$order->getPerson()->getAccount()->getCustomer()) {
                if (preg_match('/^\d+$/', $order->getCreditCard()->getCustomer())) {
                    $order->getPerson()->getAccount()->setCustomer($order->getCreditCard()->getCustomer());
                }
            }
        }
        $em->flush();
    }
}
