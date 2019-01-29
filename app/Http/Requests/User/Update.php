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
        return $this->user()->can('update', $this->route('user'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $userId = $this->route('user')->getId();
        $minLength = \App\Models\User::MIN_PASSWORD_LENGTH;

        return [
            'email' => "bail|required|email|max:255|unique:App\Models\User,email,$userId",
            'password' => "bail|nullable|max:255|min:$minLength",
        ] + parent::rules();
    }
}
