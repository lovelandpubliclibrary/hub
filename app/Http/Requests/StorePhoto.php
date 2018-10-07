<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhoto extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        logger(request());
        return [
            'photo' => 'file|required|mimes:jpeg,png,gif|max:10240',
            'caption' => 'string|nullable',
            'associatedPatrons' => 'array|nullable',
            'associatedIncident' => 'numeric|nullable'
        ];
    }


    public function messages()
    {
        return [
            'photo.mimes' => 'The photo must be a .jpeg, .jpg, .png or .gif',
        ];
    }
}
