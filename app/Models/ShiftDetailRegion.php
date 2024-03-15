<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftDetailRegion extends Model
{
    use HasFactory;

    protected $table = 'shift_detail_region';

    protected $guarded = [];

    public function region()
    {
        return $this->hasOne(Regions::class, 'id', 'region_id');
    }

}
