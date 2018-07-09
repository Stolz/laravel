<?php

namespace App\Policies;

use App\Models\Country;
use App\Models\User;

class CountryPolicy extends RoleBasedPolicy
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
        if ($preCheck !== null)
            return $preCheck;

        // If the user cannot use the 'master' module no need to further check the policy method
        if ($this->userRoleCannot($user, 'use-master-module'))
            return false;
    }

   /**
     * Determine whether the current user can list countries.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $this->userRoleCan($user, 'country-list');
    }

    /**
     * Determine whether the current user can create countries.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->userRoleCan($user, 'country-create');
    }

    /**
     * Determine whether the current user can view the given country.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Country $country
     * @return mixed
     */
    public function view(User $user, Country $country)
    {
        return $this->userRoleCan($user, 'country-view');
    }

    /**
     * Determine whether the current user can update the given country.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Country $country
     * @return mixed
     */
    public function update(User $user, Country $country)
    {
        return $this->userRoleCan($user, 'country-update');
    }

    /**
     * Determine whether the current user can delete the given country.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Country $country
     * @return mixed
     */
    public function delete(User $user, Country $country)
    {
        return $this->userRoleCan($user, 'country-delete');
    }
}
