<?php

namespace App\Services;

use App\Models\UserRoles;
use App\Models\Users;
use App\Models\AllUsers;
use App\Models\EmailLog;
use App\Models\RequestTable;
use App\Models\RequestClient;
use App\Models\RequestWiseFile;
use App\Mail\SendEmailAddress;
use Illuminate\Support\Facades\Mail;

class PatientDashboardService
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

    /**
     * it stores request in request_client and request table
     *
     * @param mixed $request (input enter by user)
     * @param mixed $email (email of loged in patient)
     *
     * @return object|Users|\Illuminate\Database\Eloquent\Model|null
     */
    public function storeMeRequest($request,$email)
    {
        $isEmailStored = Users::where('email', $email)->first();
        $RequestTable= RequestTable::create([
            'request_type_id'=>1,
            'status'=>1,
            'user_id'=>$isEmailStored->id,
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'email'=>$email,
            'phone_number'=>$request->phone_number,
        ]);

        RequestClient::create([
            'request_id'=>$RequestTable->id,
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'date_of_birth'=>$request->date_of_birth,
            'email'=>$email,
            'phone_number'=>$request->phone_number,
            'street'=>$request->street,
            'city'=> $request->city,
            'state'=>$request->state,
            'zipcode'=>$request->zipcode,
            'notes'=>$request->symptoms,
            'room'=>$request->room,
        ]);

        // store documents in request_wise_file table
        if (isset($request->docs)) {
            $request_file = new RequestWiseFile();
            $request_file->request_id = $RequestTable->id;
            $request_file->file_name = uniqid() . '_' . $request->file('docs')->getClientOriginalName();
            $request->file('docs')->storeAs('public', $request_file->file_name);
            $request_file->save();
        }

        // Generate confirmation number
        $confirmationNumber = $this->generateConfirmationNumber($request);

        // Update confirmation number if request is created successfully
        if ($RequestTable->id) {
            $RequestTable->update(['confirmation_no' => $confirmationNumber]);
        }

        return $isEmailStored;
    }


    /**
     * it stores request in request_client and request table and if user(patient) is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     *
     * @param mixed $request (input enter by user)
     *
     * @return object|Users|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Database\Eloquent\Model|null
     */
    public function storeSomeOneRequest($request)
    {
        $isEmailStored = Users::where('email', $request->email)->first();
        // Store user details if email is not already stored
        if ($isEmailStored === null) {
            $storePatientInUsers = new Users();
            $storePatientInUsers->username = $request->first_name . " " . $request->last_name;
            $storePatientInUsers->email = $request->email;
            $storePatientInUsers->phone_number = $request->phone_number;
            $storePatientInUsers->save();

            $requestInAllUsers = new AllUsers();
            $requestInAllUsers->user_id = $storePatientInUsers->id;
            $requestInAllUsers->fill($request->only([
                'first_name',
                'last_name',
                'email',
                'phone_number',
                'street',
                'city',
                'state',
                'zipcode',
            ]));
            $requestInAllUsers->save();

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
            $request_file = new RequestWiseFile();
            $request_file->request_id = $requestData->id;
            $request_file->file_name = uniqid() . '_' . $request->file('docs')->getClientOriginalName();
            $request->file('docs')->storeAs('public', $request_file->file_name);
            $request_file->save();

        }

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


    /**
     * it update patient profile data in allusers and users table
     *
     * @param mixed $request (input enter by user)
     * @param mixed $userData (loged in patient data)
     *
     * @return bool
     */
    public function patientProfileUpdate($request, $userData){

        $updateInUserTable = [
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'username' => $request->input('first_name') . $request->input('last_name'),
        ];

        $updateInAllUserTable = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('phone_number'),
            'date_of_birth' => $request->input('date_of_birth'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'street' => $request->input('street'),
            'zipcode' => $request->input('zipcode'),
        ];

        Users::where('email', $userData['email'])->update($updateInUserTable);
        AllUsers::where('email', $userData['email'])->update($updateInAllUserTable);

        return true;
    }
}
