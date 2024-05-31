<?php

namespace App\Services;

use App\Helpers\ConfirmationNumber;
use App\Models\Admin;
use App\Models\AllUsers;
use App\Models\RequestClient;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use App\Models\Users;

class AdminCreateRequestService
{
    /**
     * it stores request in request_client and request table and if user is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     */
    public function storeRequest($request)
    {
        $confirmationNumber = ConfirmationNumber::generateConfirmationNumber($request);
        $userId = Users::where('email', $request->email)->value('id');

        $requestId = $this->storeInRequestTable($request, $userId, $confirmationNumber);
        $this->storeInRequestClientTable($request, $requestId);
        $this->storeAdminNotesInRequestNotesTable($request, $requestId);

        return $requestId;
    }

    /**
     * it will return data of admin when route from one page to their profile edit page
     *
     * @param mixed $id (id of user table)
     *
     * @return Admin|object|\Illuminate\Database\Eloquent\Model|null
     */
    public function adminProfile($id)
    {
        return Admin::with('role', 'users', 'region')->where('user_id', $id)->first();
    }

    /**
     * it update admin profile administration information data in admin,allusers and users table
     *
     * @param mixed $request (input enter by user(admin))
     * @param mixed $id (id of user table)
     *
     * @return bool
     */
    public function updateAdminInformation($request, $id)
    {
        $this->updateAdministratorInformation($request, $id);
        $updateUserInfo = Users::where('id', $id)->first();  // update email and phone number in users table
        $updateUserInfo->email = $request->email;
        $updateUserInfo->phone_number = $request->phone_number;
        $updateUserInfo->save();

        return true;
    }

    /**
     * it update admin profile Mailing & Billing Information data in admin and allusers table
     *
     * @param mixed $request (input enter by user(admin))
     * @param mixed $id (id of user table)
     *
     * @return bool
     */
    public function updateAdminMailInformation($request, $id)
    {
        // Update in admin table
        $this->updateMailingInformation($request, $id);
        return true;
    }

    private function updateAdministratorInformation($request, $id)
    {
        $updateAdminInformation = Admin::where('user_id', $id)->first();  // update in Admin Table
        $updateAdminInformation->first_name = $request->first_name;
        $updateAdminInformation->last_name = $request->last_name;
        $updateAdminInformation->email = $request->email;
        $updateAdminInformation->mobile = $request->phone_number;
        $updateAdminInformation->save();

        $updateAdminInfoAllUsers = AllUsers::where('user_id', $id)->first();  // update in AllUsers Table
        $updateAdminInfoAllUsers->first_name = $request->first_name;
        $updateAdminInfoAllUsers->last_name = $request->last_name;
        $updateAdminInfoAllUsers->email = $request->email;
        $updateAdminInfoAllUsers->mobile = $request->phone_number;
        $updateAdminInfoAllUsers->save();
    }

    private function updateMailingInformation($request, $id)
    {
        $updateAdminInformation = Admin::where('user_id', $id)->first(); // update in Admin Table
        $updateAdminInformation->city = $request->city;
        $updateAdminInformation->address1 = $request->address1;
        $updateAdminInformation->address2 = $request->address2;
        $updateAdminInformation->zip = $request->zip;
        $updateAdminInformation->alt_phone = $request->alt_mobile;
        $updateAdminInformation->region_id = $request->select_state;
        $updateAdminInformation->save();

        $updateAdminInfoAllUsers = AllUsers::where('user_id', $id)->first();  // update in AllUsers Table
        $updateAdminInfoAllUsers->city = $request->city;
        $updateAdminInfoAllUsers->street = $request->address1;
        $updateAdminInfoAllUsers->zipcode = $request->zip;
        $updateAdminInfoAllUsers->save();
    }

    private function storeInRequestTable($request, $userId, $confirmationNumber)
    {
        $requestData = new RequestTable();
        $requestData->user_id = $userId;
        $requestData->request_type_id = 1;
        $requestData->status = 1;
        $requestData->confirmation_no = $confirmationNumber;
        $requestData->fill($request->only([
            'first_name',
            'last_name',
            'email',
            'phone_number',
        ]));
        $requestData->save();

        return $requestData->id;
    }

    private function storeInRequestClientTable($request, $requestId)
    {
        $patientRequest = new RequestClient();
        $patientRequest->request_id = $requestId;
        $patientRequest->notes = $request->adminNote;
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
    }

    private function storeAdminNotesInRequestNotesTable($request, $requestId)
    {
        RequestNotes::create([
            'admin_notes' => $request->adminNote,
            'request_id' => $requestId,
        ]);
    }
}
