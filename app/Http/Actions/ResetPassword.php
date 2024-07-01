<?php

namespace App\Http\Actions;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Str;

/**
 *
 */
class ResetPassword extends AbstractAction
{
    use ResetsPasswords;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';


    /**
     *
     */
    public function __invoke(Hasher $hasher, Guard $guard, Str $str, $token = NULL)
    {
        $email = $this->request->email;

        if ($this->request->getMethod() == 'POST') {
            $this->validate($this->request, $this->rules(), $this->validationErrorMessages());

            $response = $this->broker()->reset(
                $this->credentials($this->request), function ($person, $password) use ($hasher, $guard, $str) {
                    $person->getAccount()->setPassword($hasher->make($password));
                    $person->setRememberToken($str->random(60));

                    event(new PasswordReset($person));

                    $guard->login($person);
                }
            );

            return $response == Password::PASSWORD_RESET
                ? $this->sendResetResponse($response)
                : $this->sendResetFailedResponse($this->request, $response);
        }

        return $this->render('pages.password.reset', 200, get_defined_vars());
    }
}
