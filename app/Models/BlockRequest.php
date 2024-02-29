<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockRequest extends Model
{
    use HasFactory;

    protected $table = 'block_request';

    protected $fillable = [
        'request_id',
        'reason',
        'email',
        'phone_number'
    ];
}
