<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminProfileForm extends FormRequest
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
            'user_name' => 'required|alpha|min:3|max:40',
            'password' => 'required|min:8|max:20|regex:/^\S(.*\S)?$/',
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'email' => 'required|email|min:2|max:40|unique:App\Models\Users,email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'confirm_email' => 'required|email|same:email',
            'phone_number' => 'required',
            'address1' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9\s,_-]+?$/',
            'address2' => 'required|min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'zip' => 'digits:6|gte:1',
            'alt_mobile' => 'required|max_digits:10|min_digits:10',
            'role' => 'required',
            'state' => 'required',
            'region_id' => 'required',
        ];
    }

    public function messages()
    {
        $enter = 'Please enter';
        $min_message = 'Please enter more than';
        $max_message = 'Please enter less than';
        $only_alphabets = 'Please enter only Alphabets';

        return [
            'first_name.required' => $enter . ' First Name',
            'first_name.min' => $min_message . ' 3 Alphabets',
            'first_name.max' => $max_message . ' 15 Alphabets',
            'first_name.alpha' => $only_alphabets . ' in First name',

            'last_name.required' => $enter . ' Last Name',
            'last_name.min' => $min_message . ' 3 Alphabets',
            'last_name.max' => $max_message . ' 15 Alphabets',
            'last_name.alpha' => $only_alphabets . ' in Last name',

            'user_name.required' => $enter . ' User Name',
            'user_name.min' => $min_message . ' 3 Alphabets',
            'user_name.max' => $max_message . ' 40 Alphabets',
            'user_name.alpha' => $only_alphabets . ' in User name',

            'password.required' => $enter . ' Password',
            'password.min' => $min_message . ' 8 characters',
            'password.max' => $max_message . ' 20 characters',
            'password.regex' => 'Please enter a valid password',

            'email.required' => $enter . ' Email',
            'email.max' => $max_message . ' 40 characters in Email',
            'email.regex' => $enter . ' a valid email (format: alphanum@alpha.domain).',

            'confirm_email.required' => $enter . ' Email',
            'confirm_email.same' => 'Confirm Email should be same as Email',

            'phone_number.required' => $enter . ' Phone Number',
            'alt_mobile.required' => $enter . ' Alternate Phone Number',
            'alt_mobile.max_digits' => 'Please enter exactly 10 digits in Alternate Phone Number',
            'alt_mobile.min_digits' => 'Please enter exactly 10 digits in Alternate Phone Number',

            'state.required' => 'Please select state',
            'role.required' => 'Please select a Role',
            'region_id.required' => 'Please select atleast one Region',

            'address1.required' => $enter . ' a address1',
            'address1.min' => $min_message . ' 2 characters in address1',
            'address1.max' => $max_message . ' 50 characters in address1',
            'address1.regex' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in address1.',

            'city.required' => $enter . ' a city',
            'city.regex' => 'Please enter alpbabets in city.',
            'city.max' => $max_message . ' 30 alphabets in city',
            'city.min' => $min_message . ' 2 alphabets in city',

            'address2.required' => $enter . ' a address2',
            'address2.regex' => 'Please enter alpbabets in address2 name.',
            'address2.max' => $max_message . ' 30 alphabets in address2',
            'address2.min' => $min_message . ' 2 alphabets in address2',

            'zip.digits' => 'Please enter 6 digits zipcode',
            'zip.gte' => 'Please enter a 6 digit positive number in zipcode.',

        ];
    }
}
