<?php

namespace App\Http\Requests\Announcement;

class Update extends Create
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('announcement'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'bail|required|string|max:255|unique:App\Models\Announcement,name,' . $this->route('announcement')->getId(),
        ] + parent::rules();
    }
}
