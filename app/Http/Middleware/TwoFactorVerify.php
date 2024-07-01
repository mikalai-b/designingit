<?php

namespace App\Http\Middleware;

use Closure;
use Accounts;
use App\Mail;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Str;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Auth\AuthManager;

class TwoFactorVerify
{
    /**
     *
     */
    protected $auth = NULL;


    /**
     *
     */
    protected $url = NULL;


    /**
     *
     */
    public function __construct(UrlGenerator $url, AuthManager $auth, Mailer $mailer, Accounts $accounts, Str $string, ResponseFactory $response_factory)
    {
        $this->url             = $url;
        $this->auth            = $auth;
        $this->mailer          = $mailer;
        $this->string          = $string;
        $this->accounts        = $accounts;
        $this->responseFactory = $response_factory;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (env('DISABLE_2FA')) {
            return $next($request);
        }

        $user = $this->auth->user();

        if ($user) {
            $account = $user->getAccount();
            $expiry  = $account->getTokenExpiry();

            if ($expiry && $expiry <= new \DateTime()) {
                if (!$account->getToken()) {
                    $account->setToken($this->string->random(8));
                    $this->accounts->store($user);
                }

                $this->mailer
                    ->to($user->getEmail())
                    ->send(new Mail\TwoFactorVerify([
                        'person' => $user,
                        'token'  => $account->getToken()
                    ]))
                ;

                return $this->responseFactory->make(NULL, 303, [
                    'Location' => $this->url->route('2fa')
                ]);
            }
        }

        return $next($request);
    }
}
