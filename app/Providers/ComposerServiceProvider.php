<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        view()->composer('*', function ($view) {
            $view->with('show_crx_hint', check_show_crx_hint() ? 'yes' : 'no');
            $view->with('currentUser', \Auth::user());
            $view->with('siteStat', app('Phphub\Stat\Stat')->getSiteStat());

            if (\Auth::check()) {
                $view->with('following_users_json', \Auth::user()->present()->followingUsersJson());
            }

         });
    }

    public function register()
    {
    }
}
