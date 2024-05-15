<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCreateRequest extends FormRequest
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
            'date_of_birth' => 'before:today',
            'phone_number' => 'required',
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'street' => 'required|min:2|max:50',
            'city' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'state' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'room' => 'gte:1|nullable|max_digits:4',
            'zip' => 'digits:6|nullable|gte:1',
            'adminNote' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 \-_.,()]+$/',
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
            'first_name.min' =>  $min_message . ' 3 Alphabets',
            'first_name.max' => $max_message . ' 15 Alphabets',
            'first_name.alpha' => $only_alphabets . ' in First name',

            'last_name.required' => $enter . ' Last Name',
            'last_name.min' => $min_message . ' 3 Alphabets',
            'last_name.max' => $max_message . ' 15 Alphabets',
            'last_name.alpha' => $only_alphabets . ' in Last name',

            'date_of_birth.before' =>  $enter . ' Date of Birth Before Today',

            'email.required' =>  $enter . ' Email',
            'email.max' =>  $max_message . ' 40 characters in Email',
            'email.regex' =>  $enter . ' a valid email (format: alphanum@alpha.domain).',

            'phone_number.required' => $enter . ' Phone Number',

            'street.required' => $enter . ' a street',
            'street.max' => $max_message . " 50 alphabets in street",
            'street.regex' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',

            'city.required' => $enter . ' a city',
            'city.regex' => 'Please enter alpbabets in city name.',
            'city.max' => $max_message . ' 30 alphabets in city',

            'state.required' => $enter . ' a state',
            'state.regex' => 'Please enter alpbabets in state name.',
            'state.max' => $max_message . ' 30 alphabets in state',

            'zip.digits' => 'Please enter 6 digits zipcode',
            'zip.gte' => 'Please enter a 6 digit positive number in zipcode.',

            'adminNote.min' => $min_message . ' 5 character',
            'adminNote.max' => $max_message . ' 200 character',
            'adminNote.regex' => 'Please enter valid notes. notes should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.',
            
            'room.max_digits' => 'Maximum 4 digits are allowed in room number',
            'room.gte' => 'Please enter room number greater than 0',
        ];
    }
}
