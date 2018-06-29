<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Models\User;
use App\Repositories\Contracts\RoleRepository;

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
        $roleIds = app(RoleRepository::class)->all()->map->getId()->implode(',');

        return [
            'role' => "required|integer|in:$roleIds",
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:App\Models\User',
            'password' => 'required|min:' . \App\Models\User::MIN_PASSWORD_LENGTH,
        ];
    }
}
