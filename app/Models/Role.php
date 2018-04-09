<?php

namespace App\Models;

use App\Traits\Descriptable;
use App\Traits\Nameable;
use App\Traits\Timestampable;

class Role extends Model
{
    use Nameable, Descriptable, Timestampable;

    // Meta ========================================================================

    // Relationships ===============================================================

    // Gettets =====================================================================

    // Setters =====================================================================

    // Transformers ================================================================

    /**
     * Convert the role into its array representation.
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
        ] + $this->getTimestampsAsArray();
    }

    // Domain logic ================================================================
}
