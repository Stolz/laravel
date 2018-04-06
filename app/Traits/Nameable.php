<?php

namespace App\Traits;

trait Nameable
{
    // Meta ========================================================================

    /**
     * The name of the model.
     *
     * @var string
     */
    protected $name;

    // Gettets =====================================================================

    /**
     * Get the name of the model.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    // Setters =====================================================================

    /**
     * Set the name of the model.
     *
     * @param  string|null $name
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    // Transformers ================================================================

    /**
     * Convert the model to string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getName();
    }
}
