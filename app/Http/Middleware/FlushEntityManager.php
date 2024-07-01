<?php

namespace App\Http\Middleware;

use Closure;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

class FlushEntityManager
{
    /**
     *
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
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
        $response = $next($request);

        if ($this->em->isOpen() && $response->status() < 400) {
            $this->em->flush();
        }

        return $response;
    }
}
