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
        ];
    }

    /**
     * validation message display
     * @return string
     */
    public function messages(){
        $enter = 'Please enter';
        $min_message = 'Please enter more than';
        $max_message = 'Please enter less than';
        $only_alphabets = 'Please enter only Alphabets';

        return [
            'family_first_name.required' => $enter.' First Name',
            'family_first_name.min' =>  $min_message.' 3 Alphabets',
            'family_first_name.max' => $max_message.' 15 Alphabets',
            'family_first_name.alpha' => $only_alphabets . ' in First name',

            'family_last_name.required' => $enter . ' Last Name',
            'family_last_name.min' =>$min_message . ' 3 Alphabets',
            'family_last_name.max' =>$max_message . ' 15 Alphabets',
            'family_last_name.alpha' => $only_alphabets .' in Last name',

            'family_email.required' =>  $enter . ' Email',
            'family_email.max' =>  $max_message.' 40 characters in Email',
            'family_email.regex' =>  $enter . ' a valid email (format: alphanum@alpha.domain).',

            'family_phone_number.required' => $enter. ' Phone Number',
            
            'family_relation'=> $enter.' a relation with patient',
            'family_relation.alpha'=>' Please enter valid relation (Format : alphabets-alphabets).',
        ];
    }
}
