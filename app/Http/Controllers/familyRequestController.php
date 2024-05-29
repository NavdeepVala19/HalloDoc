<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFamilyRequest;
use App\Http\Requests\CreatePatientRequest;
use App\Mail\SendEmailAddress;
use App\Models\Users;
use App\Services\CreateEmailLogService;
use App\Services\CreateNewUserService;
use App\Services\FamilyRequestSubmitService;
use Illuminate\Support\Facades\Mail;

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
    public function create(CreateFamilyRequest $request, CreateNewUserService $createNewUserService, FamilyRequestSubmitService $familyRequestSubmitService, CreateEmailLogService $createEmailLogService)
    {
        $isEmailStored = Users::where('email', $request->email)->first();
        $requestId = $familyRequestSubmitService->storeRequest($request);
        if ($isEmailStored === null) {
            $createNewUserService->storeNewUsers($request);
            try {
                Mail::to($request->email)->send(new SendEmailAddress($request->email));
                $createEmailLogService->storeEmailLogs($request, $requestId);
            } catch (\Throwable $th) {
                return view('errors.500');
            }
        }
        $redirectMsg = $isEmailStored ? 'Request is Submitted' : 'Email for Create Account is Sent and Request is Submitted';
        return redirect()->route('submit.request')->with('message', $redirectMsg);
    }
}
