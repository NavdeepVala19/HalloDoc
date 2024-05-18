<?php

namespace App\Services;

use App\Models\UserRoles;
use Carbon\Carbon;
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
     * @param mixed $request
     * @return string
     */
    private function generateConfirmationNumber($request)
    {
        $currentTime = Carbon::now();
        $currentDate = $currentTime->format('Y');
        $todayDate = $currentTime->format('Y-m-d');
        $entriesCount = RequestTable::whereDate('created_at', $todayDate)->count();

        $uppercaseStateAbbr = strtoupper(substr($request->state, 0, 2));
        $uppercaseLastName = strtoupper(substr($request->last_name, 0, 2));
        $uppercaseFirstName = strtoupper(substr($request->first_name, 0, 2));

        return $uppercaseStateAbbr . $currentDate . $uppercaseLastName . $uppercaseFirstName  . '00' . $entriesCount;
    }

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

    public function storeSomeOneRequest($request)
    {
        $isEmailStored = Users::where('email', $request->email)->first();
        // Store user details if email is not already stored
        if ($isEmailStored == null) {
            $requestEmail = new Users();
            $requestEmail->username = $request->first_name . " " . $request->last_name;
            $requestEmail->email = $request->email;
            $requestEmail->phone_number = $request->phone_number;
            $requestEmail->save();

            $requestUsers = new AllUsers();
            $requestUsers->user_id = $requestEmail->id;
            $requestUsers->fill($request->only([
                'first_name',
                'last_name',
                'email',
                'phone_number',
                'street',
                'city',
                'state',
                'zipcode'
            ]));
            $requestUsers->save();

            $userRolesEntry = new UserRoles();
            $userRolesEntry->role_id = 3;
            $userRolesEntry->user_id = $requestEmail->id;
            $userRolesEntry->save();    
        }
        
        $requestData = new RequestTable();
        $requestData->user_id = $isEmailStored ? $isEmailStored->id : $requestEmail->id;
        $requestData->request_type_id = 1;
        $requestData->status = 1;
        $requestData->fill($request->only([
            'first_name',
            'last_name',
            'email',
            'phone_number'
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
            'symptoms'
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
            if ($isEmailStored == null) {
                $emailAddress = $request->email;
                Mail::to($request->email)->send(new SendEmailAddress($emailAddress));

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
