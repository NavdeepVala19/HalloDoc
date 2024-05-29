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

    protected $guarded = [];

    public function healthProfessionalType()
    {
        return $this->hasOne(HealthProfessionalType::class, 'id', 'profession');
    }
}
