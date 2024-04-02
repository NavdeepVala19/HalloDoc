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
            "email" => "required",
            "password" => "required|min:8|max:20",
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

        return redirect()->route('patientDashboardData');

        // $data = request_Client::select(
        //     'request_status.status',
        //     'request_status.request_id',
        //     'request_client.request_id',
        //     'request_client.first_name',
        //     'request_wise_file.id',
        //     DB::raw('DATE(request_client.created_at) as created_date'),
        //     'status.status_type'
        // )
        //     ->leftJoin('request_status', 'request_status.request_id', 'request_client.request_id')
        //     ->leftJoin('status', 'status.id', 'request_status.status')
        //     ->leftJoin('request_wise_file', 'request_wise_file.request_id', 'request_client.request_id')
        //     ->where('email', $request->email)
        //     ->paginate(10);

        // return view('patientSite/patientDashboard', compact('data'));
        
    }
}
