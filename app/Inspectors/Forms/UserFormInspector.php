<?php


/**
 *
 */
class UserFormInspector extends AbstractInspector
{
    static protected $rules = [
        'firstName' => 'required',
        'lastName'  => 'required',
        'email'     => 'required|email'
    ];


    /**
     *
     */
    public function validate($data, $person = NULL)
    {
        if (!$person) {
            $person = $this->auth->user();
        }

        $unique = $this->validator->validateUnique('email', $data['email'], [
            'person',
            'email',
            $person->getId()
        ]);

        if (!$unique) {
            $this->messages->add('email', 'This e-mail is already taken.');
        }

        if ($person->hasRoleByName('Provider')) {
            $this->validator->addRules(['credentials' => 'required']);
        }
    }
}
