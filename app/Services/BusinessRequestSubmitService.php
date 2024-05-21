<?php

namespace App\Services;

use App\Models\Users;
use App\Models\AllUsers;
use App\Models\Business;
use App\Models\EmailLog;
use App\Models\UserRoles;
use App\Models\RequestTable;
use App\Models\RequestClient;
use App\Mail\SendEmailAddress;
use App\Models\RequestBusiness;
use Illuminate\Support\Facades\Mail;

class BusinessRequestSubmitService
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
     * it stores request in request_client and request table and if user(patient) is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     *
     * @param mixed $request (input enter by user)
     *
     * @return object|Users|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Database\Eloquent\Model|null
     */

    public function storeBusinessRequest($request, $createPatientRequest)
    {
        $isEmailStored = Users::where('email', $request->email)->first();

        // Store user details if email is not already stored
        if ($isEmailStored === null) {
            $storePatientInUsers = new Users();
            $storePatientInUsers->username = $createPatientRequest->first_name . " " . $createPatientRequest->last_name;
            $storePatientInUsers->email = $createPatientRequest->email;
            $storePatientInUsers->phone_number = $createPatientRequest->phone_number;
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
            'request_type_id' => 4,
            'status' => 1,
            'first_name' => $request->business_first_name,
            'last_name' => $request->business_last_name,
            'email' => $request->business_email,
            'phone_number' => $request->business_mobile,
            'case_number' => $request->case_number,
        ]);

        RequestClient::create([
            'request_id' => $requestTableData->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'street' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'zipcode' => $request->zipcode,
            'symptoms' => $request->symptoms,
        ]);

        $business = Business::create([
            'phone_number' => $request->business_mobile,
            'address1' => $request->street,
            'address2' => $request->city,
            'zipcode' => $request->zipcode,
            'business_name' => $request->business_property_name,
        ]);

        RequestBusiness::create([
            'request_id' => $requestTableData->id,
            'business_id' => $business->id,
        ]);

        // Generate confirmation number
        $confirmationNumber = $this->generateConfirmationNumber($request);

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
