<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestWiseFile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'request_wise_file';

    protected $guarded = [];

    public function requestClient()
    {
        return $this->belongsTo(RequestClient::class, 'request_id', 'request_id');
    }

    public function request()
    {
        return $this->hasOne(RequestTable::class, 'id', 'request_id');
    }
}
