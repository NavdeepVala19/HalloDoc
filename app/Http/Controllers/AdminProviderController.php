<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminProviderController extends Controller
{
    public function providersInfo(){
        return view('/adminPage/provider/provider');
    }
}
