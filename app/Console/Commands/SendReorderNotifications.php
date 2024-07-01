<?php

namespace App\Console\Commands;

use App\Notifications\ReorderNotification;
use Doctrine\ORM\EntityManager;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Prescriptions;

class SendReorderNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:send-reorder-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find recently expired prescriptions and send reorder notifications.';

    /**
     * @var \Doctrine\ORM\EntityManager
     */
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
    public function handle(Prescriptions $prescriptionRepository)
    {
        Log::info('Nightly reorder notification batcher: Starting.');
        $prescriptions = $prescriptionRepository->findReadyForReorderNotification();
        foreach ($prescriptions as $prescription) {
            try {
                $prescription->getConsultation()->getOrder()->getPerson()->notify(
                    new ReorderNotification($prescription)
                );
                $prescription->setSentReorderNotification(1);
                $prescriptionRepository->store($prescription, true);
                Log::info(sprintf('Nightly reorder notification batcher: Sending notification for prescription #%s.', $prescription->getId()));
            } catch (Exception $e) {
                Log::info('Nightly reorder notifcation batcher: Hit a snag.');
                Log::info($e->getMessage());
            }
        }
        Log::info(sprintf('Nightly reorder notification batcher: Finishing. Dispatched %s notifications.', count($prescriptions)));
    }
}
