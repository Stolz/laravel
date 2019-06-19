<?php

namespace App\Models;

use App\Traits\Descriptable;
use App\Traits\Nameable;

class Permission extends Model
{
    use Nameable, Descriptable;

    // Meta ========================================================================

    // Relationships ===============================================================

    // Getters =====================================================================

    // Setters =====================================================================

    // Transformers ================================================================

    /**
     * Convert the permission into its array representation.
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
        ];
    }

    // Domain logic ================================================================
}
