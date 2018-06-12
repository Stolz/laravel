<?php

use Illuminate\Database\Seeder as BaseSeeder;

abstract class Seeder extends BaseSeeder
{
    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->countryRepository = app(App\Repositories\Contracts\CountryRepository::class);
        $this->notificationRepository = app(App\Repositories\Contracts\NotificationRepository::class);
        $this->permissionRepository = app(App\Repositories\Contracts\PermissionRepository::class);
        $this->roleRepository = app(App\Repositories\Contracts\RoleRepository::class);
        $this->userRepository = app(App\Repositories\Contracts\UserRepository::class);
    }
}
