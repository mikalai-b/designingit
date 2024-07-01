<?php

use Illuminate\Database\Seeder;

/**
 *
 */
class AccountsSeeder extends Seeder
{
    /**
     * @var array
     */
    static protected $acls = [
        'Admin' => [
            'account' => ['create']
        ],
        'Provider' => [

        ]
    ];


    /**
     * @var array
     */
    static protected $accounts = [
        [
            'email' => 'info@imarc.com',
            'roles' => ['Admin']
        ],
        [
            'email' => 'johnbfournier@gmail.com',
            'roles' => ['Admin', 'Provider'],
            'npiNumber' => '1629397120',
            'states' => [
                'CA',
                'GA',
                'MO',
                'NY',
                'PA',
                'SC',
                'VA',
                'WA',
                'WI',
            ]
        ],
        [
            'email' => 'dougheiner@gmail.com',
            'roles' => ['Admin', 'Provider'],
            'npiNumber' => '1366753865',
            'states' => [
                'CO',
                'FL',
                'MS',
                'MT',
                'NV',
                'NM',
                'OH',
                'TX',
            ]
        ],
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(People $people, Roles $roles, Providers $providers, Permissions $permissions, Accounts $accounts)
    {
        foreach (static::$acls as $role_name => $acls) {
            $role = $roles->findOneByName($role_name);

            if (!$role) {
                $role = $roles->create();

                $role->setName($role_name);
            }

            foreach ($acls as $entity => $acl) {
                foreach ($acl as $permission_name) {
                    if ($permissions->findOneByName($permission_name . '.' . $entity)) {
                        continue;
                    }

                    $permission = $permissions->create();

                    $permission->setName($permission_name . '.' . $entity);
                    $permissions->store($permission, TRUE);

                    if (!$role->getPermissions()->contains($permission)) {
                        $role->getPermissions()->add($permission);
                    }
                }
            }

            $roles->store($role, TRUE);
        }

        foreach (static::$accounts as $data) {
            if ($people->findOneByEmail($data['email'])) {
                $person  = $people->findOneByEmail($data['email']);
                $account = $person->getAccount();

            } else {
                $person  = $people->create();
                $account = $accounts->create();

                $person->setEmail($data['email']);
                $people->store($person, TRUE);

                $account->setPerson($person);
                $account->setPassword(password_hash('...', PASSWORD_BCRYPT));
            }

            foreach ($data['roles'] as $role_name) {
                if (!$role = $roles->findOneByName($role_name)) {
                    throw new \Exception('Bad role specified.');
                }

                if (!$account->getRoles()->contains($role)) {
                    $account->getRoles()->add($role);
                }

                foreach ($account->getRoles() as $role) {
                    if (!in_array($role->getName(), $data['roles'])) {
                        $account->getRoles()->removeElement($role);
                    }
                }

                if ($role_name == 'Provider') {
                    if ($providers->find($person)) {
                        $provider = $providers->find($person);

                    } else {
                        $provider = $providers->create();

                        $provider->setPerson($person);
                    }

                    $provider->setNpiNumber($data['npiNumber'] ?? NULL);

                    if (isset($data['states'])) {
                        foreach ($data['states'] as $state_id) {
                            if (!$state = $providers->getStates()->find($state_id)) {
                                throw new \Exception('Bad state specified');
                            }

                            if (!$provider->getStates()->contains($state)) {
                                $provider->getStates()->add($state);
                            }
                        }

                        foreach ($provider->getStates() as $state) {
                            if (!in_array($state->getId(), $data['states'])) {
                                $provider->getStates()->removeElement($state);
                            }
                        }
                    }

                    $providers->store($provider, TRUE);
                }
            }

            $accounts->store($account, TRUE);
        }
    }
}
