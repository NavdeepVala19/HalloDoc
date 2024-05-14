<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePatientRequest;
use App\Models\UserRoles;
use Carbon\Carbon;
use App\Models\Users;
use App\Models\AllUsers;
use App\Models\EmailLog;
use App\Models\RequestTable;
use App\Models\RequestClient;
use App\Models\RequestWiseFile;

use App\Mail\SendEmailAddress;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PatientController extends Controller
{
    // this controller is responsible for creating/storing the patient request

    /**
     * display patient request form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function patientRequests()
    {
        return view('patientSite/patientRequest');
    }


    /**
     * stores request in request_client and request table and if user is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    
    public function create(CreatePatientRequest $request)
    {
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

            $requestData = new RequestTable();
            $requestData->user_id = $requestEmail->id;
            $requestData->request_type_id = 1;
            $requestData->first_name = $request->first_name;
            $requestData->last_name = $request->last_name;
            $requestData->email = $request->email;
            $requestData->phone_number = $request->phone_number;
            $requestData->status = 1;
            $requestData->save();

            $patientRequest = new RequestClient();
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

            $patientRequest = new RequestClient();
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

        // if (!empty($requestData->id)) {
        if ($requestData->id) {
            $requestData->update(['confirmation_no' => $confirmationNumber]);
        }

        try {
            if ($isEmailStored == null) {
                // send email
                $emailAddress = $request->email;
                Mail::to($request->email)->send(new SendEmailAddress($emailAddress));

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
                return redirect()->route('submit.request')->with('message', 'Email for Create Account is Sent and Request is Submitted');
            } else {
                return redirect()->route('submit.request')->with('message', 'Request is Submitted');
            }
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }
}
