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
            'date_of_birth' => 'nullable|date|before:tomorrow|after:Jan 01 1900',
            'phone_number' => 'required',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'street' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9\s,_-]+?$/',
            'city' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'state' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'zipcode' => 'nullable|min_digits:6|max_digits:6',
            'room' => 'nullable|gte:1|max:1000',
            'symptoms' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 \-_,()]+$/',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Please enter first name.',
            'last_name.required' => 'Please enter last name.',
            'phone_number.required' => 'Please enter phone number.',
            'email.required' => 'Please enter email.',
            'street.required' => 'Please enter street.',
            'city.required' => 'Please enter city.',
            'state.required' => 'Please enter state.',
            'zipcode.required' => 'Please enter zipcode.',
        ];
    }
}
