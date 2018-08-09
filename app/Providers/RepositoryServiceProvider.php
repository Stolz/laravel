<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * List of all the repositories and their contracts.
     *
     * @const array
     */
    const REPOSITORIES = [
        // Implementation => Contract
        'App\Repositories\Doctrine\CountryRepository' => 'App\Repositories\Contracts\CountryRepository',
        'App\Repositories\Doctrine\NotificationRepository' => 'App\Repositories\Contracts\NotificationRepository',
        'App\Repositories\Doctrine\PermissionRepository' => 'App\Repositories\Contracts\PermissionRepository',
        'App\Repositories\Doctrine\RoleRepository' => 'App\Repositories\Contracts\RoleRepository',
        'App\Repositories\Doctrine\UserRepository' => 'App\Repositories\Contracts\UserRepository',
    ];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register implementations for all repository contracts.
     *
     * @return void
     */
    public function register()
    {
        foreach (self::REPOSITORIES as $repository => $contract) {
            $this->app->singleton($contract, function ($app) use ($repository) {
                return $app->make($repository, [$app['em']]);
            });
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return static::REPOSITORIES;
    }
}
