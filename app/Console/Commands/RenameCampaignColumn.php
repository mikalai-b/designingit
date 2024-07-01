<?php

namespace App\Console\Commands;

use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class RenameCampaignColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:rename-campaign-column';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rename campaign column to campaign_description.';

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
        $sql = 'ALTER TABLE coupon_codes CHANGE campaign campaign_description VARCHAR(255) DEFAULT NULL';
        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
}
