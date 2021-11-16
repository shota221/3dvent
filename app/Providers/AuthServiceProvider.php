<?php

namespace App\Providers;


use App\Models;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Auth\ApiTokenGate;
use App\Http\Auth\AppkeyGate;
use App\Http\Auth\AdminUserGate;
use App\Http\Auth\OrgUserGate;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // eloquent user provider extend
        Auth::provider('eloquent_user', function ($app, array $config) {
            return new \App\Http\Auth\UserProvider($this->app['hash'], $config['role']);
        });

        $this->app['auth']->extend('user_token', function ($app, string $name, array $config) {
            return new \App\Http\Auth\UserTokenGuard(
                Auth::createUserProvider($config['provider']),
                $app['request'],
                $config
            );
        });

        if ($this->app->isHttpRouteTypeApi()) {
            ApiTokenGate::define();

            AppkeyGate::define();

            OrgUserGate::define();
        }

        if ($this->app->isHttpRouteTypeAdmin()) {
            AdminUserGate::define();
        }

        if ($this->app->isHttpRouteTypeOrg()) {
            OrgUserGate::define();
        }
    }
}
