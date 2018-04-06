<?php

namespace App\Providers;

use App\Repositories\Contracts\NotificationRepository;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
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
        $entityManager = app('em');

        $this->app->bind(NotificationRepository::class, function () use ($entityManager) {
            return new \App\Repositories\Doctrine\NotificationRepository($entityManager);
        });

        $this->app->bind(UserRepository::class, function () use ($entityManager) {
            return new \App\Repositories\Doctrine\UserRepository($entityManager);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            NotificationRepository::class,
            UserRepository::class,
        ];
    }
}
