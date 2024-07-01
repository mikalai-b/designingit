<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Prescriptions;

class UpForReorder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:up-for-reorder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays a list of prescriptions that are due for reorder notification.';

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
    public function handle(Prescriptions $prescriptionRepository)
    {
        $prescriptions = $prescriptionRepository->findAllExpiredForReorderNotification();
        foreach ($prescriptions as $prescription) {
            print <<<CODE
Prescription: {$prescription->getId()}
Order:        {$prescription->getOrder()->getId()}
Product:      {$prescription->getLineItem()->getProduct()->getName()}
Person:       {$prescription->getOrder()->getPerson()->getId()} ({$prescription->getOrder()->getPerson()->getEmail()})


CODE;
        }
    }
}
