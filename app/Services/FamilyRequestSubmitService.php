<?php

namespace App\Services;

use App\Helpers\ConfirmationNumber;
use App\Models\RequestClient;
use App\Models\RequestTable;
use App\Models\RequestWiseFile;
use App\Models\Users;

class FamilyRequestSubmitService
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
        $confirmationNumber = ConfirmationNumber::generateConfirmationNumber($request);
        $userId = Users::where('email', $request->email)->value('id');

        $requestId = $this->storeInRequestTable($request, $userId, $confirmationNumber);
        $this->storeInRequestClient($request, $requestId);
        $this->storeInRequestWiseFile($request, $requestId);

        return $requestId;
    }
    private function storeInRequestTable($request, $userId, $confirmationNumber)
    {
        $requestTable = RequestTable::create([
            'user_id' => $userId,
            'request_type_id' => 2,
            'status' => 1,
            'first_name' => $request->family_first_name,
            'last_name' => $request->family_first_name,
            'email' => $request->family_first_name,
            'phone_number' => $request->family_first_name,
            'relation_name' => $request->family_first_name,
            'confirmation_no' => $confirmationNumber,
        ]);

        return $requestTable->id;
    }

    private function storeInRequestClient($request, $requestId)
    {
        $patientRequest = new RequestClient();
        $patientRequest->request_id = $requestId;
        $patientRequest->notes = $request->symptoms;
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

    private function storeInRequestWiseFile($request, $requestId)
    {
        // Store documents in request_wise_file table
        if ($request->hasFile('docs')) {
            $requestFile = new RequestWiseFile();
            $requestFile->request_id = $requestId;
            $requestFile->file_name = uniqid() . '_' . $request->file('docs')->getClientOriginalName();
            $request->file('docs')->storeAs('public', $requestFile->file_name);
            $requestFile->save();
        }
    }
}
