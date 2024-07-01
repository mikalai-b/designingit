<?php

namespace App\Console\Commands;

use CouponCodes;
use Illuminate\Console\Command;

class ImportCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:import-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import coupon codes into the coupon_codes table.';

    protected $couponCodes;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CouponCodes $couponCodes)
    {
        parent::__construct();
        $this->couponCodes = $couponCodes;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = $this->ask('Please enter a file containing the codes');
        if (!file_exists($file)) {
            $this->error('File does not exist.');
            return;
        } 
        $campaign = $this->ask('Please enter a campaign');
        $this->info($campaign);
        $lines = file($file);
        foreach ($lines as $line) {
            $couponCode = $this->couponCodes->create();
            $couponCode->setCode(trim($line));
            $couponCode->setCampaign($campaign);
            $this->couponCodes->store($couponCode, TRUE);
        }
    }
}
