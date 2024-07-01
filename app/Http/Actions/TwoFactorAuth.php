<?php

namespace App\Http\Actions;

use Accounts;
use Illuminate\Support\MessageBag;

/**
 *
 */
class TwoFactorAuth extends AbstractAction
{
    /**
     *
     */
    public function __invoke(Accounts $accounts, MessageBag $errors)
    {
        $user    = $this->auth->user();
        $account = $user->getAccount();

        if ($this->request->method() == 'POST') {
                if ($this->request->input('code') == $account->getToken()) {
                    $account->setTokenExpiry(NULL);
                    $account->setToken(NULL);
                    $accounts->store($account);

                    return $this->redirect('dashboard');

                } else {
                    $errors->add('code', 'The authentication code does not match.  Try again.');
                }
        }

        return $this->render('pages.auth', 200, get_defined_vars());
    }
}
