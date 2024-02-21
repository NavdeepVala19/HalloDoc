<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class patientProfileController extends Controller
{
    public function profile(){
        return view("patientSite/patientProfile");
    }

}
