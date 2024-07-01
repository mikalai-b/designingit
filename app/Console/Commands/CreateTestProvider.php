<?php

namespace App\Console\Commands;

use Accounts;
use Illuminate\Console\Command;
use People;
use Providers;
use Roles;
use States;

class CreateTestProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:create-test-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $people;
    protected $accounts;
    protected $roles;
    protected $providers;
    protected $states;

    const EMAIL = 'provider@imarc.com';
    const FIRST_NAME = 'Imarc';
    const LAST_NAME = 'Provider';
    const ADDRESS_1 = '21 Water Street';
    const CITY = 'Amesbury';
    const STATES = ['MA'];
    const ZIP = '01913';
    const PASSWORD = '...';
    const NPI = '1234567890';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(People $people, Accounts $accounts, Roles $roles, Providers $providers, States $states)
    {
        $this->people = $people;
        $this->accounts = $accounts;
        $this->roles = $roles;
        $this->providers = $providers;
        $this->states = $states;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $person = $this->people->findOneByEmail(static::EMAIL);
        if ($person) {
            $this->error('Person already exists.');
            return;
        }

        $person  = $this->people->create();
        $account = $this->accounts->create();

        $person->setEmail(static::EMAIL);
        $person->setFirstName(static::FIRST_NAME);
        $person->setLastName(static::LAST_NAME);
        $person->setState($this->states->find(static::STATES[0]));
        $person->setAddressLine1(static::ADDRESS_1);
        $person->setCity(static::CITY);
        $person->setPostalCode(static::ZIP);
        $this->people->store($person, true);

        $account->setPerson($person);
        $account->setPassword(password_hash(static::PASSWORD, PASSWORD_BCRYPT));

        if ($role = $this->roles->findOneByName('Provider')) {
            $account->getRoles()->add($role);

            $provider = $this->providers->create();
            $provider->setPerson($person);
            $provider->setNpiNumber(static::NPI);

            foreach (static::STATES as $state_id) {
                if (!$state = $this->providers->getStates()->find($state_id)) {
                    throw new \Exception('Bad state specified');
                }
                if (!$provider->getStates()->contains($state)) {
                    $provider->getStates()->add($state);
                }
            }
            $this->providers->store($provider, true);
        }

        if ($role = $this->roles->findOneByName('Admin')) {
            $account->getRoles()->add($role);
        }
        $this->accounts->store($account, true);
    }
}
