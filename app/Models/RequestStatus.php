<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestStatus extends Model
{
    use HasFactory;

    protected $table = 'request_status';

    protected $fillable = [
        'request_id',
        'status',
        'physician_id',
        'admin_id',
        'TransToPhysicianId',
        'TransToAdmin',
        'notes',
    ];

    
    public function request()
    {
        return $this->hasOne(RequestTable::class, 'id', 'request_id');
    }
    public function statusTable()
    {
        return $this->hasOne(Status::class, 'id', 'status');
    }
    public function provider()
    {
        return $this->hasOne(Provider::class, 'id', 'physician_id');
    }
    public function transferedPhysician(){
        return $this->hasOne(Provider::class, 'id', 'TransToPhysicianId');
    }
    public function requestClient()
    {
        return $this->hasOne(RequestClient::class, 'request_id', 'request_id');
    }
}
