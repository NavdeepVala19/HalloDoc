<?php

namespace App\Http\Controllers;

use App\Models\users; // Make sure your model name follows the PSR standards (User instead of users)
use App\Models\RequestTable;
use App\Models\request_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Import the Hash facade
use Illuminate\Support\Facades\DB;


class patientAccountController extends Controller
{

    /**
     *it will display patient register page
     */

    public function patientRegister()
    {
        return view("patientSite/patientRegister");
    }



    /**
     *@param $request the input which is enter by user

     * it stores email and password in users table
     */


    public function createAccount(Request $request)
    {
        $request->validate([
            "email" => "required|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/",
            "password" => "required|min:2|max:30|regex:/^\S(.*\S)?$/",
            "confirm_password" => "required|same:password",
        ]);

        if (isset($request->email)) {

            $user = users::where("email", $request->email)->first();

            if ($user != null) {
                if ($user->password != null && $user->email != null) {
                    return redirect()->route('loginScreen')->with('message', 'account with this email already exist');
                } else if ($user->password == null && $user->email != null) {
                    $user->password = Hash::make($request->password);
                    $user->save();
                    return redirect()->route('loginScreen')->with('success', 'login with your registered credentials');
                }
            } else if ($user == null) {
                return redirect()->back()->with('message', 'no single request was created from this email To create account first submit request');
            }
        }
    }
}
