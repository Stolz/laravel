<?php

namespace App\Providers;

use App\Repositories\Contracts\AnnouncementRepository;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

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

        // Register view composers
        View::composer('layouts.app', function ($view) {
            // Get active announcements
            $announcements = $this->app[AnnouncementRepository::class]->getBy('active', true);
            $view->with('activeAnnouncements', $announcements);
        });
    }
}
