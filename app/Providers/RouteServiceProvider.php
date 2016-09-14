<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $api_namespace = 'App\Http\ApiControllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        $router->pattern('id', '[0-9]+');

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        // See: https://laravel-china.org/topics/2484#reply2
        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::group([
            'namespace' => $this->namespace,
            'middleware' => 'restrict_web_access',
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    protected function mapApiRoutes()
    {
        $api_router = app('Dingo\Api\Routing\Router');
        $api_router->group([
            'version'   => config('api.prefix'),
            'namespace' => $this->api_namespace,
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}
