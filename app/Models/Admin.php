<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile',
        'address1',
        'address2',
        'zip',
        'alt_phone',
        'status',
        'alt_phone',
    ];


    public function users()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function roleMenu(){
        return $this->belongs(RoleMenu::class, 'role_id', 'role_id');
    }

    public function role(){
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function region(){
        return $this->belongsTo(Regions::class, 'regions_id', 'id');
    }
}
