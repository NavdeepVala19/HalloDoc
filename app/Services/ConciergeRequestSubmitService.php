<?php

namespace App\Services;

use App\Helpers\ConfirmationNumber;
use App\Models\Concierge;
use App\Models\RequestClient;
use App\Models\RequestConcierge;
use App\Models\RequestTable;
use App\Models\Users;

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
        $confirmationNumber = ConfirmationNumber::generateConfirmationNumber($request);
        $isEmailStored = Users::where('email', $request->email)->first();

        $requestId = $this->storeInRequestTable($request, $isEmailStored, $confirmationNumber);
        $this->storeInRequestClientTable($request, $requestId);
        $conciergeId = $this->storeInConciergeTable($request);
        $this->storeInRequestConcierge($requestId, $conciergeId);

        return $requestId;
    }
    private function storeInRequestTable($request, $isEmailStored, $confirmationNumber)
    {
        $requestData = new RequestTable();
        $requestData->user_id = $isEmailStored->id;
        $requestData->request_type_id = 3;
        $requestData->status = 1;
        $requestData->confirmation_no = $confirmationNumber;
        $requestData->first_name = $request->concierge_first_name;
        $requestData->last_name = $request->concierge_last_name;
        $requestData->email = $request->concierge_email;
        $requestData->phone_number = $request->concierge_mobile;
        $requestData->save();

        return $requestData->id;
    }

    private function storeInRequestClientTable($request, $requestId)
    {
        $patientRequest = new RequestClient();
        $patientRequest->request_id = $requestId;
        $patientRequest->notes = $request->symptoms;
        $patientRequest->street = $request->concierge_street;
        $patientRequest->city = $request->concierge_city;
        $patientRequest->state = $request->concierge_state;
        $patientRequest->zipcode = $request->concierge_zip_code;
        $patientRequest->location = $request->concierge_hotel_name;
        $patientRequest->fill($request->only([
            'first_name',
            'last_name',
            'date_of_birth',
            'email',
            'phone_number',
            'room',
        ]));
        $patientRequest->save();
    }

    private function storeInConciergeTable($request)
    {
        $concierge = Concierge::create([
            'name' => $request->concierge_first_name,
            'address' => $request->concierge_hotel_name,
            'street' => $request->concierge_street,
            'city' => $request->concierge_city,
            'state' => $request->concierge_state,
            'zipcode' => $request->concierge_zip_code,
            'role_id' => 3,
        ]);

        return $concierge->id;
    }

    private function storeInRequestConcierge($requestId, $conciergeId)
    {
        RequestConcierge::create([
            'request_id' => $requestId,
            'concierge_id' => $conciergeId,
        ]);
    }
}
