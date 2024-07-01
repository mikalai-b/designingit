<?php

/**
 *
 */
class Registration
{
    /**
     *
     */
    public function __construct(People $people, Accounts $accounts, RegistrationFormInspector $inspector)
    {
        $this->people    = $people;
        $this->accounts  = $accounts;
        $this->inspector = $inspector;
    }


    /**
     *
     */
    public function create($email, $password, $agree)
    {
        $this->inspector->run([
            'email'    => $email,
            'password' => $password,
            'agree'    => $agree
        ]);

        $person  = $this->people->create();
        $account = $this->accounts->create();

        $person->setEmail($email);
        $this->people->store($person, TRUE);

        $account->setPerson($person);
        $account->setPassword(password_hash($password, PASSWORD_BCRYPT));

        $this->accounts->store($account, TRUE);

        return $person;
    }


    /**
     *
     */
    public function getErrors()
    {
        return $this->inspector->getMessages();
    }
}
