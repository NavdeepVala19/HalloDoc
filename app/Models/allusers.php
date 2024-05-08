<?php

namespace App\Models;

use App\Models\UserRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class allusers extends Model
{
    use HasFactory;
    use SoftDeletes;
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
        'user_id',
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

    public function user_roles()
    {
        return $this->belongsTo(UserRoles::class);
    }


    public function provider()
    {
        return $this->belongsTo(allusers::class, 'user_id', 'user_id');
    }
}
