<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestBusiness extends Model
{
    use HasFactory;
    protected $table = 'request_business';

    protected $fillable = [
        'request_id',
        'business_id',
    ];

}
