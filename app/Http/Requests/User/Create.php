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
        $minLength = \App\Models\User::MIN_PASSWORD_LENGTH;
        $timezones = implode(',', timezone_identifiers_list());

        return [
            'email' => 'bail|required|email|max:255|unique:App\Models\User',
            'name' => 'bail|required|min:3|max:255',
            'password' => "bail|required|max:255|min:$minLength",
            'timezone' => "bail|required|string|max:128|in:$timezones",
            'role' => 'bail|required|integer|exists:App\Models\Role,id',
        ];
    }
}
