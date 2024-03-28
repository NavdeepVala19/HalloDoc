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
use App\Mail\sendEmailAddress;
use App\Models\EmailLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
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
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'string|min:2|max:30',
            'phone_number' => 'required',
            'email' => 'required|email',
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            // 'zipcode' => 'digits:6',
        ]);

        $isEmailStored = users::where('email', $request->email)->pluck('email');

        if ($request->email != $isEmailStored) {

        // store email and phoneNumber in users table
        $requestEmail = new users();
        $requestEmail->username = $request->first_name . " " . $request->last_name;
        $requestEmail->email = $request->email;
        $requestEmail->phone_number = $request->phone_number;
        $requestEmail->save();

        // store all details of patient in allUsers table
        $requestUsers = new allusers();
        $requestUsers->user_id = $requestEmail->id;
        $requestUsers->first_name = $request->first_name;
        $requestUsers->last_name = $request->last_name;
        $requestUsers->email = $request->email;
        $requestUsers->mobile = $request->phone_number;
        $requestUsers->street = $request->street;
        $requestUsers->city = $request->city;
        $requestUsers->state = $request->state;
        $requestUsers->zipcode = $request->zipcode;
        $requestUsers->save();
        }

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


        $currentTime = Carbon::now();
        $currentDate = $currentTime->format('Y');

        $todayDate = $currentTime->format('Y-m-d');
        $entriesCount = RequestTable::whereDate('created_at', $todayDate)->count();

        $uppercaseStateAbbr = strtoupper(substr($request->state, 0, 2));
        $uppercaseLastName = strtoupper(substr($request->last_name, 0, 2));
        $uppercaseFirstName = strtoupper(substr($request->first_name, 0, 2));

        $confirmationNumber = $uppercaseStateAbbr . $currentDate . $uppercaseLastName . $uppercaseFirstName  . '00' . $entriesCount;

        if (!empty($requestData->id)) {
            $requestData->update(['confirmation_no' => $confirmationNumber]);
        }

        // send email
        $emailAddress = $request->email;
        Mail::to($request->email)->send(new sendEmailAddress($emailAddress));

        EmailLog::create([
            'role_id' => 3,
            'request_id' =>  $requestData->id,
            'confirmation_number' => $confirmationNumber,
            'is_email_sent' => 1,
            'sent_tries' => 1,
            'create_date' => now(),
            'sent_date' => now(),
            'email_template' => $request->email,
            'subject_name' => 'Create account by clicking on below link with below email address',
            'email' => $request->email,
        ]);

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
