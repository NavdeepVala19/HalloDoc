<?php

namespace App\Services;

use App\Helpers\ConfirmationNumber;
use App\Models\RequestClient;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use App\Models\Users;

class ProviderCreateRequestService
{
    public function storeRequest($request, $providerId)
    {
        $confirmationNumber = ConfirmationNumber::generateConfirmationNumber($request);
        $isEmailStored = Users::where('email', $request->email)->first();

        $requestId = $this->storeInRequestTable($request, $isEmailStored, $confirmationNumber, $providerId);
        $this->storeInRequestClientTable($request, $requestId);
        $this->storeAdminNotesInRequestNotesTable($request, $requestId);

        return $requestId;
    }
    private function storeInRequestTable($request, $isEmailStored, $confirmationNumber, $providerId)
    {
        $requestData = new RequestTable();
        $requestData->request_type_id = 1;
        $requestData->status = 3;
        $requestData->user_id = $isEmailStored->id;
        $requestData->physician_id = $providerId;
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

    private function storeAdminNotesInRequestNotesTable($request, $requestId)
    {
        // Store notes in RequestNotes table
        $requestNotes = new RequestNotes();
        $requestNotes->request_id = $requestId;
        $requestNotes->physician_notes = $request->note;
        $requestNotes->created_by = 'physician';
        $requestNotes->save();
    }
}
