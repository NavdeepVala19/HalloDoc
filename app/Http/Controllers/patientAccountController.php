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
    public function patientRegister()
    {
        return view("patientSite/patientRegister");
    }

    public function createAccount(Request $request)
    {
        $request->validate([
            "email" => "required|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/",
            "password" => "required|min:2|max:40|regex:/^\S(.*\S)?$/",
            "confirm_password" => "required|same:password",
        ]);

        if (isset($request->email)) {
            $user = users::where("email", $request->email)->first();
            if ($user) {
                $user->password = Hash::make($request->password);
                $user->save();
            } else {
                $create_account = new users();
                $create_account->email = $request->email;
                $create_account->password = Hash::make($request->password); // Use Hash facade to hash the password
                $create_account->save();
            }
        }
        return redirect()->route('loginScreen')->with('success','login with create account credentials');

    }
}