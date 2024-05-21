<?php

namespace App\Services;

use App\Helpers\ConfirmationNumber;
use App\Mail\SendEmailAddress;
use App\Models\AllUsers;
use App\Models\Concierge;
use App\Models\EmailLog;
use App\Models\RequestClient;
use App\Models\RequestConcierge;
use App\Models\RequestTable;
use App\Models\UserRoles;
use App\Models\Users;
use Illuminate\Support\Facades\Mail;

class ConciergeRequestSubmitService
{
    /**
     * it stores request in request_client and request table and if user(patient) is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     *
     * @param mixed $request (input enter by user)
     *
     * @return object|Users|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Database\Eloquent\Model|null
     */
    public function storeConciergeRequest($request)
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

        $requestTableData = RequestTable::create([
            'user_id' => $isEmailStored ? $isEmailStored->id : $storePatientInUsers->id,
            'request_type_id' => 3,
            'first_name' => $request->concierge_first_name,
            'last_name' => $request->concierge_last_name,
            'email' => $request->concierge_email,
            'phone_number' => $request->concierge_mobile,
            'status' => 1,
        ]);

        RequestClient::create([
            'first_name',
            'request_id' => $requestTableData->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'street' => $request->concierge_street,
            'city' => $request->concierge_city,
            'state' => $request->concierge_state,
            'zipcode' => $request->concierge_zip_code,
            'symptoms' => $request->symptoms,
        ]);

        $concierge = Concierge::create([
            'name' => $request->concierge_first_name,
            'address' => $request->concierge_hotel_name,
            'street' => $request->concierge_street,
            'city' => $request->concierge_city,
            'state' => $request->concierge_state,
            'zipcode' => $request->concierge_zip_code,
            'role_id' => 3,
        ]);

        RequestConcierge::create([
            'request_id' => $requestTableData->id,
            'concierge_id' => $concierge->id,
        ]);

        // Generate confirmation number
        // $confirmationNumber = $this->generateConfirmationNumber($request);
        $confirmationNumber = ConfirmationNumber::generate($request);

        // Update confirmation number if request is created successfully
        if ($requestTableData->id) {
            $requestTableData->update(['confirmation_no' => $confirmationNumber]);
        }

        try {
            // Send email if email is not already stored
            if ($isEmailStored === null) {
                $emailAddress = $request->email;
                Mail::to($emailAddress)->send(new SendEmailAddress($emailAddress));

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

            return $isEmailStored;
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }
}
