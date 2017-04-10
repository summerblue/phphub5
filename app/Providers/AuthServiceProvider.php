<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model'  => 'App\Policies\ModelPolicy',
        \App\Models\User::class  => \App\Policies\UserPolicy::class,
        \App\Models\Topic::class => \App\Policies\TopicPolicy::class,
        \App\Models\Reply::class => \App\Policies\ReplyPolicy::class,
        \App\Models\Blog::class => \App\Policies\BlogPolicy::class,
        \App\Models\Thread::class => \App\Policies\ThreadPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //
    }
}
