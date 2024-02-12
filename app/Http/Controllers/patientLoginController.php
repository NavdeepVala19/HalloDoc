<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class patientLoginController extends Controller
{
    public function loginScreen(){
        return view('patientSite/patientLogin');
    }
}
