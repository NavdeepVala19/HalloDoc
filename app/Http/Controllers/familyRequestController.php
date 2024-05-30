<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\RequestTable;
use App\Mail\SendEmailAddress;
use App\Services\EmailLogService;
use App\Helpers\ConfirmationNumber;
use Illuminate\Support\Facades\Mail;
use App\Services\CreateNewUserService;
use App\Services\RequestClientService;
use App\Services\RequestWiseFileService;
use App\Http\Requests\CreateFamilyRequest;
use App\Http\Requests\CreatePatientRequest;

// this controller is responsible for creating/storing the family request
class FamilyRequestController extends Controller
{
    /**
     * display family/friend request page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function familyRequests()
    {
        return view('patientSite/familyRequest');
    }

    /**
     * it stores request in request_client and request table and if user(patient) is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(CreateFamilyRequest $request, CreateNewUserService $createNewUserService, RequestClientService $requestClientService, EmailLogService $emailLogService, RequestWiseFileService $requestWiseFileService)
    {
        // check if email already exists in users table
        $isEmailStored = Users::where('email', $request->email)->first();

        $userId = $isEmailStored ? $isEmailStored->id : $createNewUserService->storeNewUser($request);

        // Generate confirmation number
        $confirmationNumber = ConfirmationNumber::generate($request);

        $requestTable = RequestTable::create([
            'user_id' => $userId,
            'request_type_id' => $request->request_type_id,
            'first_name' => $request->family_first_name,
            'last_name' => $request->family_last_name,
            'email' => $request->family_email,
            'phone_number' => $request->family_phone_number,
            'relation_name' => $request->family_relation,
            'confirmation_no' => $confirmationNumber,
            'status' => 1,
        ]);
        // Store client details in RequestClient table
        $requestClientService->createEntry($request, $requestTable->id);

        // Store documents in request_wise_file table
        if ($request->hasFile('docs')) {
            $requestWiseFileService->storeDoc($request, $requestTable->id);
        }
        if (!$isEmailStored) {
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
