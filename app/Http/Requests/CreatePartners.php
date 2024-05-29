<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePartners extends FormRequest
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
            'business_name' => 'required|min:5|max:20|alpha',
            'profession' => 'required|numeric',
            'fax_number' => 'required|numeric|min_digits:4|max_digits:8',
            'mobile' => 'required',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'business_contact' => 'required|min_digits:10|max_digits:10',
            'street' => 'required|min:3|max:25|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'city' => 'required|min:3|max:25|regex:/^[a-zA-Z ]+?$/',
            'state' => 'required|min:3|max:25|regex:/^[a-zA-Z ]+?$/',
            'zip' => 'required|gte:1|digits:6',
        ];
    }

    /**
     * display validation message
     */

    public function messages()
    {
        $enter = 'Please enter';
        $min_message = 'Please enter more than';
        $max_message = 'Please enter less than';
        $only_alphabets = 'Please enter only Alphabets';

        return [
            'business_name.required' => $enter . ' First Name',
            'business_name.min' => $min_message . ' 5 Alphabets',
            'business_name.max' => $max_message . ' 20 Alphabets',
            'business_name.alpha' => $only_alphabets . ' in First name',

            'profession.required' => $enter . ' Profession',
            'fax_number.required' => $enter . ' Fax numbers',
            'fax_number.numeric' => $enter . ' only numbers',

            'email.required' => $enter . ' Email',
            'email.regex' => $enter . ' a valid email (format: alphanum@alpha.domain).',

            'mobile.required' => $enter . ' Phone Number',

            'business_contact.required' => $enter . ' Business Contact',
            'business_contact.min_digits' => $enter . ' exactly 10 digits',
            'business_contact.max_digits' => $enter . ' exactly 10 digits',

            'street.required' => $enter . ' a street',
            'street.min' => $min_message . ' 3 characters in street',
            'street.max' => $max_message . ' 25 characters in street',
            'street.regex' => 'Only alphabets, Numbers and ,_-. allowed.',

            'city.required' => $enter . ' a city',
            'city.regex' => 'Please enter alpbabets in city name.',
            'city.min' => $min_message . ' 3 alphabets in city',
            'city.max' => $max_message . ' 25 alphabets in city',

            'state.required' => $enter . ' a state',
            'state.regex' => 'Please enter alpbabets in state name. ',
            'state.min' => $min_message . ' 3 alphabets in state',
            'state.max' => $max_message . ' 25 alphabets in state',

            'zipcode.digits' => 'Please enter 6 digits zipcode',
            'zipcode.gte' => 'Please enter a 6 digit positive number in zipcode.',
        ];
    }
}
