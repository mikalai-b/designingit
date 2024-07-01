<?php

namespace App\Http\Middleware;

use Closure;

class CartStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (env('DISABLE_CART')) {
            return redirect()->route('cart.disabled');
        }
        return $next($request);
    }
}
