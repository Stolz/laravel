<?php

namespace App\Http\Requests\Country;

use App\Http\Requests\Request;
use App\Models\Country;

class Index extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('list', Country::class);
    }
}
