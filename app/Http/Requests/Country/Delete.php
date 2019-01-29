<?php

namespace App\Http\Requests\Country;

use App\Http\Requests\Request;

class Delete extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('delete', $this->route('country'));
    }
}
