<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShiftDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'shift_detail';

    protected $guarded = [];

    public function shiftDetailRegion()
    {
        return $this->hasOne(ShiftDetailRegion::class, 'id', 'region_id');
    }

    public function getShiftData()
    {
        return $this->hasOne(Shift::class, 'id', 'shift_id');
    }
}
