<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailAddress;
use App\Models\AllUsers;
use App\Models\EmailLog;
use App\Models\RequestClient;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use App\Models\UserRoles;
use App\Models\Users;

class ProviderCreateRequestService
{
    /**
     * it generates confirmation number
     *
     * @param mixed $request
     *
     * @return string
     */
    private function generateConfirmationNumber($request)
    {
        $currentTime = now();
        $currentDate = $currentTime->format('Y');
        $todayDate = $currentTime->format('Y-m-d');
        $entriesCount = RequestTable::whereDate('created_at', $todayDate)->count();

        $uppercaseStateAbbr = strtoupper(substr($request->state, 0, 2));
        $uppercaseLastName = strtoupper(substr($request->last_name, 0, 2));
        $uppercaseFirstName = strtoupper(substr($request->first_name, 0, 2));

        return $uppercaseStateAbbr . $currentDate . $uppercaseLastName . $uppercaseFirstName  . '00' . $entriesCount;
    }

    public function storeRequest($request, $providerId){

        // check if email already exists in users table
        $isEmailStored = Users::where('email', $request->email)->first();

        // Store user details if email is not already stored
        if ($isEmailStored === null) {
            $storePatientInUsers = new Users();
            $storePatientInUsers->username = $request->first_name . " " . $request->last_name;
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
        $requestData->request_type_id = $request->request_type_id;
        $requestData->status = 3;
        $requestData->physician_id = $providerId;
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
        ]));
        $patientRequest->save();

        // Store notes in RequestNotes table
        $requestNotes = new RequestNotes();
        $requestNotes->request_id = $requestData->id;
        $requestNotes->physician_notes = $request->note;
        $requestNotes->created_by = 'physician';
        $requestNotes->save();

        // Generate confirmation number
        $confirmationNumber = $this->generateConfirmationNumber($request);

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
                    'role_id' => 2,
                    'request_id' => $requestData->id,
                    'confirmation_number' => $confirmationNumber,
                    'is_email_sent' => 1,
                    'recipient_name' => $request->first_name.' '.$request->last_name,
                    'provider_id' => $providerId,
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
