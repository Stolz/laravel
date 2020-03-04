<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Register route model bindings
        $modelRouteBindings = [
            'announcement' => \App\Repositories\Contracts\AnnouncementRepository::class,
            'country' => \App\Repositories\Contracts\CountryRepository::class,
            'role' => \App\Repositories\Contracts\RoleRepository::class,
            'user' => \App\Repositories\Contracts\UserRepository::class,
        ];
        foreach ($modelRouteBindings as $name => $repository) {
            Route::bind($name, function ($id) use ($name, $repository) {
                return app($repository)->find((int) $id) ?? abort(404, sprintf(_("%s with id '%s' not found"), title_case(snake_case($name, ' ')), $id));
            });
        }
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->attribute(0, 'https') // Make routes of the group only available via HTTPS
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::middleware('api')
             ->namespace($this->namespace . '\Api')
             ->prefix('api')
             ->name('api.')
             ->attribute(0, 'https') // Make routes of the group only available via HTTPS
             ->group(base_path('routes/api.php'));
    }
}
