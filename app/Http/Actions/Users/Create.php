<?php

namespace App\Http\Actions\Users;

use Roles;
use People;
use Providers;
use Prescriptions;
use UserFormInspector;
use App\Exceptions\ValidationException;

use App\Http\Actions\AbstractAction;
use Symfony\Component\HttpFoundation\Response;


/**
 *
 */
class Create extends AbstractAction
{
    use Populate;

    /**
     *
     */
    const MSG_SUCCESS = 'User "%s" has been successfully created.';

    /**
     * @param People $people The people repository
     * @param string $id The id of the person to select
     * @return Response The response from the action
     */
    public function __invoke(People $people, UserFormInspector $inspector)
    {
        $this->people = $people;
        $provider     = $people->getProviders()->create();
        $account      = $people->getAccounts()->create();
        $person       = $people->create();

        $this->authorize('create', $person);

        try {
            $errors = FALSE;

            $this->populate($person, $account, $provider);
            $inspector->run($this->request->all());

            $people->store($person, TRUE);
            $people->getAccounts()->store($account);

            if ($person->hasRoleByName('Provider')) {
                $people->getProviders()->store($provider);
            }

            $this->session->flash('success', static::MSG_SUCCESS);

            return $this->redirect('users');

        } catch (ValidationException $e) {
            $errors = $e->getMessages();

            $this->session->flash('error', $e->getMessage());

            return $this->render('pages.users.create', 400, get_defined_vars());
        }

        return $this->render('pages.users.create', 200, get_defined_vars());
    }
}
