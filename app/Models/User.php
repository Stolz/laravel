<?php

namespace App\Models;

use App\Traits\Nameable;
use App\Traits\Softdeletable;
use App\Traits\Timestampable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\RoutesNotifications as Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, JWTSubject, CanResetPasswordContract, AuthorizableContract
{
    use Nameable, Timestampable, Softdeletable, Notifiable, CanResetPassword, Authorizable;

    /**
     * Fields to hide when converting the model to JSON.
     *
     * @const array
     */
    const JSON_HIDDEN = ['password', 'remember_token'];

    /**
     * Minimum password length before encryption.
     *
     * @const int
     */
    const MIN_PASSWORD_LENGTH = 8;

    /**
     * Password encryption work factor.
     *
     * @const int
     */
    const PASSWORD_BCRYPT_ROUNDS = 10;

    // Meta ========================================================================

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

    /**
     * The time zone of the user.
     *
     * @var string
     */
    protected $timezone = 'UTC';

    // Relationships ===============================================================

    /**
     * The role of the user.
     *
     * @var Role
     */
    protected $role;

    // Getters =====================================================================

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

    /**
     * Get the time zone of the user.
     *
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * Get the role of the user.
     *
     * @return Role|null
     */
    public function getRole(): ?Role
    {
        return $this->role;
    }

    // Setters =====================================================================

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
        $options = $this->getPasswordHashOptions();

        if (Hash::needsRehash($password, $options)) {
            if (strlen($password) < static::MIN_PASSWORD_LENGTH) {
                throw new \InvalidArgumentException(sprintf('Password must be at least %d characters long', static::MIN_PASSWORD_LENGTH));
            }

            $password = Hash::make($password, $options);
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

    /**
     * Set the time zone of the user.
     *
     * @param  string $timezone
     * @return self
     */
    public function setTimezone(string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Set the role of the user.
     *
     * @param  Role|array|null $role
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setRole($role): self
    {
        if (is_array($role))
            $role = Role::make($role);
        elseif ($role !== null and ! $role instanceof Role)
            throw new \InvalidArgumentException('Invalid role');

        $this->role = $role;

        return $this;
    }

    // Transformers ================================================================

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
            'timezone' => $this->getTimezone(),
            'role' => ($role = $this->getRole()) ? $role->toArray() : null,
        ] + $this->getTimestampsAsArray() + $this->getDeletedAtAsArray();
    }

    // Domain logic ================================================================

    /**
     * Determine whether the user is an admin with full privileges.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        $role = $this->getRole();

        return ($role instanceof Role and $role->isSuperAdmin());
    }

    /**
     * Get options used for hashing passwords.
     *
     * For performance reasons Laravel uses different hash options for different environments.
     * For instance, testing environment uses 4 Bcrypt rounds whereas other environments use
     * 10 rounds. If we hash user passwords using the default options they may become unusable
     * in our testing environment. To prevent this all password hashing operations of this model
     * must use the options returned by this function.
     *
     * @return array
     */
    protected function getPasswordHashOptions(): array
    {
        return ['rounds' => static::PASSWORD_BCRYPT_ROUNDS];
    }

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

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() // phpcs:ignore -- part of an interface from a vendor package
    {
        return $this->getId();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() // phpcs:ignore -- part of an interface from a vendor package
    {
        return [];
    }

    /**
     * Get the user's avatar URL.
     *
     * @param  int $size
     * @return string
     */
    public function getAvatar(int $size = 32): string
    {
        return sprintf('https://www.gravatar.com/avatar/%s?size=%d', md5($this->getEmail()), $size);
    }
}
