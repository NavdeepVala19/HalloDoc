<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function adminLogin(){
        return view("admin/adminLogin");
    }
}
