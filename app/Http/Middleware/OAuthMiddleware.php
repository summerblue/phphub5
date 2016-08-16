<?php

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Routing\Router;
use Dingo\Api\Auth\Auth as Authentication;
use League\OAuth2\Server\Exception\AccessDeniedException;
use LucaDegasperi\OAuth2Server\Authorizer;

class OAuthMiddleware
{
    protected $authorizer;
    private $auth;
    private $router;

    /**
     * Create a new auth middleware instance.
     *
     * @param \Dingo\Api\Routing\Router $router
     * @param \Dingo\Api\Auth\Auth      $auth
     * @param Authorizer                $authorizer
     */
    public function __construct(Router $router, Authentication $auth, Authorizer $authorizer)
    {
        $this->router = $router;
        $this->auth = $auth;
        $this->authorizer = $authorizer;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request                $request
     * @param Closure|\PHPHub\Http\Middleware\Closure $next
     * @param $type
     *
     * @return mixed
     *
     * @throws AccessDeniedException
     */
    public function handle($request, Closure $next, $type = null)
    {
        $route = $this->router->getCurrentRoute();

        if (! $this->auth->check(false)) {
            $this->auth->authenticate($route->getAuthenticationProviders());
        }

        $this->authorizer->setRequest($request);

        //type: user or client
        if ($type && $this->authorizer->getResourceOwnerType() !== $type) {
            throw new AccessDeniedException();
        }

        return $next($request);
    }
}
