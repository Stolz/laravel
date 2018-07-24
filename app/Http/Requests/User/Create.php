<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Models\User;

class Create extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255|unique:App\Models\User',
            'name' => 'required|min:3|max:255',
            'password' => 'required|min:' . \App\Models\User::MIN_PASSWORD_LENGTH,
            'role' => 'required|integer|exists:App\Models\Role,id',
        ];
    }
}
