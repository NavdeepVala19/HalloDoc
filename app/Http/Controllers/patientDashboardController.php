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
use Illuminate\Support\Facades\Crypt;


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
        try {
            $id = Crypt::decrypt($data);
            $clientData = RequestTable::with('requestClient')->where('id', $id)->first();
            if (!empty($clientData)) {
                return view("patientSite/patientAgreement", compact('clientData'));
            }
        } catch (\Throwable $th) {
            //throw $th;
            return view('errors.404');
        }
    }

    // Agreement Agreed by Patient
    public function agreeAgreement(Request $request)
    {
        $caseStatus = RequestTable::where('id', $request->requestId)->first()->status;
        if ($caseStatus == 4) {
            return redirect()->back()->with('alreadyAgreed', 'You have already agreed to the Agreement');
        } else if ($caseStatus == 11) {
            return redirect()->back()->with('errorAlreadyCancelled', "You have already Cancelled the Agreement(You can't change now)");
        }
        $physicianId = RequestTable::where('id', $request->requestId)->first()->physician_id;

        RequestTable::where('id', $request->requestId)->update([
            'status' => 4,
        ]);
        RequestStatus::create([
            'request_id' => $request->requestId,
            'status' => 4,
            'physician_id' => $physicianId,
        ]);

        return redirect()->back()->with('agreementAgreed', 'Agreement Agreed Successfully');
    }

    // Agreeemnt Cancelled by Patient
    public function cancelAgreement(Request $request)
    {
        $caseStatus = RequestTable::where('id', $request->requestId)->first()->status;

        if ($caseStatus == 4) {
            return redirect()->back()->with('errorAlreadyAgreed', "You have already agreed to the Agreement(You can't change now)");
        } else if ($caseStatus == 11) {
            return redirect()->back()->with('alreadyCancelled', "You have already Cancelled the Agreement");
        }
        RequestTable::where('id', $request->requestId)->update([
            'status' => 11,
            'physician_id' => DB::raw("Null"),
            'declined_by' => 'Patient'
        ]);
        RequestStatus::create([
            'request_id' => $request->requestId,
            'status' => 11,
            'physician_id' => DB::raw("Null"),
            'notes' => $request->cancelReason,
        ]);
        return redirect()->back()->with('agreementCancelled', 'Agreement Cancelled Sucessfully');
    }

    public function createNewPatient(Request $request)
    {
        $userData = Auth::user();
        $email = $userData["email"];

        $request->validate([
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'date_of_birth' => 'required',
            'phone_number' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'street' => 'min:2|max:30',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'state' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'zipcode' => 'digits:6|gte:1',
            'docs' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'symptoms' => 'nullable|min:5|max:200|',
            'room' => 'gte:1|nullable|max:1000'
        ]);

        $newPatient = new RequestTable();
        $newPatient->request_type_id = 1;
        $newPatient->first_name = $request->first_name;
        $newPatient->last_name = $request->last_name;
        $newPatient->email = $email;
        $newPatient->phone_number = $request->phone_number;
        $newPatient->relation_name = $request->relation;
        $newPatient->status = 1;
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


        // store documents in request_wise_file table

        if (isset($request->docs)) {
            $request_file = new RequestWiseFile();
            $request_file->request_id = $newPatient->id;
            $request_file->file_name = $request->file('docs')->getClientOriginalName();
            $path = $request->file('docs')->storeAs('public', $request->file('docs')->getClientOriginalName());
            $request_file->save();
        }

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

        $request->validate([
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'date_of_birth' => 'required',
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'street' => 'min:2|max:30',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'state' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'zipcode' => 'digits:6|gte:1',
            'docs' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'symptoms' => 'nullable|min:5|max:200|',
            'room' => 'gte:1|nullable|max:1000',
            'relation' => 'nullable|alpha'
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

        $newPatient->request_type_id = 1;
        $newPatient->first_name = $request->first_name;
        $newPatient->last_name = $request->last_name;
        $newPatient->email = $request->email;
        $newPatient->phone_number = $request->phone_number;
        $newPatient->relation_name = $request->relation;
        $newPatient->status = 1;
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



        // store documents in request_wise_file table

        if (isset($request->docs)) {
            $request_file = new RequestWiseFile();
            $request_file->request_id = $newPatient->id;
            $request_file->file_name = $request->file('docs')->getClientOriginalName();
            $path = $request->file('docs')->storeAs('public', $request->file('docs')->getClientOriginalName());
            $request_file->save();
        }

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

        $data = RequestTable::select(
            'request.id',
            'request_wise_file.request_id',
            'status.status_type',
            DB::raw('DATE(request.created_at) as created_date'),
        )
            ->leftJoin('status', 'status.id', 'request.status')
            ->leftJoin('request_wise_file', 'request_wise_file.request_id', 'request.id')
            ->where('email', $email)
            ->paginate(10);

        return view('patientSite/patientDashboard', compact('data', 'userData'));
    }
}