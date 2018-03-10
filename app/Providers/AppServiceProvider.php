<?php

namespace App\Providers;

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register User model repository
        $this->app->bind(\App\Repositories\Contracts\UserRepository::class, function () {
            $database = app('db');

            return new \App\Repositories\UserRepositoryViaCapsule($database);
        });
    }
}
