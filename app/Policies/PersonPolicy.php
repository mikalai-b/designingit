<?php

namespace App\Policies;

use Person;

class PersonPolicy extends AbstractPolicy
{
    /**
     *
     */
    public function create(Person $user, Person $person)
    {
        return $user->hasRoleByName('Admin');
    }


    /**
     *
     */
    public function remove(Person $user, Person $person)
    {
        return $user->hasRoleByname('Admin');
    }


    /**
    *
    */
    public function update(Person $user, Person $person)
    {
        return $user->getId() == $person->getId() || $user->hasRoleByName('Admin');
    }


    /**
     *
     */
    public function select(Person $user, Person $person)
    {
        return $user->getId() == $person->getId() || $user->hasRoleByName('Provider') || $user->hasRoleByName('Admin');
    }


    /**
     *
     */
    public function manage(Person $user, Person $person)
    {
        return $user->hasRoleByName('Admin');
    }
}
