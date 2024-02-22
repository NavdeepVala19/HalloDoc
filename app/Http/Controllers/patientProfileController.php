<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\request_Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class patientProfileController extends Controller
{
    public function profile()
    {
        return view("patientSite/patientProfile");
    }

    public function patientEdit($email)
    {
       
    }

    public function patientUpdate(Request $request)
    {

    }


}
