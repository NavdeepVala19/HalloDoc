<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRegion extends Model
{
    use HasFactory;
    protected $table = 'admin_region';

    protected $fillable = [
        'admin_id',
        'region_id',
    ];

    protected $guarded = [
        'admin_id',
        'region_id',
    ];
}
