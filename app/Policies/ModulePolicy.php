<?php

namespace App\Policies;

use App\Models\User;

class ModulePolicy extends RoleBasedPolicy
{
    /**
     * Determine whether the current user can use the 'access' module.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function access(User $user)
    {
        return $this->userRoleCan($user, 'use-access-module');
    }

    /**
     * Determine whether the current user can use the 'master' module.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function master(User $user)
    {
        return $this->userRoleCan($user, 'use-master-module');
    }
}
