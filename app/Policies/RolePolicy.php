<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy extends RoleBasedPolicy
{
    /**
     * Run before any ability checks.
     *
     * If null is returned, the authorization will fall through to the policy method.
     *
     * WARNING: This method will not be called unless there is a method in this
     * class with a name matching $ability.
     *
     * @param  \App\Models\User $user
     * @param  mixed $ability
     * @return mixed
     */
    public function before(User $user, $ability)
    {
        // Parent check for super admin has priority
        $preCheck = parent::before($user, $ability);
        if ($preCheck !== null) {
            return $preCheck;
        }

        // If the user cannot use the 'access' module no need to further check the policy method
        if ($this->userRoleCannot($user, 'use-access-module')) {
            return false;
        }
    }

    /**
     * Determine whether the current user can list roles.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $this->userRoleCan($user, 'role-list');
    }

    /**
     * Determine whether the current user can create roles.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->userRoleCan($user, 'role-create');
    }

    /**
     * Determine whether the current user can view the given role.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Role $role
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        return $this->userRoleCan($user, 'role-view');
    }

    /**
     * Determine whether the current user can update the given role.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Role $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        return ! $role->isSuperAdmin() and $this->userRoleCan($user, 'role-update');
    }

    /**
     * Determine whether the current user can delete the given role.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Role $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        return ! $role->isSuperAdmin() and $this->userRoleCan($user, 'role-delete');
    }
}
