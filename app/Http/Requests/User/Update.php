<?php

namespace App\Http\Requests\User;

class Update extends Create
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $minLength = \App\Models\User::MIN_PASSWORD_LENGTH;

        return [
            'email' => 'required|email|max:255|unique:App\Models\User,email,' . $this->user->getId(),
            'password' => 'nullable|min:' . \App\Models\User::MIN_PASSWORD_LENGTH,
        ] + parent::rules();
    }
}
