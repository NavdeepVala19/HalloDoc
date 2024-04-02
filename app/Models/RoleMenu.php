<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    use HasFactory;

    protected $table = 'role_menu';
    protected $guarded = [];


    public function Menu(){
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
    
}
