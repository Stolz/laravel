<?php

namespace App\Models;

use App\Traits\Softdeletable;
use App\Traits\Timestampable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Model implements AuthenticatableContract/* TODO, AuthorizableContract, CanResetPasswordContract */
{
    // TODO some of these traits implement the above contracts for Eloquent models, but I'm not using Eloquent
    // review all these traits to identigy the one that are for Eloquent and reimplement them
    use Softdeletable, Timestampable;/*TODO, Authorizable, CanResetPassword, Notifiable*/

    /**
     * Minimum password length before encryption.
     *
     * @const int
     */
    const MIN_PASSWORD_LENGTH = 5;

    // Meta ========================================================================

    /**
     * The name of the user.
     *
     * @var string
     */
    protected $name;

    /**
     * The e-mail address of the user.
     *
     * @var string
     */
    protected $email;

    /**
     * The password of the user.
     *
     * @var string
     */
    protected $password;

    /**
     * The token value for the "remember me" session.
     *
     * Used to prevent session hijacking when using "remember me" feature.
     *
     * @var string
     */
    protected $rememberToken;

    // Gettets =====================================================================

    /**
     * Get the name of the user.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get the e-mail address of the user.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Get the password of the user.
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string|null
     */
    public function getRememberToken(): ?string
    {
        return $this->rememberToken;
    }

    // Setters =====================================================================

    /**
     * Set the name of the user.
     *
     * @param  string|null $name
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the e-mail address of the user.
     *
     * @param  string|null $email
     * @return self
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the password of the user.
     *
     * @param  string $password
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setPassword(string $password): self
    {
        if (Hash::needsRehash($password)) {
            if (strlen($password) < static::MIN_PASSWORD_LENGTH) {
                throw new \InvalidArgumentException('Password must be at least 5 characters long');
            }

            $password = Hash::make($password);
        }

        $this->password = $password;

        return $this;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string|null  $token
     * @return self
     */
    public function setRememberToken($token): self
    {
        $this->rememberToken = $token;

        return $this;
    }

    // Transformers ================================================================

    /**
     * Convert the user to string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getName();
    }

    /**
     * Convert the user into its array representation.
     *
     * NOTE: By convention array keys must be in snake_case, not in camelCase or StudlyCase.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'remember_token' => $this->getRememberToken(),
        ] + $this->getTimestampsAsArray() + $this->getDeletedAtAsArray();
    }

    // Domain logic ================================================================

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getId();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->getPassword();
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }
}
