<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class registerModel extends Model
{
    use HasFactory;

    protected $table = 'crud_users';

    protected $fillable = ['firstname', 'lastname', 'email', 'password', 'gender', 'image'];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
