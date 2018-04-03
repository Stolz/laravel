<?php

namespace App\Providers;

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
            return new \App\Repositories\Doctrine\UserRepository(app('em'));
        });
    }
}
