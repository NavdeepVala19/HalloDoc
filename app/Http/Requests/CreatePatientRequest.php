<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePatientRequest extends FormRequest
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
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'date_of_birth' => 'required|date|before:tomorrow|after:Jan 01 1900',
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required',
            'room' => 'gte:1|nullable|max:1000',
            'street' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9\s,_-]+?$/',
            'city' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'state' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'zipcode' => 'digits:6|gte:1',
            'docs' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc,docx|max:2048',
            'symptoms' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 \-_,()]+$/',
            'relation' => 'nullable|regex:/^[a-zA-Z]+(?:-[a-zA-Z]+)*$/',
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

            'relation.regex' => 'Please enter relation in valid format(example:alphabets-alphabets or only alphabets)',
        ];
    }
}
