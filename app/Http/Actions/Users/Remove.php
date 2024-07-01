<?php

namespace App\Http\Actions\Users;

use People;
use App\Exceptions;

use App\Http\Actions\AbstractAction;
use Symfony\Component\HttpFoundation\Response;


/**
 *
 */
class Remove extends AbstractAction
{
    use Populate;

    /**
     *
     */
    const MSG_SUCCESS = 'User "%s" has been successfully removed.';


    /**
     * @param People $people The people repository
     * @param string $id The id of the person to select
     * @return Response The response from the action
     */
    public function __invoke(People $people, $id)
    {
        $person = $people->find($id);

        $this->authorize('remove', $person);

        try {
            $people->remove($person);

            $this->session->flash('success', sprintf(static::MSG_SUCCESS, $person));

            return $this->redirect('users');

        } catch (Exceptions\ValidationException $e) {
            $errors = $e->getMessages();

            $this->session->flash('error', $e->getMessage());

            return $this->render('pages.users.remove', 400, get_defined_vars());
        }
    }
}
