<?php

namespace App\Services;

use App\Helpers\ConfirmationNumber;
use App\Mail\SendEmailAddress;
use App\Models\AllUsers;
use App\Models\EmailLog;
use App\Models\RequestClient;
use App\Models\RequestTable;
use App\Models\RequestWiseFile;
use App\Models\UserRoles;
use App\Models\Users;
use Illuminate\Support\Facades\Mail;

class PatientRequestSubmitService
{
    /**
     * it stores request in request_client and request table and if user(patient) is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     *
     * @param mixed $request (input enter by user)
     *
     * @return object|Users|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Database\Eloquent\Model|null
     */
    public function storeRequest($request)
    {
        $isEmailStored = Users::where('email', $request->email)->first();

        // Store user details if email is not already stored
        if ($isEmailStored === null) {
            $storePatientInUsers = new Users();
            $storePatientInUsers->username = $request->first_name . ' ' . $request->last_name;
            $storePatientInUsers->email = $request->email;
            $storePatientInUsers->phone_number = $request->phone_number;
            $storePatientInUsers->save();

            $storePatientInAllUsers = new AllUsers();
            $storePatientInAllUsers->user_id = $storePatientInUsers->id;
            $storePatientInAllUsers->fill($request->only([
                'first_name',
                'last_name',
                'email',
                'phone_number',
                'street',
                'city',
                'state',
                'zipcode',
            ]));
            $storePatientInAllUsers->save();

            $userRole = new UserRoles();
            $userRole->role_id = 3;
            $userRole->user_id = $storePatientInUsers->id;
            $userRole->save();
        }

        $requestData = new RequestTable();
        $requestData->user_id = $isEmailStored ? $isEmailStored->id : $storePatientInUsers->id;
        $requestData->request_type_id = 1;
        $requestData->status = 1;
        $requestData->fill($request->only([
            'first_name',
            'last_name',
            'email',
            'phone_number',
        ]));
        $requestData->save();

        $patientRequest = new RequestClient();
        $patientRequest->request_id = $requestData->id;
        $patientRequest->fill($request->only([
            'first_name',
            'last_name',
            'date_of_birth',
            'email',
            'phone_number',
            'street',
            'city',
            'state',
            'zipcode',
            'room',
            'symptoms',
        ]));
        $patientRequest->save();

        // Store documents in request_wise_file table
        if ($request->hasFile('docs')) {
            $requestFile = new RequestWiseFile();
            $requestFile->request_id = $requestData->id;
            $requestFile->file_name = uniqid() . '_' . $request->file('docs')->getClientOriginalName();
            $request->file('docs')->storeAs('public', $requestFile->file_name);
            $requestFile->save();
        }

        // Generate confirmation number
        // $confirmationNumber = $this->generateConfirmationNumber($request);
        $confirmationNumber = ConfirmationNumber::generate($request);

        // Update confirmation number if request is created successfully
        if ($requestData->id) {
            $requestData->update(['confirmation_no' => $confirmationNumber]);
        }
        try {
            // Send email if email is not already stored
            if ($isEmailStored === null) {
                $emailAddress = $request->email;
                Mail::to($emailAddress)->send(new SendEmailAddress($emailAddress));

                EmailLog::create([
                    'role_id' => 3,
                    'request_id' => $requestData->id,
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
            }
            return $isEmailStored;
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }
}
