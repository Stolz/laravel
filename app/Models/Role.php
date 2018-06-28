<?php

namespace App\Models;

use App\Traits\Descriptable;
use App\Traits\Nameable;
use App\Traits\Timestampable;

class Role extends Model
{
    use Nameable, Descriptable, Timestampable;

    /**
     * Fields that contain the "many" part of a OneToMany or ManyToMany relationship.
     * Constructor will automatically initialize them.
     *
     * @const array
     */
    const RELATIONSHIPS = ['permissions'];

    // Meta ========================================================================

    // Relationships ===============================================================

    /**
     * The permissions of the role.
     *
     * @var \Doctrine\Common\Collections\Collection of Permissions
     */
    protected $permissions;

    // Gettets =====================================================================

    /**
     * Get the he permissions of the role.
     *
     * @return \Doctrine\Common\Collections\Collection of Bar
     */
    public function getPermissions(): \Doctrine\Common\Collections\Collection
    {
        return $this->permissions;
    }

    // Setters =====================================================================

    // Transformers ================================================================

    /**
     * Convert the role into its array representation.
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
            'description' => $this->getDescription(),
        ] + $this->getTimestampsAsArray();
    }

    // Domain logic ================================================================

    /**
     * Determine whether the the role has full privileges.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return ($this->getName() === 'Admin' and \EntityManager::contains($this));
    }
}
