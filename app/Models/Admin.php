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
    ];

    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo(users::class, 'user_id','id');
    }

    public function RoleMenu(){
        return $this->belongs(RoleMenu::class, 'role_id', 'role_id');
    }
}
