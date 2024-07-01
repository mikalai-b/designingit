<?php

namespace App\Http\Actions\Patients;

use People;
use Providers;
use PersonCriteria;
use App\Http\Actions\AbstractAction;
use Symfony\Component\HttpFoundation\Response;


/**
 *
 */
class Manage extends AbstractAction
{
    /**
     * Manage Patients
     *
     * @param People $people The people repository
     * @param PersonCriteria $criteria The criteria for a person search
     * @return Response The response from the action
     */
    public function __invoke(People $people, Providers $providers, PersonCriteria $criteria)
    {
        $action   = $this->request->query('action', 'manage');
        $template = sprintf('pages.patients.%s', $action);
        $filter   = $this->request->input('filter', NULL);
        $sortKey  = $this->request->input('sortField', NULL);
        $sortDir  = $this->request->input('sortDirection', NULL);
        $person   = $people->create();
        $user     = $this->auth->user();

        $this->authorize($action, $person);

        if ($action == 'manage') {
            if ($filter) {
                $criteria->{'set' . ucwords($filter)}($this->request->input('filterValue', NULL));
            }

            if ($sortKey) {
                $criteria->orderBy($sortKey, $sortDir);
            }

            $criteria->setProvider($user);
            $criteria->setLimit($this->request->input('limit', 50));
            $criteria->setPage($this->request->input('page', 1));

            $results = $people->search($criteria);
        }

        return $this->render($template, 200, get_defined_vars());
    }
}
