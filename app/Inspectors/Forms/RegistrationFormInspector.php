<?php


/**
 *
 */
class RegistrationFormInspector extends AbstractInspector
{
    static protected $rules = [
        'email'    => 'required|email|unique:person',
        'password' => 'required'
    ];

    static protected $errors = [
        'email.unique' => 'The email has already been taken. If that\'s your account <a href="/login?return=cart">log in now</a>.'
    ];

    /**
     *
     */
    public function validate($data)
    {
        if (empty($data['agree'])) {
            $this->messages->add('agree', 'You must agree to create an account');
        }
    }
}
