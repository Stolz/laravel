<?php

namespace App\Http\Requests;

class ChangePassword extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // An user is always authorize to change its own password
        return true;
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
            'password' => 'required',
            'new_password' => "required|confirmed|min:$minLength",
            'new_password_confirmation' => "required|min:$minLength",
        ];
    }
}
