<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    abstract public function authorize(): bool;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Get all of the input except for a specified array of items.
     *
     * Same as parent except() method but automatically excludes commonly non fillable fields.
     *
     * @param  array|mixed  $keys
     * @return array
     */
    public function exceptNonFillable($keys = [])
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        // Automatically generated fields are never fillable via request and it doesn't make sense
        // to include them in the request unless the sender is up to something shady. Therefore
        // we remove them from the request to prevent the system from using them
        $nonFillableFields = ['id', 'created_at', 'updated_at', 'deleted_at'];

        foreach ($nonFillableFields as $field) {
            $keys[] = $field;
            $keys[] = camel_case($field);
            $keys[] = studly_case($field);
        }

        return parent::except($keys);
    }
}
