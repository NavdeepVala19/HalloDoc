<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $table = 'request';

    protected $fillable = [
        'first_name', 'last_name', 'date_of_birth', 'phone_number', 'email', 'symptoms'
    ];
}
