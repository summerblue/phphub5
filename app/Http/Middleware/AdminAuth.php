<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminAuth
{

    public function handle($request, Closure $next)
    {
        if (!Auth::user()->may('visit_admin')) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}
