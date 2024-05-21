<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concierge extends Model
{
    protected $table = 'concierge';

    protected $fillable = [
        'name',
        'address',
        'street',
        'city',
        'state',
        'zipcode',
        'role_id',
    ];
    protected $guarded = [
        'region_id',
        'role_id',
    ];
}
