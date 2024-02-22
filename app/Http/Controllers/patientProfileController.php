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

    public function patientEdit()
    {
        $userData = Auth::user();
        if($userData){
            $userData->email;
            $userData->password;
           $getEmailData = request_Client::where('email','=',$userData->email)->get();

           return view("patientSite/patientProfile",compact('getEmailData'));
        }
    }

    public function patientUpdate(Request $request)
    {

    }


}
