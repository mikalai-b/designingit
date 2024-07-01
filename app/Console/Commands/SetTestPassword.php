<?php

namespace App\Console\Commands;

use Accounts;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class SetTestPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crx:set-test-password {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set a test password for a given person ID';

    /**
     * @var Accounts
     */
    protected $accounts;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Accounts $accounts)
    {
        $this->accounts = $accounts;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (App::environment(['prod', 'production'])) {
            $this->error('This should not be run in a production environment.');
            exit();
        }
        $id = $this->argument('id');
        $account = $this->accounts->find($id);
        if (!$account) {
            $this->error('Could not find account.');
            exit();
        }
        $password = Str::random(8);
        $account->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $this->accounts->store($account, true);
        $this->info(sprintf('Password has been set to %s. Email address is %s.', $password, $account->getPerson()->getEmail()));
    }
}
