<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'shift';

    public function shiftDetail()
    {
        return $this->hasOne(ShiftDetail::class, 'shift_id', 'id');
    }
    public function provider()
    {
        return $this->hasOne(Provider::class, 'id', 'physician_id');
    }
}
