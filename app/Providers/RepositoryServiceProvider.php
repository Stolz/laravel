<?php

namespace App\Providers;

use App\Repositories\Contracts\CountryRepository;
use App\Repositories\Contracts\NotificationRepository;
use App\Repositories\Contracts\PermissionRepository;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * List of all the repository contracts.
     *
     * @const array
     */
    const CONTRACTS = [
        CountryRepository::class,
        NotificationRepository::class,
        PermissionRepository::class,
        RoleRepository::class,
        UserRepository::class,
    ];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register repository implementations for all countries.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CountryRepository::class, function () {
            return new \App\Repositories\Doctrine\CountryRepository($this->app['em']);
        });

        $this->app->singleton(NotificationRepository::class, function () {
            return new \App\Repositories\Doctrine\NotificationRepository($this->app['em']);
        });

        $this->app->singleton(PermissionRepository::class, function () {
            return new \App\Repositories\Doctrine\PermissionRepository($this->app['em']);
        });

        $this->app->singleton(RoleRepository::class, function () {
            return new \App\Repositories\Doctrine\RoleRepository($this->app['em']);
        });

        $this->app->singleton(UserRepository::class, function () {
            return new \App\Repositories\Doctrine\UserRepository($this->app['em']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return static::CONTRACTS;
    }
}
