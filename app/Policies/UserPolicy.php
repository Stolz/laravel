<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends RoleBasedPolicy
{
   /**
     * Determine whether the current user can list users.
     *
     * @param  \App\Models\User $currentUser
     * @return mixed
     */
    public function list(User $currentUser)
    {
        return $this->userRoleCan($currentUser, 'user-list');
    }

    /**
     * Determine whether the current user can create users.
     *
     * @param  \App\Models\User $currentUser
     * @return mixed
     */
    public function create(User $currentUser)
    {
        return $this->userRoleCan($currentUser, 'user-create');
    }

    /**
     * Determine whether the current user can view the given user.
     *
     * @param  \App\Models\User $currentUser
     * @param  \App\Models\User $givenUser
     * @return mixed
     */
    public function view(User $currentUser, User $givenUser)
    {
        return $this->userRoleCan($currentUser, 'user-view');
    }

    /**
     * Determine whether the current user can update the given user.
     *
     * @param  \App\Models\User $currentUser
     * @param  \App\Models\User $givenUser
     * @return mixed
     */
    public function update(User $currentUser, User $givenUser)
    {
        return $this->userRoleCan($currentUser, 'user-update');
    }

    /**
     * Determine whether the current user can delete the given user.
     *
     * @param  \App\Models\User $currentUser
     * @param  \App\Models\User $givenUser
     * @return mixed
     */
    public function delete(User $currentUser, User $givenUser)
    {
        return $this->userRoleCan($currentUser, 'user-delete');
    }
}
