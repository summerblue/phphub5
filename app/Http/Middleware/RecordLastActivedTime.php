<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RecordLastActivedTime
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            Auth::user()->recordLastActivedAt();
        }

        return $next($request);
    }
}
