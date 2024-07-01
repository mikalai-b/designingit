<?php

namespace App\Http\Middleware;

use Closure;

class TranslateMethod
{
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
        if ($request->getMethod() == 'POST') {
            switch ($request->input('action', NULL)) {
                case 'update':
                    $request->setMethod('PUT');
                    break;

                case 'remove':
                    $request->setMethod('DELETE');
                    break;
            }
        }

        return $next($request);
    }
}
