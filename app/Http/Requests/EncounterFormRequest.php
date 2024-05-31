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
            'location' => 'required|min:5|max:50|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'date_of_birth' => 'required|date|before:tomorrow|after:Jan 01 1900',
            'service_date' => 'required|date|before:tomorrow',
            'mobile' => 'required',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'allergies' => 'required|min:5|max:200',
            'treatment_plan' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'medication_dispensed' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'procedure' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'followUp' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'present_illness_history' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'medical_history' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'medications' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'temperature' => 'nullable|numeric|min:-50|max:50',
            'heart_rate' => 'nullable|numeric|min:30|max:220',
            'repository_rate' => 'nullable|numeric|min:12|max:40',
            'sis_BP' => 'nullable|numeric|min:40|max:250',
            'dia_BP' => 'nullable|numeric|min:40|max:250',
            'oxygen' => 'nullable|numeric|min:70|max:100',
            'pain' => 'nullable|min:5|max:50|regex:/^[a-zA-Z ]+?$/',
            'heent' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'cv' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'chest' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'abd' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'extr' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'skin' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'neuro' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'other' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
            'diagnosis' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/',
        ];
    }
}
