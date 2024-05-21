<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePatientRequest;
use App\Services\PatientRequestSubmitService;

/**
 * controller is responsible for display patient request page and creating the patient request
 */
class PatientController extends Controller
{
    /**
     * display patient request form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function patientRequests()
    {
        return view('patientSite/patientRequest');
    }

    /**
     * stores request in request_client and request table and if user is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */

    public function create(CreatePatientRequest $request, PatientRequestSubmitService $requestSubmitService)
    {
        $patientRequest = $requestSubmitService->storeRequest($request);
        $redirectMsg = $patientRequest ? 'Request is Submitted' : 'Email for Create Account is Sent and Request is Submitted';

        return redirect()->route('submit.request')->with('message', $redirectMsg);
    }
}
