<?php

namespace App\Console\Commands;

use Craft\DateTime;
use Illuminate\Console\Command;
use People;

class CleanPeople extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clean:people';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean table people from dead persons';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(People $people)
    {
        $this->people = $people;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = new DateTime();
        $date->modify('-2 hour');

        $people = $this->people->query(function($query) use ($date) {
            $query
                ->where('this.dateModified < ?1')
                ->andWhere('this.email = NULL')
                ->setParameter(1, $date)
            ;
        })->getResult();

        foreach ($people as $person) {
            $this->people->remove($person);
        }
    }
}
