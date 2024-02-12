<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users extends Model
{
    use HasFactory;

    protected $table = 'users';
    
    protected $fillable = [
        'username',
        'password_hash',
        'email',
        'phone_number'        
    ];

    public function allusers(){
        return $this->hasMany(allusers::class);
    }
}
