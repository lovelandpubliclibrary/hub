<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StorePatron extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // we don't need to worry about authorization since this request
        // is run through the web middleware
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
            'first_name' => 'string|nullable',
            'last_name' => 'string|nullable',
            'description' => 'string|required',
            'card_number' => 'digits:9|nullable',
            'user' => 'numeric|required',
        ];
    }
}
