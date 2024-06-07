<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestTable extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'request';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function allusers()
    {
        return $this->belongsTo(AllUsers::class);
    }

    // Making relationship with requestClient table
    public function requestClient()
    {
        return $this->hasOne(RequestClient::class, 'request_id');
    }

    public function requestStatus()
    {
        return $this->belongsTo(RequestStatus::class);
    }

    public function statusConclude()
    {
        return $this->hasOne(RequestStatus::class, 'request_id')->where('status', 6)->orderBy('id', 'desc');
    }

    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'status');
    }

    public function statusTable()
    {
        return $this->hasOne(Status::class, 'id', 'status');
    }

    public function requestType()
    {
        return $this->hasOne(requestType::class, 'id', 'request_type_id');
    }

    public function provider()
    {
        return $this->hasOne(Provider::class, 'id', 'physician_id');
    }

    public function requestWiseFile()
    {
        return $this->hasOne(RequestWiseFile::class, 'request_id');
    }

    public function medicalReport()
    {
        return $this->hasOne(MedicalReport::class, 'request_id');
    }

    public function requestNotes()
    {
        return $this->hasOne(RequestNotes::class, 'request_id');
    }

    public function usersData()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }

    public function providerUsersData()
    {
        return $this->hasManyThrough(Users::class, Provider::class,);
    }
}
