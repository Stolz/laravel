<?php

namespace App\Traits;

use Carbon\Carbon;

trait Softdeletable
{
    // Meta ========================================================================

    /**
     * When the model was deleted.
     *
     * @var \Carbon\Carbon
     */
    protected $deletedAt;

    // Getters =====================================================================

    /**
     * Get the deletion date of the model.
     *
     * @return \Carbon\Carbon|null
     */
    public function getDeletedAt(): ?Carbon
    {
        return $this->deletedAt;
    }

    // Setters =====================================================================

    /**
     * Set the deletion date of the model.
     *
     * @param  mixed $date
     * @return self
     */
    public function setDeletedAt($date): self
    {
        $this->deletedAt = convert_to_date($date, static::DATETIME_FORMAT);

        return $this;
    }

    // Domain logic ================================================================

    /**
     * Get the deletion date as an array.
     *
     * @return array
     */
    protected function getDeletedAtAsArray(): array
    {
        return [
            'deleted_at' => convert_date_to_string($this->getDeletedAt(), static::DATETIME_FORMAT),
        ];
    }

    /**
     * Determine whether the model has been soft-deleted.
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return isset($this->deletedAt);
    }
}
