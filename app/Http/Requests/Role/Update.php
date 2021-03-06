<?php

namespace App\Http\Requests\Role;

class Update extends Create
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('role'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $roleId = $this->route('role')->getId();

        return [
            'name' => "bail|required|min:3|max:255|unique:App\Models\Role,name,$roleId",
        ] + parent::rules();
    }
}
