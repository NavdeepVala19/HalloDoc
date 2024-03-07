<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserRoles;

class allusers extends Model
{
    use HasFactory;

    protected $table = 'allusers';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'zipcode',
        'street',
        'city',
        'state',
        'status',
        'is_request_with_email',
        'str_month',
        'int_year',
        'int_date',
        'region_id',
        'user_id'
    ];


    public function users()
    {
        return $this->belongsTo(users::class);
    }

    public function request()
    {
        // return $this->hasMany(request::class);
        return $this->hasMany('App\Model\request', 'user_id', 'user_id');
    }

    public function user_roles(){
        return $this->belongsTo(UserRoles::class);
    }
}
