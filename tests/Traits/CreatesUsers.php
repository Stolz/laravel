<?php

namespace Tests\Traits;

use App\Models\Role;
use App\Models\User;

trait CreatesUsers
{
    /**
     * Create a test user.
     *
     * @param  array $attributes
     * @return \App\Models\User
     */
    protected function createUser(array $attributes = []): User
    {
        // Create role if none was provided
        if (! isset($attributes['role'])) {
            $attributes['role'] = factory(Role::class)->make();
            $this->roleRepository->create($attributes['role']);
        }

        // Create user
        $user = factory(User::class)->make(array_except($attributes, 'permissions'));
        $this->userRepository->create($user);

        // Assign permissions to role
        if ($attributes['permissions'] ?? false) {
            $newPermissions = $this->permissionRepository->getBy('name', $attributes['permissions']);
            $this->roleRepository->replacePermissions($attributes['role'], $newPermissions);
        }

        return $user;
    }
}
