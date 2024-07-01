<?php

namespace App\Console\Commands;

use App\Notifications\ReorderNotification;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Prescriptions;

class BatchReorderNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:batch-reorder-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reorder notifications for all expired prescriptions.';

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
            try {
                $prescription->getConsultation()->getOrder()->getPerson()->notify(
                    new ReorderNotification()
                );
                $prescription->setSentReorderNotification(1);
                $prescriptionRepository->store($prescription, true);
            } catch (Exception $e) {
                Log::info($e->getMessage());
            }
        }
    }
}
