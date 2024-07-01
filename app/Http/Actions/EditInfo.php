<?php

namespace App\Http\Actions;

use People;
use PersonFormInspector;
use App\Exceptions\ValidationException;

/**
 *
 */
class EditInfo extends AbstractAction
{
    const MSG_SUCCESS = 'Your information has been successfully updated.';

    /**
     *
     */
    public function __invoke(People $people, PersonFormInspector $inspector)
    {
        $person = $this->auth->user();

        if ($this->request->getMethod() == 'POST') {
            try {
                $person->setTitle($this->request->input('title'));
                $person->setFirstName($this->request->input('firstName'));
                $person->setLastName($this->request->input('lastName'));
                $person->setCredentials($this->request->input('credentials'));
                $person->setDateOfBirth(new \DateTime($this->request->input('dateOfBirth')));
                $person->setEmail($this->request->input('email'));
                $person->setPhone($this->request->input('phone'));
                $person->setAvatar($this->request->file('avatar', $person->getAvatar()));

                if ($this->request->input('remove-avatar', NULL)) {
                    $person->setAvatar(NULL);
                }

                $inspector->run($this->request->all());

                $this->session->flash('success', static::MSG_SUCCESS);

                return $this->redirect('dashboard');

            } catch (ValidationException $e) {
                $errors = $inspector->getMessages();

                $this->session->flash('error', $e->getMessage());

                return $this->render('pages/dashboard/info', 400, get_defined_vars());
            }
        }

        return $this->render('pages/dashboard/info', 200, get_defined_vars());
    }
}
