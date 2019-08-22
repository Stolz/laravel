<?php

namespace App\Models;

use App\Traits\Codeable;
use App\Traits\Nameable;
use App\Traits\Softdeletable;
use App\Traits\Timestampable;

class Country extends Model
{
    use Nameable, Codeable, Timestampable, Softdeletable;

    // Meta ========================================================================

    // Relationships ===============================================================

    // Getters =====================================================================

    // Setters =====================================================================

    /**
     * Set the code of the country.
     *
     * @param  string|null $code
     * @return self
     */
    public function setCode(?string $code): self
    {
        if ($code !== null) {
            $code = strtoupper($code);
        }

        $this->code = $code;

        return $this;
    }

    // Transformers ================================================================

    /**
     * Convert the country into its array representation.
     *
     * NOTE: By convention array keys must be in snake_case, not in camelCase or StudlyCase.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'code' => $this->getCode(),
            'name' => $this->getName(),
        ] + $this->getTimestampsAsArray() + $this->getDeletedAtAsArray();
    }

    // Domain logic ================================================================
}
