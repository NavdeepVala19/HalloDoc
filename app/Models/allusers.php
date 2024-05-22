<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AllUsers extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'allusers';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile',
        'zipcode',
        'street',
        'city',
        'state',
        'status',
        'region_id',
        'user_id',
        'date_of_birth',
    ];


    public function users()
    {
        return $this->belongsTo(Users::class);
    }

    public function request()
    {
        return $this->hasMany('App\Model\request', 'user_id', 'user_id');
    }

    public function userRoles()
    {
        return $this->belongsTo(UserRoles::class);
    }

    public function provider()
    {
        return $this->belongsTo(AllUsers::class, 'user_id', 'user_id');
    }
}
