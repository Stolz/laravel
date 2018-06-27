<?php

namespace App\Providers;

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
        'App\Models\Country' => 'App\Policies\CountryPolicy',
        'App\Models\Role' => 'App\Policies\RolePolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'module' => 'App\Policies\ModulePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Register our own user provider for authentication
        Auth::provider('userRepository', function ($app, array $config): \Illuminate\Contracts\Auth\UserProvider {
            return $app->make(\App\Repositories\Contracts\UserRepository::class);
        });
    }
}
