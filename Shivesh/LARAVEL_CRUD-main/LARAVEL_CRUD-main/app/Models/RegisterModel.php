<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class RegisterModel extends Model
{
    use HasFactory;

   
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'gender',
        'image',
    ];

    // public static function create($attributes){
    //     $attributes::insert('insert into crud_users (firstname, lastname, email, password,gender,image) values(?,?,?,?,?,?)',
    //     [$attributes['firstname'], $attributes['lastname'], $attributes['email'], $attributes['password'], $attributes[ 'gender'], $attributes['image'], ]);
    //     }
    
    //     public static function showAll()
    //     {
    //         $users = DB::select('select * from crud_users');
    //         return $users;
    //     }
        
    //     public static function destroy($id)
    //     {
    //         DB::table('crud_users')->delete($id);
    //     }
}
