<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockRequest extends Model
{
    use HasFactory;

    protected $table = 'block_request';

    protected $fillable = [
        'request_id',
        'reason',
        'email',
        'phone_number',
        'is_active'
    ];

    protected $guarded = [
        'request_id ',
    ];

    public function request_status()
    {
        return $this->belongsTo(RequestStatus::class, 'request_id', 'request_id');
    }
}
