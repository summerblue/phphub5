<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        view()->composer('*', function ($view) {
             $view->with('currentUser', \Auth::user());
             $view->with('siteStat', app('Phphub\Stat\Stat')->getSiteStat());
             $view->with('siteTip', \App\Models\Tip::getRandTip());
         });
    }

    public function register()
    {
    }
}
