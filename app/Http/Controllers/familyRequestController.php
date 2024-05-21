<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFamilyRequest;
use App\Http\Requests\CreatePatientRequest;
use App\Services\FamilyRequestSubmitService;

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
    public function create(CreatePatientRequest $request , CreateFamilyRequest $createFamilyRequest, FamilyRequestSubmitService $familyRequestSubmitService)
    {
        $familyRequest = $familyRequestSubmitService->storeRequest($request, $createFamilyRequest);
        $redirectMsg = $familyRequest ? 'Request is Submitted' : 'Email for Create Account is Sent and Request is Submitted';

        return redirect()->route('submit.request')->with('message', $redirectMsg);
    }
}
