<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhysicianLocation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'physician_location';

    
    protected $fillable = [
        'id',
        'provider_id',
        'latitude',
        'longitude',
        'physician_name',
        'address',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

}
