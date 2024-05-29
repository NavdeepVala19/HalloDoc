<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EncounterFormRequest extends FormRequest
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
            'location' => 'required|min:5|max:50',
            'date_of_birth' => 'required|date|before:tomorrow|after:Jan 01 1900',
            'service_date' => 'required|date',
            'mobile' => 'required',
            'allergies' => 'required|min:5|max:200',
            'treatment_plan' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'medication_dispensed' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'procedure' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'followUp' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
        ];
    }
}
