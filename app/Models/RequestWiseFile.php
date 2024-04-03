<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestWiseFile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "request_wise_file";

    protected $fillable = [
        'request_id',
        'file_name',
        'physician_id',
        'admin_id',
        'doc_type',
        'is_frontSide',
        'is_compensation',
        'is_finalize',
        'is_patient_records',
    ];

    public function RequestClient(){
        return $this->belongsTo(request_Client::class,'request_id','request_id');
    }

    public function Request(){
        return $this->belongsTo(RequestTable::class,'id','request_id');
    }
}
