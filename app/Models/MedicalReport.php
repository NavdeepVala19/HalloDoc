<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalReport extends Model
{
    use HasFactory;
    protected $table = 'medical_report';

    protected $fillable = [
        'request_id',
        'first_name',
        'last_name',
        'email',
        'location',
        'service_date',
        'date_of_birth',
        'mobile',
        'present_illness_history',
        'medical_history',
        'medications',
        'allergies',
        'temperature',
        'heart_rate',
        'repository_rate',
        'sis_BP',
        'dia_BP',
        'oxygen',
        'pain',
        'heent',
        'cv',
        'chest',
        'abd',
        'extr',
        'skin',
        'neuro',
        'other',
        'diagnosis',
        'treatment_plan',
        'medication_dispensed',
        'procedure',
        'followUp',
        'is_finalize',
    ];

}
