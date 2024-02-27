<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminProviderController extends Controller
{
    public function providersInfo(){
        return view('/adminPage/provider/adminProvider');
    }

    public function editProvider(){
        return view('/adminPage/provider/adminEditProvider');
    }

    public function newProvider()
    {
        return view('/adminPage/provider/adminNewProvider');
    }
}
