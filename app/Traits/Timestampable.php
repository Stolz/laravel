<?php

namespace App\Traits;

use Carbon\Carbon;

trait Timestampable
{
    // Meta ========================================================================

    /**
     * When the model was created.
     *
     * @var \Carbon\Carbon
     */
    protected $createdAt;

    /**
     * When the model was updated.
     *
     * @var \Carbon\Carbon
     */
    protected $updatedAt;

    // Gettets =====================================================================

    /**
     * Get the creation date of the model.
     *
     * @return \Carbon\Carbon|null
     */
    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

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
     * Get the timestamps as an array.
     *
     * @return array
     */
    protected function getTimestampsAsArray(): array
    {
        return [
            'created_at' => convert_date_to_string($this->getCreatedAt(), static::DATETIME_FORMAT),
            'updated_at' => convert_date_to_string($this->getUpdatedAt(), static::DATETIME_FORMAT),
        ];
    }
}
