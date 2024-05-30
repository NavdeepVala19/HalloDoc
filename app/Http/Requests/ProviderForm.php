<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderForm extends FormRequest
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
            'password' => 'required|min:8|max:50|regex:/^\S(.*\S)?$/',
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'email' => 'required|email|min:2|max:40|unique:App\Models\Users,email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required',
            'medical_license' => 'required|numeric|max_digits:10|min_digits:10',
            'npi_number' => 'required|numeric|min_digits:10|max_digits:10',
            'address1' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9\s,_-]+?$/',
            'address2' => 'required|min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'city' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'zip' => 'digits:6|gte:1',
            'phone_number_alt' => 'required',
            'select_state' => 'required',
            'region_id' => 'required',
            'role' => 'required',
            'business_name' => 'required|min:3|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'business_website' => 'required|url|max:40|min:10',
            'admin_notes' => 'required|min:5|max:200|regex: /^[a-zA-Z0-9 \-_.,\/]+$/',
            'provider_photo' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'independent_contractor' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'background_doc' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'hipaa_docs' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'non_disclosure_doc' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
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
            'password.max' => $max_message . ' 50 characters',
            'password.regex' => 'Please enter a valid password',

            'email.required' => $enter . ' Email',
            'email.max' => $max_message . ' 40 characters in Email',
            'email.regex' => $enter . ' a valid email (format: alphanum@alpha.domain).',

            'phone_number.required' => $enter . ' Phone Number',
            'phone_number_alt.required' => $enter . ' Alternate Phone Number',

            'select_state.required' => 'Please select state',
            'role.required' => 'Please select a Role',
            'region_id.required' => 'Please select atleast one Region',

            'address1.required' => $enter . ' a address1',
            'address1.min' => $min_message . ' 2 alphabets in address1',
            'address1.max' => $max_message . ' 50 alphabets in address1',
            'address1.regex' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in address1.',

            'city.required' => $enter . ' a city',
            'city.regex' => 'Please enter alphabets in city.',
            'city.max' => $max_message . ' 30 alphabets in city',
            'city.min' => $min_message . ' 2 alphabets in city',

            'address2.required' => $enter . ' a address2',
            'address2.regex' => 'Please enter alphabets in address2 name.',
            'address2.max' => $max_message . ' 30 alphabets in address2',
            'address2.min' => $min_message . ' 2 alphabets in address2',

            'zip.digits' => 'Please enter 6 digits zipcode',
            'zip.gte' => 'Please enter a 6 digit positive number in zipcode.',

            'business_name.required' => $enter . ' Business Name',
            'business_name.min' => $min_message . ' 3 Alphabets',
            'business_name.max' => $max_message . ' 30 Alphabets',
            'business_name.regex' => 'Please enter alphabets in business name.',

            'business_website.required' => $enter . ' Business Website Url',
            'business_website.min' => $min_message . ' 10 Alphabets',
            'business_website.max' => $max_message . ' 40 Alphabets',
            'business_website.url' => 'Please enter a valid business website URL starting with https://www.',

            'medical_license.required' => $enter . ' medical license',
            'medical_license.max_digits' => 'Please enter exactly 10 digits',
            'medical_license.min_digits' => 'Please enter exactly 10 digits',
            'medical_license.numeric' => 'Please enter only numbers',

            'npi_number.required' => $enter . ' NPI number',
            'npi_number.max_digits' => 'Please enter exactly 10 digits',
            'npi_number.min_digits' => 'Please enter exactly 10 digits',
            'npi_number.numeric' => 'Please enter only numbers',

            'admin_notes.required' => $enter . ' Admin Notes',
            'admin_notes.min' => $min_message . ' 5 character',
            'admin_notes.max' => $max_message . ' 200 character',
            'admin_notes.regex' => 'Please enter alphabets,numbers, hyphens, underscores,fullstop, commas, and forward slashes in admin notes.',

            'provider_photo.mimes' => 'Please select a valid file (JPG, PNG, PDF, DOC) with a size less than 2MB.',
            'independent_contractor.mimes' => 'Please select a valid file (JPG, PNG, PDF, DOC) with a size less than 2MB.',
            'background_doc.mimes' => 'Please select a valid file (JPG, PNG, PDF, DOC) with a size less than 2MB.',
            'hipaa_docs.mimes' => 'Please select a valid file (JPG, PNG, PDF, DOC) with a size less than 2MB.',
            'non_disclosure_doc.mimes' => 'Please select a valid file (JPG, PNG, PDF, DOC) with a size less than 2MB.',
        ];
    }
}
