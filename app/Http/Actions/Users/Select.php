<?php

namespace App\Http\Actions\Users;

use People;
use Providers;
use Prescriptions;
use Messages;
use App\Http\Actions\AbstractAction;
use Symfony\Component\HttpFoundation\Response;
use Professions;

/**
 *
 */
class Select extends AbstractAction
{
    /**
     * @param People $people The people repository
     * @param Professions $professions The professions repository
     * @param string $id The id of the person to select
     * @return Response The response from the action
     */
    public function __invoke(People $people, Professions $professions, Providers $providers, Prescriptions $prescriptions, Messages $messages, $id)
    {
        $action   = $this->request->query('action', 'select');
        $template = sprintf('pages.users.%s', $action);
        $provider = $providers->findOneByPerson($id);
        $person   = $people->find($id);
        $user     = $this->auth->user();

        if (!$person) {
            return $this->respond(NULL, 404);
        }

        if (!$provider) {
            $provider = $providers->create();
        }

        $this->authorize($action, $person);

        if ($action == 'select') {
            $open_prescriptions = $prescriptions->findOpenForPerson($person);
        }

        return $this->render($template, 200, get_defined_vars());
    }
}
