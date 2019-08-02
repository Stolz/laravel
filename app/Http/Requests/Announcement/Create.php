<?php

namespace App\Http\Requests\Announcement;

use App\Http\Requests\Request;
use App\Models\Announcement;

class Create extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Announcement::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'bail|required|string|max:255|unique:App\Models\Announcement',
            'description' => 'bail|nullable|string|max:2000',
            'active' => 'bail|required|boolean',
        ];
    }
}
