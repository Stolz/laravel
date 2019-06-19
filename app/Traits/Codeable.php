<?php

namespace App\Traits;

trait Codeable
{
    // Meta ========================================================================

    /**
     * The code of the model.
     *
     * @var string
     */
    protected $code;

    // Getters =====================================================================

    /**
     * Get the code of the model.
     *
     * @return mixed|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    // Setters =====================================================================

    /**
     * Set the code of the model.
     *
     * @param  mixed|null $code
     * @return self
     */
    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }

    // Transformers ================================================================
}
