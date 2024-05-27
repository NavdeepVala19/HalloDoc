<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthProfessionalType extends Model
{
    use HasFactory;
    protected $table = 'health_professional_type';

    protected $guarded = [];

    public function healthProfessional()
    {
        // return $this->belongsTo(HealthProfessional::class, 'profession');
    }
}
