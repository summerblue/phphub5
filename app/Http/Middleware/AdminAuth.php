<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class AdminAuth
{

    protected $auth;


    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if (!$this->auth->user()->may('visit_admin')) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}
