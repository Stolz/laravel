<?php

namespace Tests\Traits;

use App\Models\Permission;

trait CreatesPermissions
{
    /**
     * Create a test permission.
     *
     * @param  array $attributes
     * @return \App\Models\Permission
     */
    protected function createPermission(array $attributes = []): Permission
    {
        if (! isset($attributes['name'])) {
            $attributes['name'] = str_random(5);
        }

        // Create permission
        $permission = Permission::make($attributes);
        $this->permissionRepository->create($permission);

        return $permission;
    }
}
