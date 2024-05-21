<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestNotes extends Model
{
    use HasFactory;

    protected $table = 'request_notes';

    protected $fillable = [
        'request_id',
        'patient_notes',
        'physician_notes',
        'admin_notes',
        'AdministrativeNotes',
    ];
}
