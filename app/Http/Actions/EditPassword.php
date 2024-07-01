<?php

namespace App\Http\Actions;

use Accounts;
use PasswordFormInspector;
use App\Exceptions\ValidationException;

/**
 *
 */
class EditPassword extends AbstractAction
{
    const MSG_SUCCESS = 'Your password has been successfully updated.';

    /**
     *
     */
    public function __invoke(Accounts $accounts, PasswordFormInspector $inspector)
    {
        $person  = $this->auth->user();
        $account = $person->getAccount();

        if ($this->request->getMethod() == 'POST') {
            try {
                $password = $this->request->input('new');

                $inspector->setAccount($account)->run($this->request->all());
                $account->setPassword(password_hash($password, PASSWORD_BCRYPT));
                $this->session->flash('success', static::MSG_SUCCESS);

                return $this->redirect('dashboard');

            } catch (ValidationException $e) {
                $errors = $inspector->getMessages();

                $this->session->flash('error', $e->getMessage());
            }
        }

        return $this->render('pages/dashboard/password', 200, get_defined_vars());
    }
}
