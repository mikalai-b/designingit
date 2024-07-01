<?php

namespace App\Http\Actions\Consultations;

use Orders;
use Providers;
use OrderCriteria;
use App\Http\Actions\AbstractAction;
use Symfony\Component\HttpFoundation\Response;


/**
 *
 */
class Manage extends AbstractAction
{
    /**
     * Manage Consultations
     */
    public function __invoke(Orders $orders, Providers $providers, OrderCriteria $criteria)
    {
        $action   = $this->request->query('action', 'manage');
        $filter   = $this->request->input('filter', NULL);
        $sortKey  = $this->request->input('sortField', NULL);
        $sortDir  = $this->request->input('sortDirection', NULL);

        $user     = $this->auth->user();
        $order    = $orders->create();
        $template = sprintf('pages.consultations.%s', $action);

        $this->authorize($action, $order);

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
            $criteria->setStates($this->request->input('consultationStatus', array()));

            $results = $orders->search($criteria);
        }

        return $this->render($template, 200, get_defined_vars());
    }
}
