<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftDetail extends Model
{
    use HasFactory;
    protected  $table = 'shift_detail';

    protected $guarded = [];


    public function shiftDetailRegion()
    {
        return $this->hasOne(ShiftDetailRegion::class, 'shift_detail_id', 'region_id');
    }
}
