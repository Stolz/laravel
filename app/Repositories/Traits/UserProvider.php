<?php

namespace App\Repositories\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

/**
 * It is possible to implement \Illuminate\Contracts\Auth\UserProvider by only using
 * functions from \App\Repositories\Contracts\ModelRepository and \App\Models\User.
 * This trait implements the contract by using such functions so we don't have to repeat
 * them for every possible implementation of \App\Repositories\Contracts\UserRepository.
 */
trait UserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return $this->find($identifier);
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed   $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        if ($user = $this->retrieveById($identifier) and $user->getRememberToken() and $user->getRememberToken() === $token) {
            return $user;
        }

        return null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        $this->update($user->setRememberToken($token), [$user->getRememberTokenName()]);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        return $this->findBy('email', $credentials['email']);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $user->getEmail() === $credentials['email'] and Hash::check($credentials['password'], $user->getAuthPassword());
    }
}
