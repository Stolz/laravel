<?php

namespace App\Traits;

use Carbon\Carbon;

trait UpdateTimestampable
{
    // Meta ========================================================================

    /**
     * When the model was updated.
     *
     * @var \Carbon\Carbon
     */
    protected $updatedAt;

    // Getters =====================================================================

    /**
     * Get the date of the last update of the model.
     *
     * @return \Carbon\Carbon|null
     */
    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }

    // Setters =====================================================================

    /**
     * Set the date of the last modification model.
     *
     * @param  mixed $date
     * @return self
     */
    public function setUpdatedAt($date): self
    {
        $this->updatedAt = convert_to_date($date, static::DATETIME_FORMAT);

        return $this;
    }

    // Domain logic ================================================================

    /**
     * Get the update timestamp as an array.
     *
     * @return array
     */
    protected function getUpdateTimestampAsArray(): array
    {
        return [
            'updated_at' => convert_date_to_string($this->getUpdatedAt(), static::DATETIME_FORMAT),
        ];
    }
}
