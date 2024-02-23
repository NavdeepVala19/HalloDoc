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

    public function patientEdit(Request $request)
    {

        $userData = Auth::user();
        $email = $userData["email"];


        $getEmailData = request_client::where('email', '=', $email)->first();

        return view("patientSite/patientProfile", compact('getEmailData'));

    }



    public function patientUpdate(Request $request)
    {


        $userData = Auth::user();
        $email = $userData["email"];

        $getEmailData = request_client::where('email', '=', $email)->first();


        $updatedData = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'street' => $request->input('street'),
            'zipcode' => $request->input('zipcode')
        ];



        $updateData = request_Client::where('email', $userData['email'])->update($updatedData);


        return redirect()->route('patientProfile');


    }


}
