<?php

namespace App\Models;

use App\Models\Users;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'provider';

    protected $fillable = [
        'is_notifications',
    ];


    public function users()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function Regions()
    {
        return $this->hasOne(Regions::class, 'id', 'regions_id');
    }
}
