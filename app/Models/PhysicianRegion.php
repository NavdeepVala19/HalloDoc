<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicianRegion extends Model
{
    use HasFactory;

    protected $table = 'physician_region';

    protected $guarded = [];

    public function regions()
    {
        return $this->hasOne(Regions::class, 'id', 'region_id');
    }
}
