<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBusinessRequest extends FormRequest
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
            'business_first_name' => 'required|min:3|max:15|alpha',
            'business_last_name' => 'required|min:3|max:15|alpha',
            'business_email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'business_mobile' => 'required',
            'business_property_name' => 'required|min:2|max:30|regex:/^[a-zA-Z0-9 &\-_.,]+$/',
        ];
    }

    public function messages()
    {
        $enter = 'Please enter';
        $min_message = 'Please enter more than';
        $max_message = 'Please enter less than';
        $only_alphabets = 'Please enter only Alphabets';

        return [
            'business_first_name.required' => $enter . ' First Name',
            'business_first_name.min' => $min_message . ' 3 Alphabets',
            'business_first_name.max' => $max_message . ' 15 Alphabets',
            'business_first_name.alpha' => $only_alphabets . ' in First name',

            'business_last_name.required' => $enter . ' Last Name',
            'business_last_name.min' => $min_message . ' 3 Alphabets',
            'business_last_name.max' => $max_message . ' 15 Alphabets',
            'business_last_name.alpha' => $only_alphabets . ' in Last name',

            'business_email.required' => $enter . ' Email',
            'business_email.max' => $max_message . ' 40 characters in Email',
            'business_email.regex' => $enter . ' a valid email (format: alphanum@alpha.domain).',

            'business_mobile.required' => $enter . ' Phone Number',

            'business_property_name.required' => $enter . ' a business/property name',
            'business_property_name.min' => $min_message . ' 2 character',
            'business_property_name.max' => $max_message . ' 30 character',
            'business_property_name.regex' => 'Please enter a only alphabets,numbers,dash,underscore,fullstop,ampersand in business/property name.',
        ];
    }
}
