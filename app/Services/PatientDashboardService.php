<?php

namespace App\Services;

use App\Helpers\ConfirmationNumber;
use App\Models\AllUsers;
use App\Models\RequestClient;
use App\Models\RequestTable;
use App\Models\RequestWiseFile;
use App\Models\Users;

class PatientDashboardService
{
    private function storeInRequestTable($request, $isEmailStored, $confirmationNumber)
    {
        $requestData = new RequestTable();
        $requestData->user_id =  $isEmailStored->id;
        $requestData->request_type_id = 1;
        $requestData->status = 1;
        $requestData->confirmation_no = $confirmationNumber;
        $requestData->relation_name = $request->relation;
        $requestData->fill($request->only([
            'first_name',
            'last_name',
            'email',
            'phone_number',
        ]));
        $requestData->save();

        return $requestData->id;
    }

    private function storeInRequestClientTable($request, $requestId,$email)
    {
        $patientRequest = new RequestClient();
        $patientRequest->request_id = $requestId;
        $patientRequest->notes = $request->symptoms;
        $patientRequest->email = $email;
        $patientRequest->fill($request->only([
            'first_name',
            'last_name',
            'date_of_birth',
            'phone_number',
            'street',
            'city',
            'state',
            'zipcode',
            'room',
        ]));
        $patientRequest->save();
    }

    private function storeImageInRequestWiseFile($request, $requestId)
    {
        if ($request->hasFile('docs')) {
            $requestFile = new RequestWiseFile();
            $requestFile->request_id = $requestId;
            $requestFile->file_name = uniqid() . '_' . $request->file('docs')->getClientOriginalName();
            $request->file('docs')->storeAs('public', $requestFile->file_name);
            $requestFile->save();
        }
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
        $confirmationNumber = ConfirmationNumber::generateConfirmationNumber($request);
        $isEmailStored = Users::where('email', $email)->first();

        $requestId = $this->storeInRequestTable($request, $isEmailStored, $confirmationNumber);
        $this->storeInRequestClientTable($request, $requestId, $email);
        $this->storeImageInRequestWiseFile($request, $requestId);

        return $requestId;
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
        $confirmationNumber = ConfirmationNumber::generateConfirmationNumber($request);
        $isEmailStored = Users::where('email', $request->email)->first();

        $requestId = $this->storeInRequestTable($request, $isEmailStored, $confirmationNumber);
        $this->storeInRequestClientTable($request, $requestId, $request->email);
        $this->storeImageInRequestWiseFile($request, $requestId);

        return $requestId;
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
