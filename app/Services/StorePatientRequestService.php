<?php

namespace App\Services;

use App\Helpers\ConfirmationNumber;
use App\Models\RequestClient;
use App\Models\RequestTable;
use App\Models\RequestWiseFile;
use App\Models\Users;

class StorePatientRequestService
{
    public function storeRequest($request)
    {
        $confirmationNumber = ConfirmationNumber::generateConfirmationNumber($request);

        $userId = Users::where('email', $request->email)->value('id');

        $requestId = $this->storeInRequestTable($request, $userId, $confirmationNumber);
        $this->storeInRequestClientTable($request, $requestId);
        $this->storeImageInRequestWiseFile($request, $requestId);

        return $requestId;
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
}
