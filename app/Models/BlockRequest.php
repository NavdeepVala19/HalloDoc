<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockRequest extends Model
{
    use HasFactory;

    protected $table = 'block_request';

    protected $guarded = [];

    public function requestStatus()
    {
        return $this->belongsTo(RequestStatus::class, 'request_id', 'request_id');
    }
}
