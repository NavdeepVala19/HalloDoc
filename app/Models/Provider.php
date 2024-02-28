<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\users;

class Provider extends Model
{
    use HasFactory;
    protected $table = 'provider';


    public function users()
    {
        return $this->belongsTo(users::class, 'user_id');
    }
}
