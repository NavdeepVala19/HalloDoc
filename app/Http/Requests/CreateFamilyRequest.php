<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFamilyRequest extends FormRequest
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
            'family_first_name' => 'required|min:3|max:15|alpha',
            'family_last_name' => 'required|min:3|max:15|alpha',
            'family_phone_number' => 'required',
            'family_email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'family_relation' => 'required|alpha',

            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'date_of_birth' => 'required|before:tomorrow|after:Jan 01 1900',
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required|max_digits:10|min_digits:10',
            'room' => 'gte:1|nullable|max:1000',
            'street' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9\s,_-]+?$/',
            'city' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'state' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'zipcode' => 'digits:6|gte:1',
            'docs' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc,docx|max:2048',
            'symptoms' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 \-_,()]+$/',
        ];
    }

    /**
     * validation message display
     */
    public function messages()
    {
        $enter = 'Please enter';
        $min_message = 'Please enter more than';
        $max_message = 'Please enter less than';
        $only_alphabets = 'Please enter only Alphabets';

        return [
            'family_first_name.required' => $enter.' First Name',
            'family_first_name.min' => $min_message.' 3 Alphabets',
            'family_first_name.max' => $max_message.' 15 Alphabets',
            'family_first_name.alpha' => $only_alphabets . ' in First name',

            'family_last_name.required' => $enter . ' Last Name',
            'family_last_name.min' => $min_message . ' 3 Alphabets',
            'family_last_name.max' => $max_message . ' 15 Alphabets',
            'family_last_name.alpha' => $only_alphabets .' in Last name',

            'family_email.required' => $enter . ' Email',
            'family_email.max' => $max_message.' 40 characters in Email',
            'family_email.regex' => $enter . ' a valid email (format: alphanum@alpha.domain).',

            'family_phone_number.required' => $enter. ' Phone Number',
            
            'family_relation' => $enter.' a relation with patient',
            'family_relation.alpha' => ' Please enter valid relation (Format : alphabets-alphabets).',

            'first_name.required' => $enter . ' First Name',
            'first_name.min' => $min_message . ' 3 Alphabets',
            'first_name.max' => $max_message . ' 15 Alphabets',
            'first_name.alpha' => $only_alphabets . ' in First name',

            'last_name.required' => $enter . ' Last Name',
            'last_name.min' => $min_message . ' 3 Alphabets',
            'last_name.max' => $max_message . ' 15 Alphabets',
            'last_name.alpha' => $only_alphabets . ' in Last name',

            'date_of_birth.required' => $enter . ' Date of Birth',
            'date_of_birth.before' => 'Date of Birth should not be greater than today',

            'email.required' => $enter . ' Email',
            'email.max' => $max_message . ' 40 characters in Email',
            'email.regex' => $enter . ' a valid email (format: alphanum@alpha.domain).',

            'phone_number.required' => $enter . ' Phone Number',

            'room.max' => $max_message . ' a number less than 1000',
            'room.gte' => 'Please enter room number greater than 0',

            'street.required' => $enter . ' a street',
            'street.max' => $max_message . ' 50 alphabets in street',
            'street.regex' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',

            'city.required' => $enter . ' a city',
            'city.regex' => 'Please enter alpbabets in city name.',
            'city.max' => $max_message . ' 30 alphabets in city',

            'state.required' => $enter . ' a state',
            'state.regex' => 'Please enter alpbabets in state name.',
            'state.max' => $max_message . ' 30 alphabets in state',

            'zipcode.digits' => 'Please enter 6 digits zipcode',
            'zipcode.gte' => 'Please enter a 6 digit positive number in zipcode.',

            'docs.mimes' => 'Please select a valid file (JPG, PNG, PDF, DOC) with a size less than 2MB.',

            'symptoms.min' => $min_message . ' 5 character',
            'symptoms.max' => $max_message . ' 200 character',
            'symptoms.regex' => 'Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.',

        ];
    }
}
