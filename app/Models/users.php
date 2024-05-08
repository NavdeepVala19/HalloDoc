<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class users extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    use SoftDeletes;

    protected $fillable = [
        'username',
        'password',
        'email',
        'phone_number',
    ];

    public function allusers()
    {
        return $this->hasMany(allusers::class);
    }
    public function userRoles()
    {
        return $this->hasOne(UserRoles::class, 'user_id', 'id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'user_id');
    }



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_hash' => 'hashed',
    ];
}
