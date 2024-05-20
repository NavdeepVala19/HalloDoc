<?php

namespace App\Http\Controllers;

use App\Mail\SendEmailAddress;
use App\Models\AllUsers;
use App\Models\Concierge;
use App\Models\EmailLog;
use App\Models\RequestClient;
use App\Models\RequestConcierge;
use App\Models\RequestTable;
use App\Models\UserRoles;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


// this controller is responsible for creating/storing the concierge request
class conciergeRequestController extends Controller
{
    public function conciergeRequests()
    {
        return view('patientSite/conciergeRequest');
    }

    /**
     *@param $request the input which is enter by user

     * it stores request in request_client and request table and if user(patient) is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     */
    public function create(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'date_of_birth' => 'required',
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required|min_digits:10|max_digits:10',
            'concierge_first_name' => 'required|min:3|max:15|alpha',
            'concierge_last_name' => 'required|min:3|max:15|alpha',
            'concierge_email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'concierge_mobile' => 'required',
            'concierge_hotel_name' => 'required|min:2|max:50|regex:/^[a-zA-Z ,_-]+?$/',
            'concierge_street' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9\s,_-]+?$/',
            'concierge_state' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'concierge_city' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'concierge_zip_code' => 'digits:6|gte:1',
            'symptoms' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 \-_,()]+$/',
            'room' => 'gte:1|nullable|max_digits:4|numeric|lt:1000'
        ]);

        $isEmailStored = Users::where('email', $request->email)->first();

        if ($isEmailStored === null) {
            // store email and phoneNumber in users table
            $requestEmail = new Users();
            $requestEmail->username = $request->first_name . ' ' . $request->last_name;
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

            // concierge request into request table
            $requestConcierge = new RequestTable();
            $requestConcierge->status = 1;
            $requestConcierge->user_id = $requestEmail->id;
            $requestConcierge->request_type_id = 3;
            $requestConcierge->first_name = $request->concierge_first_name;
            $requestConcierge->last_name = $request->concierge_last_name;
            $requestConcierge->email = $request->concierge_email;
            $requestConcierge->phone_number = $request->concierge_mobile;
            $requestConcierge->relation_name = $request->concierge_hotel_name;
            $requestConcierge->save();

            $patientRequest = new RequestClient();
            $patientRequest->request_id = $requestConcierge->id;
            $patientRequest->first_name = $request->first_name;
            $patientRequest->last_name = $request->last_name;
            $patientRequest->date_of_birth = $request->date_of_birth;
            $patientRequest->email = $request->email;
            $patientRequest->phone_number = $request->phone_number;
            $patientRequest->street = $request->concierge_street;
            $patientRequest->city = $request->concierge_city;
            $patientRequest->state = $request->concierge_state;
            $patientRequest->zipcode = $request->concierge_zip_code;
            $patientRequest->room = $request->room;
            $patientRequest->notes = $request->symptoms;
            $patientRequest->save();

            $concierge = new Concierge();
            $concierge->name = $request->concierge_first_name;
            $concierge->address = $request->concierge_hotel_name;
            $concierge->street = $request->concierge_street;
            $concierge->city = $request->concierge_city;
            $concierge->state = $request->concierge_state;
            $concierge->zipcode = $request->concierge_zip_code;
            $concierge->save();

            // store data in request_concierge table
            $conciergeRequest = new RequestConcierge();
            $conciergeRequest->request_id = $requestConcierge->id;
            $conciergeRequest->concierge_id = $concierge->id;
            $conciergeRequest->save();
        } else {
            // concierge request into request table
            $requestConcierge = new RequestTable();
            $requestConcierge->status = 1;
            $requestConcierge->user_id = $isEmailStored->id;
            $requestConcierge->request_type_id = 3;
            $requestConcierge->first_name = $request->concierge_first_name;
            $requestConcierge->last_name = $request->concierge_last_name;
            $requestConcierge->email = $request->concierge_email;
            $requestConcierge->phone_number = $request->concierge_mobile;
            $requestConcierge->relation_name = $request->concierge_hotel_name;
            $requestConcierge->save();

            // concierge request into concierge table
            $concierge = new Concierge();
            $concierge->name = $request->concierge_first_name;
            $concierge->address = $request->concierge_hotel_name;
            $concierge->street = $request->concierge_street;
            $concierge->city = $request->concierge_city;
            $concierge->state = $request->concierge_state;
            $concierge->zipcode = $request->concierge_zip_code;
            $concierge->save();

            $patientRequest = new RequestClient();
            $patientRequest->request_id = $requestConcierge->id;
            $patientRequest->first_name = $request->first_name;
            $patientRequest->last_name = $request->last_name;
            $patientRequest->date_of_birth = $request->date_of_birth;
            $patientRequest->email = $request->email;
            $patientRequest->phone_number = $request->phone_number;
            $patientRequest->street = $request->concierge_street;
            $patientRequest->city = $request->concierge_city;
            $patientRequest->state = $request->concierge_state;
            $patientRequest->zipcode = $request->concierge_zip_code;
            $patientRequest->room = $request->room;
            $patientRequest->notes = $request->symptoms;
            $patientRequest->save();

            // store data in request_concierge table
            $conciergeRequest = new RequestConcierge();
            $conciergeRequest->request_id = $requestConcierge->id;
            $conciergeRequest->concierge_id = $concierge->id;
            $conciergeRequest->save();
        }

        // confirmation number
        $currentTime = Carbon::now();
        $currentDate = $currentTime->format('Y');

        $todayDate = $currentTime->format('Y-m-d');
        $entriesCount = RequestTable::whereDate('created_at', $todayDate)->count();

        $confirmationNumber = substr($request->concierge_state, 0, 2) . $currentDate . substr($request->last_name, 0, 2) . substr($request->first_name, 0, 2) . '00' . $entriesCount;

        // if (!empty($requestConcierge->id)) {
        if ($requestConcierge->id) {
            $requestConcierge->update(['confirmation_no' => $confirmationNumber]);
        }

        try {
            if ($isEmailStored === null) {
                // send email
                $emailAddress = $request->email;
                Mail::to($request->email)->send(new SendEmailAddress($emailAddress));

                EmailLog::create([
                    'request_id' => $requestConcierge->id,
                    'confirmation_number' => $confirmationNumber,
                    'role_id' => 3,
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
                return redirect()->route('submit.request')->with('message', 'Email for Create Account is Sent and Request is Submitted');
            }
            return redirect()->route('submit.request')->with('message', 'Request is Submitted');
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }
}
