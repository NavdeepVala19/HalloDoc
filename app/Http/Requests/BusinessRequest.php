<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'business_name' => 'required|alpha|min:5|max:20',
            'profession' => 'required|numeric',
            'fax_number' => 'required|numeric|min_digits:4|max_digits:8',
            'mobile' => 'required',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'business_contact' => 'required|min_digits:10|max_digits:10',
            'street' => 'required|min:3|max:25|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'city' => 'required|min:3|max:25|regex:/^[a-zA-Z ]+?$/',
            'state' => 'required|min:3|max:25|regex:/^[a-zA-Z ]+?$/',
            'zip' => 'required|min_digits:6|max_digits:6',
        ];
    }
}
