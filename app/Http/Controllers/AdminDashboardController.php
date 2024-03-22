<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\users;
use App\Models\allusers;
use App\Models\Provider;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\RequestStatus;
use App\Models\request_Client;
use App\Models\RequestWiseFile;
use App\Services\TwilioService;
use App\Exports\PendingStatusExport;
use Maatwebsite\Excel\Facades\Excel;
use Twilio\Rest\Client;

class AdminDashboardController extends Controller
{
    public function createNewRequest()
    {
        return view('adminPage/adminRequest');
    }

    public function createAdminPatientRequest(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email',
            'street' => 'required',
            'city' => 'required',
            'state' => 'required'
        ]);

        // store email and phoneNumber in users table
        $requestEmail = new users();
        $requestEmail->email = $request->email;
        $requestEmail->phone_number = $request->phone_number;
        $requestEmail->save();

        $requestData = new RequestTable();
        $requestData->status = 1;
        $requestData->user_id = $requestEmail->id;
        $requestData->request_type_id = $request->request_type;
        $requestData->first_name = $request->first_name;
        $requestData->last_name = $request->last_name;
        $requestData->email = $request->email;
        $requestData->phone_number = $request->phone_number;
        $requestData->save();

        $adminPatientRequest = new request_Client();
        $adminPatientRequest->request_id = $requestData->id;
        $adminPatientRequest->first_name = $request->first_name;
        $adminPatientRequest->last_name = $request->last_name;
        $adminPatientRequest->date_of_birth = $request->date_of_birth;
        $adminPatientRequest->email = $request->email;
        $adminPatientRequest->phone_number = $request->phone_number;
        $adminPatientRequest->street = $request->street;
        $adminPatientRequest->city = $request->city;
        $adminPatientRequest->state = $request->state;
        $adminPatientRequest->zipcode = $request->zipcode;
        $adminPatientRequest->room = $request->room;
        $adminPatientRequest->save();

        // store notes in request_notes table

        $request_notes = new RequestNotes();
        $request_notes->request_id = $requestData->id;
        $request_notes->admin_notes = $request->adminNote;
        $request_notes->created_by = 'admin';
        $request_notes->save();

        // store all details of patient in allUsers table
        $requestUsers = new allusers();
        $requestUsers->first_name = $request->first_name;
        $requestUsers->last_name = $request->last_name;
        $requestUsers->email = $request->email;
        $requestUsers->mobile = $request->phone_number;
        $requestUsers->street = $request->street;
        $requestUsers->city = $request->city;
        $requestUsers->state = $request->state;
        $requestUsers->zipcode = $request->zipcode;
        $requestUsers->save();

        return redirect()->route('admin.dashboard');
    }

    public function adminProfile($id)
    {
        $adminProfileData = Admin::with('users')->where('user_id', $id)->first();
        return view('adminPage/adminProfile', compact('adminProfileData'));
    }

    public function adminProfileEdit(Request $request, $id)
    {
        $request->validate([
            'user_name' => 'required',
            'first_name' => 'required',
            'email' => 'required|email',
        ]);
        $updateAdminInformation = Admin::with('users')->where('user_id', $id)->first();

        $updateAdminInformation->first_name = $request->first_name;
        $updateAdminInformation->last_name = $request->last_name;
        $updateAdminInformation->email = $request->email;
        $updateAdminInformation->mobile = $request->phone_number;
        $updateAdminInformation->address1 = $request->address1;
        $updateAdminInformation->address2 = $request->address2;
        $updateAdminInformation->city = $request->city;
        $updateAdminInformation->zip = $request->zip;
        $updateAdminInformation->alt_phone = $request->alt_mobile;
        $updateAdminInformation->save();

        $updateAdminInfoInUsers = users::where('id', $id)->first();
        $updateAdminInfoInUsers->username = $request->user_name;
        $updateAdminInfoInUsers->password = $request->password;
        $updateAdminInfoInUsers->save();

        $updateAdminInfoAllUsers = allusers::where('user_id', $id)->first();

        $updateAdminInfoAllUsers->first_name = $request->first_name;
        $updateAdminInfoAllUsers->last_name = $request->last_name;
        $updateAdminInfoAllUsers->email = $request->email;
        $updateAdminInfoAllUsers->mobile = $request->phone_number;
        $updateAdminInfoAllUsers->street = $request->address1;
        $updateAdminInfoAllUsers->city = $request->city;
        $updateAdminInfoAllUsers->zipcode = $request->zip;

        $updateAdminInfoAllUsers->save();


        return redirect()->route('admin.user.access');
    }


    public function sendSMS(Request $request)
    {

        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $senderNumber = getenv("TWILIO_PHONE_NUMBER");

        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create(
            "+91 99780 71802", // to
                [
                    "body" => "har har mahadev",
                    "from" =>  $senderNumber
                ]
            );

        dd('success message');
    }
}
