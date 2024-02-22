<?php

namespace App\Http\Controllers;

use App\Models\users; // Make sure your model name follows the PSR standards (User instead of users)
use App\Models\RequestTable;
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
            "email" => "required",
            "password" => "required",
            "confirm_password" => "required",
        ]);


        if (isset($request->email)) {
            $user = users::where("email", $request->email)->first();
            if ($user) {
                // $user->password_hash = Hash::make($request->password);
                $user->password_hash = $request->password;
                $user->save();
            } else {
                $create_account = new users();
                $create_account->email = $request->email;
                $create_account->password_hash = $request->password; // Use Hash facade to hash the password

                $create_account->save();
            }
        }


        $timestamp = RequestTable::select('created_at')->get();

        $data = DB::table('request')
            ->join('status', 'request.status', '=', 'status.id')
            ->select('request.created_at', 'status.status_type')
            ->get();

        return view('patientSite/patientDashboard', compact('data'));

    }


}


