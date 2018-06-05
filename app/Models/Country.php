<?php

namespace App\Models;

use App\Traits\Nameable;

class Country extends Model
{
    use Nameable;

    // Meta ========================================================================

    // Relationships ===============================================================

    // Gettets =====================================================================

    // Setters =====================================================================

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
            'name' => $this->getName(),
        ];
    }

    // Domain logic ================================================================
}
