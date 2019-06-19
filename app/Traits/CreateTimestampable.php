<?php

namespace App\Traits;

use Carbon\Carbon;

trait CreateTimestampable
{
    // Meta ========================================================================

    /**
     * When the model was created.
     *
     * @var \Carbon\Carbon
     */
    protected $createdAt;

    // Getters =====================================================================

    /**
     * Get the creation date of the model.
     *
     * @return \Carbon\Carbon|null
     */
    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    // Setters =====================================================================

    /**
     * Set the creation date of the model.
     *
     * @param  mixed $date
     * @return self
     */
    public function setCreatedAt($date): self
    {
        $this->createdAt = convert_to_date($date, static::DATETIME_FORMAT);

        return $this;
    }

    // Domain logic ================================================================

    /**
     * Get the create timestamp as an array.
     *
     * @return array
     */
    protected function getCreateTimestampAsArray(): array
    {
        return [
            'created_at' => convert_date_to_string($this->getCreatedAt(), static::DATETIME_FORMAT),
        ];
    }
}
