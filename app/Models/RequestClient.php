<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestClient extends Model
{
    use HasFactory;
    use SoftDeletes;

    // request_client table is use to create only the patient records
    protected $table = 'request_client';

    protected $fillable = [
        'request_id',
        'notes',
        'first_name',
        'last_name',
        'phone_number',
        'address',
        'email',
        'street',
        'city',
        'state',
        'zipcode',
        'date_of_birth',
        'room',
    ];

    public function request()
    {
        return $this->belongsTo(RequestTable::class);
    }

    public function requestStatus()
    {
        return $this->belongs(RequestStatus::class, 'request_id', 'request_id');
    }

    public function requestWiseFile()
    {
        return $this->belongsTo(RequestWiseFile::class, 'request_id', 'request_id');
    }

    public function requestClosed()
    {
        return $this->belongsTo(RequestClosed::class, 'request_id', 'request_id');
    }
}
