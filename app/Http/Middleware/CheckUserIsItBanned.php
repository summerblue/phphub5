<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Request;

class CheckUserIsItBanned
{

    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_banned == 'yes' && Request::is('user-banned') == false) {
            return redirect('/user-banned');
        }

        return $next($request);
    }
}
