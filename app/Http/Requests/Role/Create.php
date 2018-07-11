<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\Request;
use App\Models\Role;

class Create extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Role::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'description' => 'max:255',
            'name' => 'required|min:3|max:255|unique:App\Models\Role',
            'permissions' => 'required|array|min:1',
        ];
    }
}
