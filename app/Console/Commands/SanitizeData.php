<?php

namespace App\Console\Commands;

use Doctrine\ORM\EntityManager;
use Faker\Generator;
use Illuminate\Console\Command;
use People;

class SanitizeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:sanitize-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sanitize patient data.';

    protected $faker;

    protected $entityManager;

    const CHUNK_SIZE = 20;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Generator $faker, EntityManager $entityManager)
    {
        $this->faker = $faker;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(People $peopleRepository)
    {
        if (app()->environment() === 'production') {
            $this->error('This command may not be run in the production environment.');
            return 0;
        }

        $this->info('Sanitizing person data...');
        $count = 0;
        while(true) {
            $people = $peopleRepository->findBy([], ['id' => 'asc'], static::CHUNK_SIZE, ($count * static::CHUNK_SIZE));
            if (count($people)) {
                foreach ($people as $person) {
                    if (!in_array($person->getEmail(), config('app.sanitization-exceptions', []))) {
                        $person->setFirstName($this->faker->firstName());
                        $person->setLastName($this->faker->lastName());
                        $person->setEmail($this->faker->email());
                        $person->setPhone($this->faker->phoneNumber());
                        $person->setAddressLine1($this->faker->streetAddress());
                        $person->setCity($this->faker->city());
                        $person->setPostalCode($this->faker->postcode());
                        $person->setDateOfBirth($this->faker->dateTimeBetween('-70 years', '-20 years'));
                        $peopleRepository->store($person);
                        print ".";
                    }
                }
                $this->entityManager->flush();
            } else {
                print PHP_EOL;
                break;
            }
            $count++;
        }
        $this->info('Done!');
        return 0;
    }
}
