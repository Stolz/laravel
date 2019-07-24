<?php

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider implements DeferrableProvider
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
     * Register implementations for all repository contracts.
     *
     * @return void
     */
    public function register()
    {
        foreach (self::REPOSITORIES as $repository => $contract)
            $this->app->singleton($contract, $repository);
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
