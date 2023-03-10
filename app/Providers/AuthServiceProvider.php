<?php

namespace App\Providers;

use App\Services\Auth\jwtUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('jwtUserProvider', function ($app, array $config) {
            return new jwtUserProvider(
                $app->make('App\User'),
                app()->make('App\Repositories\UserRepository'),
                app()->make('App\Services\Api\AccountHttpService')
            );
        });
    }
}
