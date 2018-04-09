<?php

namespace App\Http\Requests;

class ChangePassword extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        dd($this->user());
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $minLength = \App\Models\User::MIN_PASSWORD_LENGTH;

        return [
            'password' => 'required',
            'new_password' => "required|confirmed|min:$minLength",
            'new_password_confirmation' => "required|min:$minLength",
        ];
    }
}
