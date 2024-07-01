<?php

namespace App\Http\Actions;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 *
 */
class Logout extends AbstractAction
{
    use AuthenticatesUsers, ValidatesRequests;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';


    /**
     *
     */
    public function __invoke()
    {
        return $this->logout($this->request);
    }
}
