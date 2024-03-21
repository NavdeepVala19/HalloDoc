<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\users;
use App\Models\allusers;
use App\Models\EmailLog;
use App\Models\RequestWise;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\RequestStatus;
use App\Mail\sendEmailAddress;
use App\Models\request_Client;
use App\Models\RequestWiseFile;
use Illuminate\Support\Facades\Mail;
// use App\Models\User;
class familyRequestController extends Controller
{


    public function create(Request $request)
    {
   
        $request->validate([
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'string|min:2|max:30',
            'date_of_birth' => 'required',
            'email' => 'required|email|min:2|max:30',
            'phone_number' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'street' => 'min:2|max:30',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'state' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'zipcode' => 'digits:6',
            'family_first_name' => 'required|min:2|max:30',
            'family_last_name' => 'min:2|max:30',
            'family_email' => 'required|email|min:2|max:30',
            'family_phone_number' => 'required',
            'family_relation' => 'required',
        ]);

        $isEmailStored = users::where('email', $request->email)->pluck('email');
       

        if($request->email != $isEmailStored){
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

        // family request creating

        $familyRequest = new RequestTable();

        $familyRequest->user_id = $requestEmail->id;
        $familyRequest->request_type_id = $request->request_type;
        $familyRequest->first_name = $request->family_first_name;
        $familyRequest->last_name = $request->family_last_name;
        $familyRequest->email = $request->family_email;
        $familyRequest->phone_number = $request->family_phone_number;
        $familyRequest->relation_name = $request->family_relation;
        $familyRequest->save();

        $requestStatus = new RequestStatus();
        $requestStatus->request_id = $familyRequest->id;
        $requestStatus->status = 1;
        $requestStatus->save();


        if (!empty($requestStatus)) {
            $familyRequest->update(['status' => $requestStatus->id]);
        }


        $patientRequest = new request_Client();
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

        $patientRequest->save();


        // store documents in request_wise_file table

        if (isset($request->docs)) {
            $request_file = new RequestWiseFile();
            $request_file->request_id = $familyRequest->id;
            $request_file->file_name = $request->file('docs')->getClientOriginalName();
            $path = $request->file('docs')->storeAs('public', $request->file('docs')->getClientOriginalName());
            $request_file->save();
        }


        // store symptoms in request_notes table

        $request_notes = new RequestNotes();
        $request_notes->request_id = $familyRequest->id;
        $request_notes->patient_notes = $request->symptoms;

        $request_notes->save();


 
        $currentTime = Carbon::now();
        $currentDate = $currentTime->format('Y');

        $todayDate = $currentTime->format('Y-m-d');
        $entriesCount = RequestTable::whereDate('created_at', $todayDate)->count();


        $uppercaseStateAbbr = strtoupper(substr($request->state, 0, 2));
        $uppercaseLastName = strtoupper(substr($request->last_name, 0, 2));
        $uppercaseFirstName = strtoupper(substr($request->first_name, 0, 2));



        $confirmationNumber = $uppercaseStateAbbr . $currentDate . $uppercaseLastName . $uppercaseFirstName  . '00' . $entriesCount;

        if (!empty($familyRequest->id)) {
            $familyRequest->update(['confirmation_no' => $confirmationNumber]);
        }


        if ($request->email != $isEmailStored) {

            // send email
            $emailAddress = $request->email;
            Mail::to($request->email)->send(new sendEmailAddress($emailAddress));

            EmailLog::create([
                'role_id' => 3,
                'request_id' => $familyRequest->id,
                'confirmation_number' => $confirmationNumber,
                'is_email_sent' => 1,
                'sent_tries' => 1,
                'create_date' => now(),
                'sent_date' => now(),
                'email_template' => $request->email,
                'subject_name' => 'Create account by clicking on below link with below email address',
                'email' => $request->email,
            ]);

        }

        return redirect()->route('submitRequest')->with('message','Email for Create Account is Sent');
    }
}
