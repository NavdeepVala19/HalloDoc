<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class allusers extends Model
{
    use HasFactory;

    protected $table = 'allusers';

    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'phone', 'zipcode', 'street','city', 'state'];
}
