<?php

namespace App\Http\Actions;

use Accounts;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 *
 */
class Login extends AbstractAction
{
    use AuthenticatesUsers, ValidatesRequests;

    /**
     *
     */
    protected $accounts = NULL;


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';


    /**
     *
     */
    public function __invoke(Accounts $accounts)
    {
        $this->accounts = $accounts;

        if ($this->request->getMethod() == 'POST') {
            return $this->login($this->request);
        }

        return $this->render('pages.login');
    }


    /**
     *
     */
    public function redirectTo() {
        $return = $this->request->input('return', NULL);

        if (!$this->validateReturn($return)) {
            $return = 'dashboard';
        }

        return $this->url->route($return);
    }


    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRoleByName('Admin') || $user->hasRoleByName('Provider')) {
            $account = $user->getAccount();

            $account->setTokenExpiry(new \DateTime('now'));
            $this->accounts->store($account);
        }
    }


    /**
     *
     */
    protected function validateReturn($return)
    {
        if (!$return) {
            return FALSE;
        }

        if (strpos($return, '/') !== FALSE) {
            return FALSE;
        }

        return TRUE;
    }
}
