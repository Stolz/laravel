<?php

namespace App\Models;

use App\Traits\Descriptable;
use App\Traits\Nameable;
use App\Traits\Timestampable;

class Announcement extends Model
{
    use Nameable, Descriptable, Timestampable;

    // Meta ========================================================================

    /**
     * Whether the announcement is active.
     *
     * @var bool
     */
    protected $active = false;

    // Relationships ===============================================================

    // Getters =====================================================================

    /**
     * Get the active status of the announcement.
     *
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    // Setters =====================================================================

    /**
     * Set the active status of the announcement.
     *
     * @param  bool $active
     * @return self
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    // Transformers ================================================================

    /**
     * Convert the announcement into its array representation.
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
            'active' => $this->getActive(),
        ] + $this->getTimestampsAsArray();
    }

    // Domain logic ================================================================

    /**
     * Alias for `getActive()` method.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->getActive();
    }

    /**
     * Negative alias for `getActive()` method.
     *
     * @return bool
     */
    public function isNotActive(): bool
    {
        return ! $this->getActive();
    }
}
