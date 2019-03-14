<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaff extends FormRequest
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
        // logger(request());
        return [
            'name' => 'string|required',
            'email' => 'email|required|unique:users',
            'password' => 'string|required',
            'divisions' => 'array|required',
            'supervisor' => 'integer|required',
            'supervises' => 'array|nullable',
            'administrator' => 'integer|nullable'
        ];
    }
}
