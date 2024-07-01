<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Rx;
use Prescriptions;
use Doctrine\ORM\EntityManager;
use Prescription;
use SuiteRx;

class Resupply extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:resupply';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resupply all past-due prescriptions and charge associated cards.';

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
    public function handle(Prescriptions $prescriptions, Rx $rx, SuiteRx $suiteRx, EntityManager $em)
    {
        foreach ($prescriptions->findForResupply() as $prescription) {
            $this->info(sprintf('Attempt Re-filling Prescription: %s', $prescription->getId()));


            $consultation = $prescription->getConsultation();
            $order = $prescription->getConsultation()->getOrder();
            $selectedProduct = $order->getProducts()->first();

            if ($selectedProduct->getCategory()->getSlug() === "derma") {
                $suiteRx->createFromConsultation($consultation);
            } else {
                $rx->fill($prescription);
            }
            $em->flush();
        }
    }
}
