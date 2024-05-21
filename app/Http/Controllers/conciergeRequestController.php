<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateConciergeRequest;
use App\Services\ConciergeRequestSubmitService;

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
    public function create(CreateConciergeRequest $request, ConciergeRequestSubmitService $conciergeRequestSubmitService)
    {
        $conciergeRequest = $conciergeRequestSubmitService->storeConciergeRequest($request);
        $redirectMsg = $conciergeRequest ? 'Request is Submitted' : 'Email for Create Account is Sent and Request is Submitted';

        return redirect()->route('submit.request')->with('message', $redirectMsg);
    }
}
