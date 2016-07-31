<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Request;

class RecordLastActivedTime
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Request::is('notifications/count') == false) {
            Auth::user()->recordLastActivedAt();
        }

        return $next($request);
    }
}
