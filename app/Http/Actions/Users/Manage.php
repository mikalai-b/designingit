<?php

namespace App\Http\Actions\Users;

use People;
use Providers;
use PersonCriteria;
use App\Http\Actions\AbstractAction;
use Symfony\Component\HttpFoundation\Response;
use Professions;

/**
 *
 */
class Manage extends AbstractAction
{
    /**
     * Manage Users
     *
     * @param People $people The people repository
     * @param PersonCriteria $criteria The criteria for a person search
     * @return Response The response from the action
     */
    public function __invoke(People $people, Professions $professions, Providers $providers, PersonCriteria $criteria)
    {
        $action   = $this->request->query('action', 'manage');
        $template = sprintf('pages.users.%s', $action);
        $provider = $providers->create();
        $filter   = $this->request->input('filter', NULL);
        $person   = $people->create();
        $professionsList = $professions->findAll();

        $this->authorize($action, $person);

        if ($action == 'manage') {
            if ($filter) {
                $criteria->{'set' . ucwords($filter)}($this->request->input('filterValue', NULL));
            }

            $criteria->setHasRoles(TRUE);
            $criteria->setLimit($this->request->input('limit', 50));
            $criteria->setPage($this->request->input('page', 1));

            $results = $people->search($criteria);
        }

        return $this->render($template, 200, get_defined_vars());
    }
}
