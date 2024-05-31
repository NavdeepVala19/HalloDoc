<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
            'phone_number' => 'required',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'street' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9\s,_-]+?$/',
            'city' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'state' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'zipcode' => 'digits:6',
            'note' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 \-_,()]+$/',
        ];
    }

    public function messages()
    {
        $enter = 'Please enter';
        $min_message = 'Please enter more than';
        $max_message = 'Please enter less than';
        $only_alphabets = 'Please enter only Alphabets';
        return [
            'first_name.required' => 'Please enter first name.',
            'last_name.required' => 'Please enter last name.',
            'phone_number.required' => 'Please enter phone number.',
            'email.required' => 'Please enter email.',
            'zipcode.required' => 'Please enter zipcode.',
            
            'street.required' => $enter . ' a street',
            'street.max' => $max_message . ' 50 alphabets in street',
            'street.regex' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',

            'city.required' => $enter . ' a city',
            'city.regex' => 'Please enter alpbabets in city name.',
            'city.max' => $max_message . ' 30 alphabets in city',

            'state.required' => $enter . ' a state',
            'state.regex' => 'Please enter alpbabets in state name.',
            'state.max' => $max_message . ' 30 alphabets in state',

            'note.min' => $min_message . ' 5 character',
            'note.max' => $max_message . ' 200 character',
            'note.regex' => 'Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.',
        ];
    }
}
