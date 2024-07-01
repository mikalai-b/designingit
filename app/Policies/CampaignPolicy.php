<?php

namespace App\Policies;

use Person;
use Campaign;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;

    public function before(Person $person, $ability)
    {
        if ($person->hasRoleByName('Provider')) {
            return true;
        }
    }

    public function manage(Person $person)
    {
    }

}
