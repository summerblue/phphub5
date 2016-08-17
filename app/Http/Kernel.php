<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\CheckUserIsItBanned::class,
        \App\Http\Middleware\RecordLastActivedTime::class,
        \Spatie\Pjax\Middleware\FilterIfPjax::class,

        // API
        \LucaDegasperi\OAuth2Server\Middleware\OAuthExceptionHandlerMiddleware::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'       => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest'      => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'admin_auth' => \App\Http\Middleware\AdminAuth::class,
        'verified_email' => \App\Http\Middleware\RequireVerifiedEmail::class,
        
        // API
        'oauth2'     => \App\Http\Middleware\OAuthMiddleware::class,
        'check-authorization-params' => \LucaDegasperi\OAuth2Server\Middleware\CheckAuthCodeRequestMiddleware::class,
        'api.throttle'               => \Dingo\Api\Http\Middleware\RateLimit::class,

        // 限制 Web 内容只能是 web 访问
        'restrict_web_access' => \App\Http\Middleware\RestrictWebAccess::class,
    ];
}
