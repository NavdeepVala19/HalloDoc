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
            'location' => 'required|min:5|max:100|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'date_of_birth' => 'required|before:today|after:Jan 01 1900',
            'service_date' => 'required|before:tomorrow|after:date_of_birth',
            'mobile' => 'required',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'temperature' => 'nullable|min:-50|max:50|numeric',
            'heart_rate' => 'nullable|min:30|max:220|numeric',
            'repository_rate' => 'nullable|min:12|max:40|numeric',
            'sis_BP' => 'nullable|min:40|max:250|numeric',
            'dia_BP' => 'nullable|min:40|max:150|numeric',
            'oxygen' => 'nullable|min:70|max:100|numeric',
            'pain' => 'nullable|min:5|max:50|regex:/^[a-zA-Z ]+?$/',
            'medical_history' => 'nullable|min:5|max:100|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'medications' => 'nullable|min:5|max:100|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'heent' => 'nullable|min:5|max:100|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'cv' => 'nullable|min:5|max:100|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'abd' => 'nullable|min:5|max:100|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'chest' => 'nullable|min:5|max:100|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'extr' => 'nullable|min:5|max:100|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'skin' => 'nullable|min:5|max:100|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'neuro' => 'nullable|min:5|max:100|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'other' => 'nullable|min:5|max:100|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'diagnosis' => 'nullable|min:5|max:100|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'allergies' => 'required|min:5|max:200',
            'present_illness_history' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'allergies' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'treatment_plan' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'medication_dispensed' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'procedure' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'followUp' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
        ];
    }
}
