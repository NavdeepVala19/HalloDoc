<?php

namespace App\Http\Controllers;

use App\Models\users; // Make sure your model name follows the PSR standards (User instead of users)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Import the Hash facade


class patientAccountController extends Controller
{
    public function patientRegister(){
        return view("patientSite/patientRegister");
    }

    public function createAccount(Request $request){

        $request ->validate([
            "email"=> "required",
            "password" => "required",
            "confirm_password" => "required",
        ]);

        $create_account = new users();
        $create_account->email = $request->email;
        $create_account->password_hash = Hash::make($request->password); // Use Hash facade to hash the password

        $create_account->save();

        return view ("patientSite/patientDashboard");
    }
}


