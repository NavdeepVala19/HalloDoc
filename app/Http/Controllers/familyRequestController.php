<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Users;
use App\Models\AllUsers;
use App\Models\EmailLog;
use App\Models\UserRoles;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Mail\SendEmailAddress;
use App\Models\RequestClient;
use App\Models\RequestWiseFile;
use Illuminate\Support\Facades\Mail;

// this controller is responsible for creating/storing the family request

class familyRequestController extends Controller
{

    public function familyRequests()
    {
        return view('patientSite/familyRequest');
    }


    /**
     *@param $request the input which is enter by user

     * it stores request in request_client and request table and if user(patient) is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     */


    public function create(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'min:2', 'max:40', 'regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/'],
            'family_email' => ['required', 'email', 'min:2', 'max:40', 'regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/'],
            'city' => ['required', 'min:2', 'max:30', 'regex:/^[a-zA-Z ]+?$/'],
            'state' => ['required', 'min:2', 'max:30', 'regex:/^[a-zA-Z ]+?$/'],
            'symptoms' => ['regex:/^[a-zA-Z0-9 \-_,()]+$/', 'nullable', 'min:5', 'max:200'],
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'date_of_birth' => 'required|before:today',
            'phone_number' => 'required|min_digits:10|max_digits:10',
            'street' => 'required|min:2|max:50',
            'zipcode' => 'digits:6|gte:1',
            'family_first_name' => 'required|min:3|max:15|alpha',
            'family_last_name' => 'required|min:3|max:15|alpha',
            'family_phone_number' => 'required',
            'family_relation' => 'required|alpha',
            'docs' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc,docx|max:2048',
            'room' => 'gte:1|nullable|max:1000'
        ]);

        $isEmailStored = Users::where('email', $request->email)->first();

        if ($isEmailStored == null) {
            // store email and phoneNumber in users table
            $requestEmail = new Users();
            $requestEmail->username = $request->first_name . " " . $request->last_name;
            $requestEmail->email = $request->email;
            $requestEmail->phone_number = $request->phone_number;
            $requestEmail->save();

            // store all details of patient in allUsers table

            $requestUsers = new AllUsers();
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

            $familyRequest = new RequestTable();
            $familyRequest->user_id = $requestEmail->id;
            $familyRequest->request_type_id = 2;
            $familyRequest->first_name = $request->family_first_name;
            $familyRequest->last_name = $request->family_last_name;
            $familyRequest->email = $request->family_email;
            $familyRequest->phone_number = $request->family_phone_number;
            $familyRequest->relation_name = $request->family_relation;
            $familyRequest->status = 1;
            $familyRequest->save();

            $patientRequest = new RequestClient();
            $patientRequest->request_id = $familyRequest->id;
            $patientRequest->first_name = $request->first_name;
            $patientRequest->last_name = $request->last_name;
            $patientRequest->date_of_birth = $request->date_of_birth;
            $patientRequest->email = $request->email;
            $patientRequest->phone_number = $request->phone_number;
            $patientRequest->street = $request->street;
            $patientRequest->city = $request->city;
            $patientRequest->state = $request->state;
            $patientRequest->zipcode = $request->zipcode;
            $patientRequest->notes = $request->symptoms;
            $patientRequest->save();


            // store documents in request_wise_file table

            if (isset($request->docs)) {
                $request_file = new RequestWiseFile();
                $request_file->request_id = $familyRequest->id;
                $request_file->file_name = uniqid() . '_' . $request->file('docs')->getClientOriginalName();
                $request->file('docs')->storeAs('public', $request_file->file_name);
                $request_file->save();
            }
        } else {
            $familyRequest = new RequestTable();
            $familyRequest->user_id = $isEmailStored->id;
            $familyRequest->request_type_id = 2;
            $familyRequest->first_name = $request->family_first_name;
            $familyRequest->last_name = $request->family_last_name;
            $familyRequest->email = $request->family_email;
            $familyRequest->phone_number = $request->family_phone_number;
            $familyRequest->relation_name = $request->family_relation;
            $familyRequest->status = 1;
            $familyRequest->save();


            $patientRequest = new RequestClient();
            $patientRequest->request_id = $familyRequest->id;
            $patientRequest->first_name = $request->first_name;
            $patientRequest->last_name = $request->last_name;
            $patientRequest->date_of_birth = $request->date_of_birth;
            $patientRequest->email = $request->email;
            $patientRequest->phone_number = $request->phone_number;
            $patientRequest->street = $request->street;
            $patientRequest->city = $request->city;
            $patientRequest->state = $request->state;
            $patientRequest->zipcode = $request->zipcode;
            $patientRequest->notes = $request->symptoms;
            $patientRequest->save();


            // store documents in request_wise_file table

            if (isset($request->docs)) {
                $request_file = new RequestWiseFile();
                $request_file->request_id = $familyRequest->id;
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

        // if (!empty($familyRequest->id)) {
        if ($familyRequest->id) {
            $familyRequest->update(['confirmation_no' => $confirmationNumber]);
        }

        try {
            if ($isEmailStored == null) {
                // send email
                $emailAddress = $request->email;
                Mail::to($request->email)->send(new SendEmailAddress($emailAddress));

                EmailLog::create([
                    'role_id' => 3,
                    'request_id' => $familyRequest->id,
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
