<?php

namespace App\Repositories\Doctrine;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepository as RoleRepositoryContract;

class RoleRepository extends ModelRepository implements RoleRepositoryContract
{
    /**
     * Full class name of the model this repository is in charge of.
     *
     * @var string
     */
    protected $modelClass = Role::class;

    /**
     * Alias to be used to reference the model within query builder.
     *
     * @var string
     */
    protected $modelAlias = 'role';

    /**
     * Determine whether the role has all of the given permissions by name
     *
     * @param  \App\Models\Role $role
     * @param  string|array $permissions
     * @return bool
     */
    public function hasPermission(Role $role, $permissions): bool
    {
        $permissions = (array) $permissions;
        if (! $permissions)
            return false;

        $matching = $role->getPermissions()->filter(function ($permission) use ($permissions) {
            return in_array($permission->getName(), $permissions, true);
        });

        return count($permissions) === count($matching);
    }

    /**
     * Determine whether the role has any of the given permissions by name.
     *
     * @param  \App\Models\Role $role
     * @param  string|array $permissions
     * @return bool
     */
    public function hasAnyPermission(Role $role, $permissions): bool
    {
        $permissions = (array) $permissions;
        if (! $permissions)
            return false;

        return $role->getPermissions()->exists(function ($key, $permission) use ($permissions) {
            return in_array($permission->getName(), $permissions, true);
        });
    }

    /**
     * Replace current role permissions with the given ones.
     *
     * @param  \App\Models\Role $role
     * @param  \Illuminate\Support\Collection of \App\Models\Permission $permissions
     * @return bool
     */
    public function replacePermissions(Role $role, \Illuminate\Support\Collection $permissions): bool
    {
        $rolePermissions = $role->getPermissions();

        // Remove old permissions
        $rolePermissions->clear();

        // Assign new permissions
        foreach ($permissions as $permission)
            $rolePermissions->add($permission);

        // Persist changes
        return $this->update($role);
    }
}
