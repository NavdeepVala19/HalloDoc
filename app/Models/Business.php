<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    protected $table = 'business';

    protected $fillable = [
        'phone_number',
        'address1',
        'address2',
        'zipcode',
        'business_name',
        'city',
        'fax_number',
        'is_registered',
    ];
}
