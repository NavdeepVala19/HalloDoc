<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthProfessional extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'health_professional';

    protected $fillable = [
        'vendor_name',
        'fax_number',
        'address',
        'city',
        'state',
        'zip',
        'phone_number',
        'email',
        'business_contact',
        'profession',
    ];


    public function healthProfessionalType()
    {
        return $this->hasOne(HealthProfessionalType::class, 'id', 'profession');
    }
}
