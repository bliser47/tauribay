<?php

namespace TauriBay\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'TauriBay\Model' => 'TauriBay\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
         $this->registerPolicies();
         if(env('REDIRECT_HTTPS')) {
             $url->formatScheme('https');
         }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
         if (env('APP_ENV') === 'production') {
            $this->app['url']->forceScheme('https');
         }
    }
}
