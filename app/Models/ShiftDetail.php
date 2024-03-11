<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftDetail extends Model
{
    use HasFactory;
    protected  $table = 'shift_detail';


    public function shiftDetailRegion()
    {
        $this->hasOne(ShiftDetailRegion::class, 'shift_detail_id', 'id');
    }
}