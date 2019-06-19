<?php

namespace App\Traits;

trait Identifiable
{
    // Meta ========================================================================

    /**
     * The unique identifier of the instance.
     *
     * @var mixed
     */
    protected $id;

    /**
     * Clones the current instance.
     *
     * @return void
     */
    public function __clone()
    {
        $this->setId(null);
    }

    // Getters =====================================================================

    /**
     * Get the id of the instance.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    // Setters =====================================================================

    /**
     * Set the id of the instance.
     *
     * @param  mixed $id
     * @return self
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }
}
