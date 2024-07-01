<?php

namespace App\Http\Actions;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

/**
 *
 */
class ForgotPassword extends AbstractAction
{
    use SendsPasswordResetEmails;

    /**
     *
     */
    public function __invoke()
    {
        if ($this->request->getMethod() == 'POST') {
            $this->validateEmail($this->request);

            $response = $this->broker()->sendResetLink(
                $this->request->only('email')
            );

            return $response == Password::RESET_LINK_SENT
                        ? $this->sendResetLinkResponse($response)
                        : $this->sendResetLinkFailedResponse($this->request, $response);
        }

        return $this->render('pages.password.email');
    }
}
