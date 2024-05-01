<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\users;
use App\Models\allusers;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\request_Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class patientProfileController extends Controller
{

    // * display patient profile edit page
    public function patientEdit()
    {
        $userData = Auth::user();
        $email = $userData["email"];

        $getEmailData = allusers::where('email', '=', $email)->first();
        return view("patientSite/patientProfile", compact('getEmailData'));
    }


     // * edit patient profile without disable field
    public function patientprofileEdit($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $getPatientData = allusers::where('id', '=', $id)->first();
            if (!empty($getPatientData)) {
                return view("patientSite/patientProfileEdit", compact('getPatientData'));
            }
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

// * update patient profile data
    public function patientUpdate(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:3|max:15',
            'last_name' => 'required|min:3|max:15',
            'date_of_birth' => 'required|before:today',
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required',
            'street' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9\s,_-]+?$/',
            'city' => 'required|min:2|max:30|regex:/^[a-zA-Z\s,.-]+$/',
            'state' => 'required|min:2|max:30|regex:/^[a-zA-Z\s,.-]+$/',
            'zipcode' => 'digits:6|gte:1',
        ]);


        $userData = Auth::user();

        // Update data in users table
        $updateUserData = [
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'username' => $request->input('first_name') . $request->input('last_name'),
        ];


        // update Data in allusers table 
        $updateAllUser = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('phone_number'),
            'date_of_birth' => $request->input('date_of_birth'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'street' => $request->input('street'),
            'zipcode' => $request->input('zipcode')
        ];

        $updateUser = users::where('email', $userData['email'])->update($updateUserData);

        $updateAllUserData = allusers::where('email', $userData['email'])->update($updateAllUser);

        return redirect()->route('patientDashboardData')->with('message', 'profile is updated successfully');
    }


    // * display map location of patient
    public function patientMapLocation()
    {
        $userData = Auth::user();
        $email = $userData["email"];
        $getEmailData = allusers::where('email', '=', $email)->first();
        $address = $getEmailData->street . $getEmailData->city . $getEmailData->state;

        return view('patientSite.patientMapLocation', compact('address'));
    }
}