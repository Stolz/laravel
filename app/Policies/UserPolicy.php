<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends RoleBasedPolicy
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
     * Determine whether the current user can list users.
     *
     * @param  \App\Models\User $user Current user
     * @return mixed
     */
    public function list(User $user)
    {
        return $this->userRoleCan($user, 'user-list');
    }

    /**
     * Determine whether the current user can create users.
     *
     * @param  \App\Models\User $user Current user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->userRoleCan($user, 'user-create');
    }

    /**
     * Determine whether the current user can view the given user.
     *
     * @param  \App\Models\User $user      Current user
     * @param  \App\Models\User $givenUser
     * @return mixed
     */
    public function view(User $user, User $givenUser)
    {
        return $this->userRoleCan($user, 'user-view');
    }

    /**
     * Determine whether the current user can update the given user.
     *
     * @param  \App\Models\User $user      Current user
     * @param  \App\Models\User $givenUser
     * @return mixed
     */
    public function update(User $user, User $givenUser)
    {
        return $this->userRoleCan($user, 'user-update');
    }

    /**
     * Determine whether the current user can delete the given user.
     *
     * @param  \App\Models\User $user      Current user
     * @param  \App\Models\User $givenUser
     * @return mixed
     */
    public function delete(User $user, User $givenUser)
    {
        return $this->userRoleCan($user, 'user-delete');
    }
}
