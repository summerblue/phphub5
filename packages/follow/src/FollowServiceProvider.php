<?php
namespace Smartisan\Follow;

use Illuminate\Support\ServiceProvider;

class FollowServiceProvider extends ServiceProvider
{
    /**
     * Application bootstrap event.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/follow.php' => config_path('follow.php'),
        ], 'config');

        $timestamp = date('Y_m_d_His', time());

        $this->publishes([
            __DIR__ . '/../database/migrations/create_followers_table.php' =>
                database_path('migrations/' . $timestamp . '_create_followers_table.php')
        ], 'migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}