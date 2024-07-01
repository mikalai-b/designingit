<?php


/**
 *
 */
class PasswordFormInspector extends AbstractInspector
{
    static protected $rules = [
        'current' => 'required',
        'confirm' => 'required',
        'new'     => 'required'
    ];


    /**
     *
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }


    /**
     *
     */
    public function validate($data)
    {
        if ($data['current'] && !password_verify($data['current'], $this->account->getPassword())) {
            $this->messages->add('current', 'Did not match current password');
        }

        if ($data['confirm'] && $data['new'] != $data['confirm']) {
            $this->messages->add('confirm', 'Did not match new password');
        }
    }
}
