<?php

namespace App\Http\Actions\Patients;

use Person;
use Account;

/**
 *
 */
trait Populate
{
    /**
     *
     */
    public function populate(Person $person, Account $account)
    {
        $person->setFirstName($this->request->input('firstName', NULL));
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

        $account->setPerson($person);
    }
}
