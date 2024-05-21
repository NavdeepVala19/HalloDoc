<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShiftDetailRegion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'shift_detail_region';

    public function region()
    {
        return $this->hasOne(Regions::class, 'id', 'region_id');
    }
}
