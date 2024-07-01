<?php

namespace App\Http\Actions\Users;

use Person;
use Provider;
use Account;

/**
 *
 */
trait Populate
{
    /**
     *
     */
    public function populate(Person $person, Account $account, Provider $provider)
    {
        $professionId = $this->request->input('professionId', null);
        $person->setProfessionId($professionId);

        $person->setFirstName($this->request->input('firstName', NULL));
        $person->setMiddleName($this->request->input('middleName', NULL));
        $person->setLastName($this->request->input('lastName', NULL));
        $person->setEmail($this->request->input('email', NULL));
        $person->setPhone($this->request->input('phone', NULL));
        $person->setAddressLine1($this->request->input('addressLine1', NULL));
        $person->setAddressLine2($this->request->input('addressLine2', NULL));
        $person->setCity($this->request->input('city', NULL));
        $person->setPostalCode($this->request->input('postalCode', NULL));
        $person->setCredentials($this->request->input('credentials', NULL));

        $person->setState($this->people->getStates()->findOneById($this->request->input('state', NULL)));

        if ($this->request->input('dateOfBirth', NULL)) {
            $person->setDateOfBirth(new \DateTime($this->request->input('dateOfBirth')));
        }

        $account->setRoles($this->people->getRoles()->findByName(array_keys($this->request->input('roles', array()))));
        $account->setPerson($person);

        if ($person->hasRoleByName('Provider')) {
            $provider->setPerson($person);
            $provider->setNpiNumber($this->request->input('npiNumber', NULL));
            $provider->setStates($this->people->getStates()->findById($this->request->input('providerStates', array())));

            $this->people->getProviders()->store($provider);

        } else {
            $this->people->getProviders()->remove($provider);
        }
    }
}
