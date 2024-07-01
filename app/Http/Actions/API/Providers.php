<?php

namespace App\Http\Actions\API;

use Providers as Repository;
use PersonResource;
use PersonCollection;
use App\Http\Actions\AbstractAction;
use App\Exceptions\ValidationException;

/**
 *
 */
class Providers extends AbstractAction
{
    /**
     *
     */
    public function get(Repository $repository)
    {
        $results = new \Collection([]);
        $state   = $repository->getStates()->find($this->request->input('state', 'XX'));

        if ($state) {
            $results = $repository->findForState($state)->map(function($provider) {
                return $provider->getPerson();
            });
        }

        return new PersonCollection($results);
    }
}
