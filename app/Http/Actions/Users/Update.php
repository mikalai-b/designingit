<?php

namespace App\Http\Actions\Users;

use People;
use Providers;
use UserFormInspector;
use App\Exceptions;

use App\Http\Actions\AbstractAction;
use Symfony\Component\HttpFoundation\Response;


/**
 *
 */
class Update extends AbstractAction
{
    use Populate;

    /**
     *
     */
    const MSG_SUCCESS = 'User "%s" has been successfully updated.';


    /**
     * @param People $people The people repository
     * @param string $id The id of the person to select
     * @return Response The response from the action
     */
    public function __invoke(People $people, Providers $providers, UserFormInspector $inspector, $id)
    {
        $this->people = $people;
        $provider     = $people->getProviders()->findOneByPerson($id);
        $account      = $people->getAccounts()->findOneByPerson($id);
        $person       = $people->find($id);

        if (!$person) {
            return $this->respond(NULL, 404);
        }

        if (!$provider) {
            $provider = $people->getProviders()->create();
        }

        if (!$account) {
            $account = $people->getAccounts()->create();
        }

        $this->authorize('update', $person);

        try {
            $errors = FALSE;

            $this->populate($person, $account, $provider);
            $inspector->run($this->request->all(), $person);

            $this->session->flash('success', sprintf(static::MSG_SUCCESS, $person));

            return $this->redirect('users');

        } catch (Exceptions\ValidationException $e) {
            $errors = $e->getMessages();

            $this->session->flash('error', $e->getMessage());

            return $this->render('pages.users.update', 400, get_defined_vars());
        }
    }
}
