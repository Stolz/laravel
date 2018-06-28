<?php

namespace App\Http\Requests\Country;

use App\Http\Requests\Request as FormRequest;

class View extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('view', $this->country);
    }
}
