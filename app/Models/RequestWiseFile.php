<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestWiseFile extends Model
{
    protected $table = "request_wise_file";

    protected $fillable = [
        'request_id ',
        'file_name',
        'physician_id',
        'admin_id',
        'doc_type',
        'is_frontSide',
        'is_compensation',
        'is_finalize',
        'is_patient_records',
    ];
}
