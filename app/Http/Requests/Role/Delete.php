<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\Request as FormRequest;

class Delete extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
    {
        return $this->user()->can('delete', $this->role);
    }
}
