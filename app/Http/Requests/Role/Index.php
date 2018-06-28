<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\Request;
use App\Models\Role;

class Index extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('list', Role::class);
    }
}
