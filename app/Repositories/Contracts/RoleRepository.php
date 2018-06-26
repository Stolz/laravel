<?php

namespace App\Repositories\Contracts;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Collection;

interface RoleRepository extends ModelRepository
{
    /**
     * Add a permission to a role.
     *
     * @param  \App\Models\Role $role
     * @param  \App\Models\Permission $permission
     * @return bool
     */
    public function addPermission(Role $role, Permission $permission): bool;

    /**
     * Remove a permission from a role.
     *
     * @param  \App\Models\Role $role
     * @param  \App\Models\Permission $permission
     * @return bool
     */
    public function removePermission(Role $role, Permission $permission): bool;

    /**
     * Replace current role permissions with the given ones.
     *
     * @param  \App\Models\Role $role
     * @param  \Illuminate\Support\Collection of \App\Models\Permission $permissions
     * @return bool
     */
    public function replacePermissions(Role $role, Collection $permissions): bool;

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
}
