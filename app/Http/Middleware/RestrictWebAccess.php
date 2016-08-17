<?php

namespace App\Http\Middleware;

use Closure;

class RestrictWebAccess
{
    public function handle($request, Closure $next)
    {
        // 如果是通过 API 域名进来的话，就拒绝访问
        // 这样做是为了防止网站出现双入口，混淆用户和 SEO 优化。
        if ($_SERVER['SERVER_NAME'] == config('api.domain')) {
            return response('Bad Request.', 400);
        }

        return $next($request);
    }
}
