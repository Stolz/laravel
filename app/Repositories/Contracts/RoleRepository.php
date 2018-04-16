<?php

namespace App\Repositories\Contracts;

use App\Models\Role;

interface RoleRepository extends ModelRepository
{
    /**
     * Determine whether the role has all of the given permissions by name.
     *
     * @param  \App\Models\Role $role
     * @param  string|array $permissions
     * @return bool
     */
    public function hasPermission(Role $role, $permissions): bool;

    /**
     * Determine whether the role has any of the given permissions by name.
     *
     * @param  \App\Models\Role $role
     * @param  string|array $permissions
     * @return bool
     */
    public function hasAnyPermission(Role $role, $permissions): bool;

    /**
     * Replace current role permissions with the given ones.
     *
     * @param  \App\Models\Role $role
     * @param  \Illuminate\Support\Collection of \App\Models\Permission $permissions
     * @return bool
     */
    public function replacePermissions(Role $role, \Illuminate\Support\Collection $permissions): bool;
}
