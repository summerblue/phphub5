<?php

namespace App\Providers;

use Auth;
use Dingo\Api\Auth\Auth as DingoAuth;
use Dingo\Api\Auth\Provider\OAuth2;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

class OAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app[DingoAuth::class]->extend('oauth', function ($app) {
            $provider = new OAuth2($app['oauth2-server.authorizer']->getChecker());

            $provider->setUserResolver(function ($id) {
                Auth::loginUsingId($id);

                return User::findOrFail($id);
            });

            $provider->setClientResolver(function ($id) {
                //TODO: Logic to return a client by their ID.
            });

            return $provider;
        });
    }

    public function register()
    {
        //
    }
}
