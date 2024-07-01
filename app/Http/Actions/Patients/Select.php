<?php

namespace App\Http\Actions\Patients;

use People;
use Providers;
use Prescriptions;
use Messages;
use App\Http\Actions\AbstractAction;
use Symfony\Component\HttpFoundation\Response;


/**
 *
 */
class Select extends AbstractAction
{
    /**
     * @param People $people The people repository
     * @param string $id The id of the person to select
     * @return Response The response from the action
     */
    public function __invoke(People $people, Providers $providers, Prescriptions $prescriptions, Messages $messages, $id)
    {
        $action   = $this->request->query('action', 'select');
        $template = sprintf('pages.patients.%s', $action);
        $person   = $people->find($id);
        $user     = $this->auth->user();

        if (!$person) {
            return $this->respond(NULL, 404);
        }

        $this->authorize($action, $person);

        if ($action == 'select') {
            $open_prescriptions = $prescriptions->findOpenForPerson($person);
        }

        return $this->render($template, 200, get_defined_vars());
    }
}
