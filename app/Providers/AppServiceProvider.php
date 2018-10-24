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
        $this->registerBlade();
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

    /**
     * Register Blade directives and components.
     *
     * @return void
     */
    protected function registerBlade()
    {
        // Custom Blade directives
        Blade::if('route', function ($routes) {
            return str_is((array) $routes, Route::currentRouteName());
        });
        Blade::if('notroute', function ($routes) {
            return ! str_is((array) $routes, Route::currentRouteName());
        });

        // Custom Blade components
        $components = [
            'alert.alert' => 'alert',
            'alert.no-results' => 'noResultsAlert',
            'avatar' => 'avatar',
            'card.card' => 'card',
            'colorize' => 'colorize',
            'flag' => 'flag',
            'form.checkbox' => 'checkbox',
            'form.input' => 'input',
            'form.radios' => 'radios',
            'form.select' => 'select',
            'form.textarea' => 'textarea',
            'modal.delete-model' => 'deleteModelModal',
            'table.pagination-caption' => 'tableCaption',
            'table.sortable-headers' => 'tableHeaders',
            'table.table' => 'table',
        ];
        foreach ($components as $path => $name) {
            Blade::component("components.$path", $name);
        }
    }
}
