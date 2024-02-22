<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class patientProfileController extends Controller
{
    public function profile()
    {
        return view("patientSite/patientProfile");
    }

    public function patientEdit()
    {

    }

    public function patientUpdate(Request $request)
    {
        
    }


}
