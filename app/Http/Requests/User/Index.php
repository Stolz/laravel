<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Models\User;

class Index extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('list', User::class);
    }
}
