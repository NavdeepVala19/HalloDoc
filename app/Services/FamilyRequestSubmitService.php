<?php

namespace App\Services;
use Carbon\Carbon;
use App\Models\Users;
use App\Models\AllUsers;
use App\Models\EmailLog;
use App\Models\UserRoles;
use App\Models\RequestTable;
use App\Models\RequestClient;
use App\Mail\SendEmailAddress;
use App\Models\RequestWiseFile;
use Illuminate\Support\Facades\Mail;



class FamilyRequestSubmitService
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

    public function storeRequest($request)
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

       $requestTableData= RequestTable::create([
            'user_id'=> $isEmailStored ? $isEmailStored->id : $requestEmail->id,
            'request_type_id'=> 2,
            'first_name'=> $request->family_first_name,
            'last_name'=> $request->family_first_name,
            'email'=> $request->family_first_name,
            'phone_number'=> $request->family_first_name,
            'relation_name'=> $request->family_first_name,
            'status'=> 1,
        ]);

        $patientRequest = new RequestClient();
        $patientRequest->request_id = $requestTableData->id;
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
            $request_file->request_id = $requestTableData->id;
            $request_file->file_name = uniqid() . '_' . $request->file('docs')->getClientOriginalName();
            $request->file('docs')->storeAs('public', $request_file->file_name);
            $request_file->save();
        }

        // Generate confirmation number
        $confirmationNumber = $this->generateConfirmationNumber($request);

        // Update confirmation number if request is created successfully
        if ($requestTableData->id) {
            $requestTableData->update(['confirmation_no' => $confirmationNumber]);
        }

        try {
            // Send email if email is not already stored
            if ($isEmailStored == null) {
                $emailAddress = $request->email;
                Mail::to($request->email)->send(new SendEmailAddress($emailAddress));

                EmailLog::create([
                    'role_id' => 3,
                    'request_id' => $requestTableData->id,
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
        } catch (\Throwable $th) {
            return view('errors.500');
        }
        return $isEmailStored;
    }
}
