<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
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
        // Custom blade directives
        Blade::if('route', function ($routes) {
            return in_array(Route::currentRouteName(), (array) $routes, true);
        });
        Blade::if('notroute', function ($routes) {
            return ! in_array(Route::currentRouteName(), (array) $routes, true);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
