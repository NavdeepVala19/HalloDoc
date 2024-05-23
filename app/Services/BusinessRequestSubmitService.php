<?php

namespace App\Services;

use App\Models\Users;
use App\Models\Business;
use App\Models\RequestTable;
use App\Models\RequestClient;
use App\Models\RequestBusiness;
use App\Helpers\ConfirmationNumber;

class BusinessRequestSubmitService
{
    private function storeInRequestTable($request, $isEmailStored, $confirmationNumber)
    {
        $requestData = RequestTable::create([
            'user_id' => $isEmailStored->id,
            'request_type_id' => 4,
            'status' => 1,
            'first_name' => $request->business_first_name,
            'last_name' => $request->business_last_name,
            'email' => $request->business_email,
            'phone_number' => $request->business_mobile,
            'case_number' => $request->case_number,
            'confirmation_no' => $confirmationNumber,
        ]);

        return $requestData->id;
    }

    private function storeInRequestClientTable($request, $requestId)
    {
        RequestClient::create([
            'request_id' =>  $requestId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'street' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'zipcode' => $request->zipcode,
            'notes' => $request->symptoms,
        ]);
    }

    private function storeInBusinessTable($request){
        $business = Business::create([
            'phone_number' => $request->business_mobile,
            'address1' => $request->street,
            'address2' => $request->city,
            'zipcode' => $request->zipcode,
            'business_name' => $request->business_property_name,
        ]);

        return $business->id;
    }


    private function storeInRequestBusiness($requestId, $businessId){
        RequestBusiness::create([
            'request_id' => $requestId,
            'business_id' => $businessId,
        ]);
    }


    /**
     * it stores request in request_client and request table and if user(patient) is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     *
     * @param mixed $request (input enter by user)
     *
     * @return object|Users|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Database\Eloquent\Model|null
     */

    public function storeBusinessRequest($request)
    {
        $confirmationNumber = ConfirmationNumber::generateConfirmationNumber($request);
        $isEmailStored = Users::where('email', $request->email)->first();

        $requestId = $this->storeInRequestTable($request, $isEmailStored, $confirmationNumber);
        $this->storeInRequestClientTable($request, $requestId);
        $businessId = $this->storeInBusinessTable($request);
        $this->storeInRequestBusiness($requestId, $businessId);

        return $requestId;
    }
}
