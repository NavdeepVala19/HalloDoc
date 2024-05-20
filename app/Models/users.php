<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Users extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'email',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'token',
    ];


    public function allusers()
    {
        return $this->hasMany(AllUsers::class);
    }
    public function userRoles()
    {
        return $this->hasOne(UserRoles::class, 'user_id', 'id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'user_id');
    }
}
