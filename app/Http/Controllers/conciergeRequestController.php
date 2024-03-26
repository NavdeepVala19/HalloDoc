<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\users;
use App\Models\allusers;
use App\Models\EmailLog;
use App\Models\Concierge;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\RequestStatus;

use App\Mail\sendEmailAddress;
use App\Models\request_Client;
use App\Models\RequestConcierge;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Mail;

// use App\Models\User;

class conciergeRequestController extends Controller
{

    public function conciergeRequests()
    {
        return view('patientSite/conciergeRequest');
    }

    public function create(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'required|min:2|max:30',
            'date_of_birth' => 'required',
            'email' => 'required|email|min:2|max:30',
            'phone_number' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'concierge_first_name' => 'required|min:2|max:30',
            'concierge_last_name' => 'required|min:2|max:30',
            'concierge_email' => 'required|email|min:2|max:30',
            'concierge_mobile' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'concierge_hotel_name' => 'required|min:2|max:30',
            'concierge_street' => 'required|min:2|max:30',
            'concierge_state' => 'required|min:2|max:30',
            'concierge_city' => 'required|min:2|max:30',
            'concierge_zip_code' => 'digits:6',
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
        }

        $requestEmail = new users();

        // concierge request into concierge table

        $concierge = new Concierge();
        $concierge->name = $request->concierge_first_name;
        $concierge->address = $request->concierge_hotel_name;
        $concierge->street = $request->concierge_street;
        $concierge->city = $request->concierge_city;
        $concierge->state = $request->concierge_state;
        $concierge->zipcode = $request->concierge_zip_code;

        $concierge->save();

        // concierge request into request table

        $requestConcierge = new RequestTable();
        $requestStatus = new RequestStatus();

        $requestConcierge->status = $requestStatus->id;
        $requestConcierge->user_id = $requestEmail->id;
        $requestConcierge->request_type_id = $request->request_type;
        $requestConcierge->first_name = $request->concierge_first_name;
        $requestConcierge->last_name = $request->concierge_last_name;
        $requestConcierge->email = $request->concierge_email;
        $requestConcierge->phone_number = $request->concierge_mobile;
        $requestConcierge->relation_name = $request->concierge_hotel_name;
        $requestConcierge->save();


        $requestStatus->request_id = $requestConcierge->id;
        $requestStatus->status = 1;
        $requestStatus->save();

        if (!empty($requestStatus)) {
            $requestConcierge->update(["status" => $requestStatus->id]);
        }



        $patientRequest = new request_Client();
        $patientRequest->request_id = $requestConcierge->id;
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

        $patientRequest->save();


        // store symptoms in request_notes table

        $request_notes = new RequestNotes();
        $request_notes->request_id = $requestConcierge->id;
        $request_notes->patient_notes = $request->symptoms;

        $request_notes->save();


        // store data in request_concierge table
        $conciergeRequest = new RequestConcierge();
        $conciergeRequest->request_id = $requestConcierge->id;
        $conciergeRequest->concierge_id = $concierge->id;
        $conciergeRequest->save();




        $currentTime = Carbon::now();
        $currentDate = $currentTime->format('Y');

        $todayDate = $currentTime->format('Y-m-d');
        $entriesCount = RequestTable::whereDate('created_at', $todayDate)->count();


        $confirmationNumber = substr($request->concierge_state, 0, 2) . $currentDate . substr($request->last_name, 0, 2) . substr($request->first_name, 0, 2) . '00' . $entriesCount;


        if (!empty($requestConcierge->id)) {
            $requestConcierge->update(['confirmation_no' => $confirmationNumber]);
        }

        if ($isEmailStored == null) {

            // send email
            $emailAddress = $request->email;
            Mail::to($request->email)->send(new sendEmailAddress($emailAddress));

            EmailLog::create([
                'request_id' => $requestConcierge->id,
                'confirmation_number' => $confirmationNumber,
                'role_id' => 3,
                'is_email_sent' => 1,
                'sent_tries' => 1,
                'create_date' => now(),
                'sent_date' => now(),
                'email_template' => $request->email,
                'subject_name' => 'Create account by clicking on below link with below email address',
                'email' => $request->email,
            ]);
        }

        if ($isEmailStored == null) {
            return redirect()->route('submitRequest')->with('message', 'Email for Create Account is Sent');
        } else {
            return redirect()->route('submitRequest');
        }
    }
}
