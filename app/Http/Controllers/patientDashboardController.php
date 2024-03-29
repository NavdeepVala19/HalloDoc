<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\users;
use App\Models\Status;
use App\Models\allusers;
use App\Models\EmailLog;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\RequestStatus;
use App\Http\Controllers\view;
use App\Mail\sendEmailAddress;
use App\Models\request_Client;
use App\Models\RequestWiseFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class patientDashboardController extends Controller
{
    public function patientDashboard()
    {
        return view("patientSite/patientDashboard");
    }


    public function createNewRequest()
    {
        $userData = Auth::user();
        $email = $userData["email"];

        return view("patientSite/patientNewRequest", compact('email'));
    }

    public function createSomeoneRequest()
    {
        return view("patientSite/patientSomeoneRequest");
    }

    public function viewAgreement($data)
    {
        $clientData = RequestTable::with('requestClient')->where('id', $data)->first();
        return view("patientSite/patientAgreement", compact('clientData'));
    }

    public function createNewPatient(Request $request)
    {
        $userData = Auth::user();
        $email = $userData["email"];


        $request->validate([
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'required|min:2|max:30',
            'date_of_birth' => 'required',
            'phone_number' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'street' => 'min:2|max:30',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'state' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'zipcode' => 'digits:6',
            'docs' => 'nullable'
        ]);

        $newPatient = new RequestTable();
        $requestStatus = new RequestStatus();

        $newPatient->request_type_id = $request->request_type;
        $newPatient->first_name = $request->first_name;
        $newPatient->last_name = $request->last_name;
        $newPatient->email = $email;
        $newPatient->phone_number = $request->phone_number;
        $newPatient->relation_name = $request->relation;
        $newPatient->save();

        $newPatientRequest = new request_Client();
        $newPatientRequest->request_id = $newPatient->id;
        $newPatientRequest->first_name = $request->first_name;
        $newPatientRequest->last_name = $request->last_name;
        $newPatientRequest->date_of_birth = $request->date_of_birth;
        $newPatientRequest->email = $email;
        $newPatientRequest->phone_number = $request->phone_number;
        $newPatientRequest->street = $request->street;
        $newPatientRequest->city = $request->city;
        $newPatientRequest->state = $request->state;
        $newPatientRequest->zipcode = $request->zipcode;
        $newPatientRequest->room = $request->room;

        $newPatientRequest->save();


        $requestStatus->request_id = $newPatient->id;
        $requestStatus->status = 1;
        $requestStatus->save();


        if (!empty($requestStatus)) {
            $newPatient->update(['status' => $requestStatus->id]);
        }

        // store documents in request_wise_file table

        $request_file = new RequestWiseFile();
        $request_file->request_id = $newPatient->id;
        $request_file->file_name = $request->file('docs')->getClientOriginalName();
        $path = $request->file('docs')->storeAs('public', $request->file('docs')->getClientOriginalName());
        $request_file->save();   $request_file = new RequestWiseFile();

        $request_file->request_id = $newPatient->id;
        $fileName = isset($request->docs) ? $request->file('docs')->store('public') : '';
        $request_file->file_name = $fileName;
        $request_file->save();

        // store symptoms in request_notes table

        $request_notes = new RequestNotes();
        $request_notes->request_id = $newPatient->id;
        $request_notes->patient_notes = $request->symptoms;

        $request_notes->save();

        // confirmation number
        $currentTime = Carbon::now();
        $currentDate = $currentTime->format('Y');

        $todayDate = $currentTime->format('Y-m-d');
        $entriesCount = RequestTable::whereDate('created_at', $todayDate)->count();

        $uppercaseStateAbbr = strtoupper(substr($request->state, 0, 2));
        $uppercaseLastName = strtoupper(substr($request->last_name, 0, 2));
        $uppercaseFirstName = strtoupper(substr($request->first_name, 0, 2));

        $confirmationNumber = $uppercaseStateAbbr . $currentDate . $uppercaseLastName . $uppercaseFirstName  . '00' . $entriesCount;

        if (!empty($newPatient->id)) {
            $newPatient->update(['confirmation_no' => $confirmationNumber]);
        }

        return redirect()->route('patientDashboardData');
    }

    public function createSomeOneElseRequest(Request $request)
    {

        $userData = Auth::user();
        $email = $userData["email"];


        $request->validate([
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'required|min:2|max:30',
            'date_of_birth' => 'required',
            'email' => 'required|email|min:2|max:30',
            'phone_number' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'street' => 'min:2|max:30',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'state' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'zipcode' => 'digits:6',
            'docs' => 'nullable',
            'relation' => 'alpha|nullable'
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


        $newPatient = new RequestTable();
        $requestStatus = new RequestStatus();

        $newPatient->request_type_id = $request->request_type;
        $newPatient->first_name = $request->first_name;
        $newPatient->last_name = $request->last_name;
        $newPatient->email = $request->email;
        $newPatient->phone_number = $request->phone_number;
        $newPatient->relation_name = $request->relation;
        $newPatient->save();

        $newPatientRequest = new request_Client();
        $newPatientRequest->request_id = $newPatient->id;
        $newPatientRequest->first_name = $request->first_name;
        $newPatientRequest->last_name = $request->last_name;
        $newPatientRequest->date_of_birth = $request->date_of_birth;
        $newPatientRequest->email = $request->email;
        $newPatientRequest->phone_number = $request->phone_number;
        $newPatientRequest->street = $request->street;
        $newPatientRequest->city = $request->city;
        $newPatientRequest->state = $request->state;
        $newPatientRequest->zipcode = $request->zipcode;
        $newPatientRequest->room = $request->room;

        $newPatientRequest->save();



        $requestStatus->request_id = $newPatient->id;
        $requestStatus->status = 1;
        $requestStatus->save();


        if (!empty($requestStatus)) {
            $newPatient->update(['status' => $requestStatus->id]);
        }


        // store documents in request_wise_file table

        $request_file = new RequestWiseFile();
        $request_file->request_id = $newPatient->id;
        $request_file->file_name = $request->file('docs')->getClientOriginalName();
        $path = $request->file('docs')->storeAs('public', $request->file('docs')->getClientOriginalName());
        $request_file->save();

        // store symptoms in request_notes table

        $request_notes = new RequestNotes();
        $request_notes->request_id = $newPatient->id;
        $request_notes->patient_notes = $request->symptoms;

        $request_notes->save();

        // confirmation number
        $currentTime = Carbon::now();
        $currentDate = $currentTime->format('Y');

        $todayDate = $currentTime->format('Y-m-d');
        $entriesCount = RequestTable::whereDate('created_at', $todayDate)->count();

        $uppercaseStateAbbr = strtoupper(substr($request->state, 0, 2));
        $uppercaseLastName = strtoupper(substr($request->last_name, 0, 2));
        $uppercaseFirstName = strtoupper(substr($request->first_name, 0, 2));

        $confirmationNumber = $uppercaseStateAbbr . $currentDate . $uppercaseLastName . $uppercaseFirstName  . '00' . $entriesCount;

        if (!empty($newPatient->id)) {
            $newPatient->update(['confirmation_no' => $confirmationNumber]);
        }




        if ($isEmailStored == null) {
            // send email
            $emailAddress = $request->email;
            Mail::to($request->email)->send(new sendEmailAddress($emailAddress));

            EmailLog::create([
                'role_id' => 3,
                'request_id' =>  $newPatient->id,
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

        if ($isEmailStored == null) {
            return redirect()->route('patientDashboardData')->with('message', 'Email for Create Account is Sent');
        } else {
            return redirect()->route('patientDashboardData');
        }
    }


    public function read()
    {
        $userData = Auth::user();
        $email = $userData["email"];

        $data = request_Client::select(
            'request_status.status',
            'request_client.request_id',
            'request_wise_file.id',
            'status.status_type',
            DB::raw('DATE(request_client.created_at) as created_date'),
        )
            ->leftJoin('request_status', 'request_status.request_id', 'request_client.request_id')
            ->leftJoin('status', 'status.id', 'request_status.status')
            ->leftJoin('request_wise_file', 'request_wise_file.request_id', 'request_client.request_id')
            ->where('email', $email)
            ->paginate(10);

        return view('patientSite/patientDashboard', compact('data'));
    }
}
