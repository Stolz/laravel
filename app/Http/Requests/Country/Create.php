<?php

namespace App\Http\Requests\Country;

use App\Http\Requests\Request;
use App\Models\Country;

class Create extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Country::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'code' => 'bail|required|alpha|size:2|unique:App\Models\Country,code',
            'name' => 'bail|required|max:255|unique:App\Models\Country,name',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('code'))
            $this->merge(['code' => strtoupper($this->input('code'))]);
    }
}
