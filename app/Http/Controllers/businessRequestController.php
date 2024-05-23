<?php

namespace App\Http\Controllers;

use App\Helpers\ConfirmationNumber;
use App\Http\Requests\CreateBusinessRequest;
use App\Mail\SendEmailAddress;
use App\Models\Business;
use App\Models\RequestBusiness;
use App\Models\RequestTable;
use App\Models\Users;
use App\Services\CreateNewUserService;
use App\Services\EmailLogService;
use App\Services\RequestClientService;
use Illuminate\Support\Facades\Mail;

// this controller is responsible for creating/storing the business request
class BusinessRequestController extends Controller
{
    /**
     * display business request page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function businessRequests()
    {
        return view('patientSite/businessRequest');
    }

    /**
     * it stores request in request_client and request table and if user(patient) is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(CreateBusinessRequest $request, CreateNewUserService $createNewUserService, RequestClientService $requestClientService, EmailLogService $emailLogService)
    {
        $isEmailStored = Users::where('email', $request->email)->first();

        $userId = $isEmailStored ? $isEmailStored->id : $createNewUserService->storeNewUser($request);

        // Generate confirmation number
        $confirmationNumber = ConfirmationNumber::generate($request);

        $requestTable = RequestTable::create([
            'user_id' => $userId,
            'request_type_id' => $request->request_type_id,
            'status' => 1,
            'first_name' => $request->business_first_name,
            'last_name' => $request->business_last_name,
            'email' => $request->business_email,
            'phone_number' => $request->business_mobile,
            'case_number' => $request->case_number,
            'confirmation_no' => $confirmationNumber,
        ]);

        // Store client details in RequestClient table
        $requestClientService->createEntry($request, $requestTable->id);

        $business = Business::create([
            'phone_number' => $request->business_mobile,
            'address1' => $request->street,
            'address2' => $request->city,
            'zipcode' => $request->zipcode,
            'business_name' => $request->business_property_name,
        ]);

        RequestBusiness::create([
            'request_id' => $requestTable->id,
            'business_id' => $business->id,
        ]);
        if (! $isEmailStored) {
            // Send email to user
            $emailAddress = $request->email;

            try {
                Mail::to($request->email)->send(new SendEmailAddress($emailAddress));
            } catch (\Throwable $th) {
                return view('errors.500');
            }

            $emailLogService->createEntry($request, $requestTable->id, $confirmationNumber);

            return redirect()->route('submit.request')->with('message', 'Email for Create Account is Sent and Request is Submitted');
        }
        return redirect()->route('submit.request')->with('message', 'Request is Submitted');
    }
}
