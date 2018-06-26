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
        // Custom Blade directives
        Blade::if('route', function ($routes) {
            return str_is((array) $routes, Route::currentRouteName());
        });
        Blade::if('notroute', function ($routes) {
            return str_is((array) $routes, Route::currentRouteName());
        });

        // Custom Blade components
        $components = [
            'components.alert' => 'alert',
            'components.form.checkbox' => 'checkbox',
            'components.form.input' => 'input',
        ];
        foreach ($components as $path => $name) {
            Blade::component($path, $name);
        }
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
