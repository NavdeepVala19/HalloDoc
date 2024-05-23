<?php

namespace App\Http\Controllers;

use App\Mail\SendEmailAddress;
use App\Http\Requests\CreateConciergeRequest;
use App\Models\Users;
use App\Services\ConciergeRequestSubmitService;
use App\Services\CreateEmailLogService;
use App\Services\CreateNewUserService;
use Illuminate\Support\Facades\Mail;

// this controller is responsible for creating/storing the concierge request
class ConciergeRequestController extends Controller
{
    /**
     * display concierge request page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function conciergeRequests()
    {
        return view('patientSite/conciergeRequest');
    }

    /**
     * it stores request in request_client and request table and if user(patient) is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(CreateConciergeRequest $request ,ConciergeRequestSubmitService $conciergeRequestSubmitService, CreateNewUserService $createNewUserService, CreateEmailLogService $createEmailLogService)
    {
        $isEmailStored = Users::where('email', $request->email)->first();
        if ($isEmailStored === null) {
            $createNewUserService->storeNewUsers($request);
            try {
                Mail::to($request->email)->send(new SendEmailAddress($request->email));
            } catch (\Throwable $th) {
                return view('errors.500');
            }
        }
        $requestId = $conciergeRequestSubmitService->storeConciergeRequest($request);
        if ($isEmailStored === null) {
            $createEmailLogService->storeEmailLogs($request, $requestId);
        }
        $redirectMsg = $isEmailStored ? 'Request is Submitted' : 'Email for Create Account is Sent and Request is Submitted';

        return redirect()->route('submit.request')->with('message', $redirectMsg);
    }
}
