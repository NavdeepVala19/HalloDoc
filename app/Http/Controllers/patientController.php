<?php

namespace App\Http\Controllers;

use App\Models\UserRoles;
use Carbon\Carbon;
use App\Models\users;
use App\Models\allusers;
use App\Models\EmailLog;
use App\Models\RequestTable;
use App\Models\request_Client;
use App\Models\RequestWiseFile;

use App\Mail\sendEmailAddress;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class patientController extends Controller
{
    // this controller is responsible for creating/storing the patient request
    public function patientRequests()
    {
        return view('patientSite/patientRequest');
    }


    /**
     *@param $request the input which is enter by user

     * it stores request in request_client and request table and if user is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     */

    public function create(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'date_of_birth' => 'required|before:today',
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required',
            'street' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9\s,_-]+?$/',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'state' => 'min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'zipcode' => 'digits:6|gte:1',
            'docs' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc,docx|max:2048',
            'symptoms' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 \-_,()]+$/',
            'room' => 'gte:0|nullable|max:1000'
        ]);

        $isEmailStored = users::where('email', $request->email)->first();
        if ($isEmailStored == null) {
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

            $userRolesEntry = new UserRoles();
            $userRolesEntry->role_id = 3;
            $userRolesEntry->user_id = $requestEmail->id;
            $userRolesEntry->save();

            $requestData = new RequestTable();
            $requestData->user_id = $requestEmail->id;
            $requestData->request_type_id = 1;
            $requestData->first_name = $request->first_name;
            $requestData->last_name = $request->last_name;
            $requestData->email = $request->email;
            $requestData->phone_number = $request->phone_number;
            $requestData->status = 1;
            $requestData->save();

            $patientRequest = new request_Client();
            $patientRequest->request_id = $requestData->id;
            $patientRequest->first_name = $request->first_name;
            $patientRequest->last_name = $request->last_name;
            $patientRequest->date_of_birth = $request->date_of_birth;
            $patientRequest->email = $request->email;
            $patientRequest->phone_number = $request->phone_number;
            $patientRequest->street = $request->street;
            $patientRequest->city = $request->city;
            $patientRequest->state = $request->state;
            $patientRequest->zipcode = $request->zipcode;
            $patientRequest->room = $request->room;
            $patientRequest->notes = $request->symptoms;
            $patientRequest->save();

            // store documents in request_wise_file table
            if (isset($request->docs)) {
                $request_file = new RequestWiseFile();
                $request_file->request_id = $requestData->id;
                $request_file->file_name = uniqid() . '_' . $request->file('docs')->getClientOriginalName();
                $request->file('docs')->storeAs('public', $request_file->file_name);
                $request_file->save();
            }
        } else {

            $requestData = new RequestTable();
            $requestData->user_id = $isEmailStored->id;
            $requestData->request_type_id = 1;
            $requestData->first_name = $request->first_name;
            $requestData->last_name = $request->last_name;
            $requestData->email = $request->email;
            $requestData->phone_number = $request->phone_number;
            $requestData->status = 1;
            $requestData->save();

            $patientRequest = new request_Client();
            $patientRequest->request_id = $requestData->id;
            $patientRequest->first_name = $request->first_name;
            $patientRequest->last_name = $request->last_name;
            $patientRequest->date_of_birth = $request->date_of_birth;
            $patientRequest->email = $request->email;
            $patientRequest->phone_number = $request->phone_number;
            $patientRequest->street = $request->street;
            $patientRequest->city = $request->city;
            $patientRequest->state = $request->state;
            $patientRequest->zipcode = $request->zipcode;
            $patientRequest->room = $request->room;
            $patientRequest->notes = $request->symptoms;
            $patientRequest->save();

            // store documents in request_wise_file table
            if (isset($request->docs)) {
                $request_file = new RequestWiseFile();
                $request_file->request_id = $requestData->id;
                $request_file->file_name = uniqid() . '_' . $request->file('docs')->getClientOriginalName();
                $request->file('docs')->storeAs('public', $request_file->file_name);
                $request_file->save();
            }
        }

        // confirmation number
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

        try {
            if ($isEmailStored == null) {
                // send email
                $emailAddress = $request->email;
                Mail::to($request->email)->send(new sendEmailAddress($emailAddress));

                EmailLog::create([
                    'role_id' => 3,
                    'request_id' =>  $requestData->id,
                    'confirmation_number' => $confirmationNumber,
                    'is_email_sent' => 1,
                    'recipient_name' => $request->first_name . ' ' . $request->last_name,
                    'sent_tries' => 1,
                    'create_date' => now(),
                    'sent_date' => now(),
                    'email_template' => $request->email,
                    'subject_name' => 'Create account by clicking on below link with below email address',
                    'email' => $request->email,
                    'action' => 5,
                ]);
                return redirect()->route('submitRequest')->with('message', 'Email for Create Account is Sent and Request is Submitted');
            } else {
                return redirect()->route('submitRequest')->with('message', 'Request is Submitted');
            }
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }
}
