<?php

namespace App\Http\Requests\Country;

class Update extends Create
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('country'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $countryId = $this->route('country')->getId();

        return [
            'code' => "bail|required|alpha|size:2|unique:App\Models\Country,code,$countryId",
            'name' => "bail|required|max:255|unique:App\Models\Country,name,$countryId",
        ] + parent::rules();
    }
}
