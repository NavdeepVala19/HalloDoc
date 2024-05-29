<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateConciergeRequest extends FormRequest
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
            'concierge_first_name' => 'required|min:3|max:15|alpha',
            'concierge_last_name' => 'required|min:3|max:15|alpha',
            'concierge_email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'concierge_mobile' => 'required',
            'concierge_hotel_name' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9 &\-_.,]+$/',
            'concierge_street' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9\s,_-]+?$/',
            'concierge_state' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'concierge_city' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'concierge_zip_code' => 'digits:6|gte:1',

            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'date_of_birth' => 'required|before:today',
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required|min_digits:10|max_digits:10',
            'room' => 'gte:1|nullable|max:1000',
            'symptoms' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 \-_,()]+$/',
        ];
    }

    public function messages()
    {
        $enter = 'Please enter';
        $min_message = 'Please enter more than';
        $max_message = 'Please enter less than';
        $only_alphabets = 'Please enter only Alphabets';

        return [
            'concierge_first_name.required' => $enter . ' First Name',
            'concierge_first_name.min' => $min_message . ' 3 Alphabets',
            'concierge_first_name.max' => $max_message . ' 15 Alphabets',
            'concierge_first_name.alpha' => $only_alphabets . ' in First name',

            'concierge_last_name.required' => $enter . ' Last Name',
            'concierge_last_name.min' => $min_message . ' 3 Alphabets',
            'concierge_last_name.max' => $max_message . ' 15 Alphabets',
            'concierge_last_name.alpha' => $only_alphabets . ' in Last name',

            'concierge_email.required' => $enter . ' Email',
            'concierge_email.max' => $max_message . ' 40 characters Email',
            'concierge_email.regex' => $enter . ' a valid email (format: alphanum@alpha.domain).',

            'concierge_mobile.required' => $enter . ' Phone Number',

            'concierge_street.required' => $enter . ' a street',
            'concierge_street.max' => $max_message . ' 50 alphabets in street',
            'concierge_street.regex' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',

            'concierge_city.required' => $enter . ' a city',
            'concierge_city.regex' => 'Please enter alpbabets in city name.',
            'concierge_city.max' => $max_message . ' 30 alphabets in city',

            'concierge_state.required' => $enter . ' a state',
            'concierge_state.regex' => 'Please enter alpbabets in state name.',
            'concierge_state.max' => $max_message . ' 30 alphabets in state',

            'concierge_zip_code.digits' => 'Please enter 6 digits zipcode',
            'concierge_zip_code.gte' => 'Please enter a 6 digit positive number in zipcode.',
            'concierge_hotel_name.required' => $enter . ' a hotel name',
            'concierge_hotel_name.min' => $min_message . ' 2 character',
            'concierge_hotel_name.max' => $max_message . ' 50 character',
            'concierge_hotel_name.regex' => 'Please enter alphabets,number,dash,underscore,ampersand,fullstop,comma in hotel/property name.',
            'first_name.required' => $enter . ' First Name',
            'first_name.min' => $min_message . ' 3 Alphabets',
            'first_name.max' => $max_message . ' 15 Alphabets',
            'first_name.alpha' => $only_alphabets . ' in First name',

            'last_name.required' => $enter . ' Last Name',
            'last_name.min' => $min_message . ' 3 Alphabets',
            'last_name.max' => $max_message . ' 15 Alphabets',
            'last_name.alpha' => $only_alphabets . ' in Last name',

            'date_of_birth.required' => $enter . ' Date of Birth',
            'date_of_birth.before' => $enter . ' Date of Birth Before Today',

            'email.required' => $enter . ' Email',
            'email.max' => $max_message . ' 40 characters in Email',
            'email.regex' => $enter . ' a valid email (format: alphanum@alpha.domain).',

            'phone_number.required' => $enter . ' Phone Number',
            'room.max' => $max_message . ' a number less than 1000',
            'room.gte' => 'Please enter room number greater than 0',

            'symptoms.min' => $min_message . ' 5 character',
            'symptoms.max' => $max_message . ' 200 character',
            'symptoms.regex' => 'Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.',
        ];
    }
}
