<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * Only add here policies that don't follow the default naming convention.
     * The rest will be auto-discovered using `guessPolicyNamesUsing()` below.
     *
     * @var array
     */
    protected $policies = [
        //'App\Models\Foo\Bar' => 'App\Policies\FooBarPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Default policy auto-discover logic looks for policies under `app\ModelName\Policies` directory. Since our models
        // have an extra namespace (app\Models\ModelName) we have to register our own logic to properly discover policies.
        Gate::guessPolicyNamesUsing(function ($modelClass) {
            $policy = studly_case(class_basename($modelClass));

            return ["App\Policies\\{$policy}Policy"];
        });

        // Register our own user provider for authentication
        Auth::provider('userRepository', function ($app, array $config): \Illuminate\Contracts\Auth\UserProvider {
            return $app->make(\App\Repositories\Contracts\UserRepository::class);
        });
    }
}
