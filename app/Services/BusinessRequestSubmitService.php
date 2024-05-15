<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Users;
use App\Models\AllUsers;
use App\Models\Business;
use App\Models\EmailLog;
use App\Models\Concierge;
use App\Models\UserRoles;
use App\Models\RequestTable;
use App\Models\RequestClient;
use App\Mail\SendEmailAddress;
use App\Models\RequestBusiness;
use App\Models\RequestConcierge;
use Illuminate\Support\Facades\Mail;

class ConciergeRequestSubmitService
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

    public function storeBusinessRequest($request)
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

        $requestTableData = RequestTable::create([
            'user_id' => $isEmailStored ? $isEmailStored->id : $requestEmail->id,
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
            'street' => $request->concierge_street,
            'city' => $request->concierge_city,
            'state' => $request->concierge_state,
            'zipcode' => $request->concierge_zip_code,
            'symptoms' => $request->symptoms
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

            return $isEmailStored;
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }
}
