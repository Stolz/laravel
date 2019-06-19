<?php

namespace App\Traits;

trait Descriptable
{
    // Meta ========================================================================

    /**
     * The description of the model.
     *
     * @var string|null
     */
    protected $description;

    // Getters =====================================================================

    /**
     * Get the description of the model.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    // Setters =====================================================================

    /**
     * Set the description of the model.
     *
     * @param  string|null $description
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
