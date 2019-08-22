<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class RoleBasedPolicy
{
    use HandlesAuthorization;

    /**
     * Instance of the service used to interact with roles.
     *
     * @var \App\Repositories\Contracts\RoleRepository
     */
    protected $roleRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\Contracts\RoleRepository $roleRepository
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

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
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the role of the given user has the given permission name.
     *
     * @param  \App\Models\User $user
     * @param  string $permission Permission name
     * @return bool
     */
    protected function userRoleCan(User $user, string $permission): bool
    {
        // We use hasAnyPermission() instead of hasPermission() because it has better performance
        return $this->roleRepository->hasAnyPermission($user->getRole(), $permission);
    }

    /**
     * Determine whether the role of the given user does NOT have the given permission name.
     *
     * @param  \App\Models\User $user
     * @param  string $permission Permission name
     * @return bool
     */
    protected function userRoleCannot(User $user, string $permission): bool
    {
        return ! $this->userRoleCan($user, $permission);
    }
}
