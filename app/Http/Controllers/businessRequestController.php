<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BusinessRequest;
use App\Http\Requests\CreateBusinessRequest;
use App\Http\Requests\CreatePatientRequest;
use App\Services\BusinessRequestSubmitService;


// this controller is responsible for creating/storing the business request
class businessRequestController extends Controller
{

  /**
   * display business request page
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
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
   */

  public function create(CreateBusinessRequest $request ,CreatePatientRequest $createPatientRequest, BusinessRequestSubmitService $businessRequestSubmitService)
  {
    $businessRequest = $businessRequestSubmitService->storeBusinessRequest($request);
    $redirectMsg = $businessRequest ? 'Request is Submitted' : 'Email for Create Account is Sent and Request is Submitted';

    return redirect()->route('submit.request')->with('message', $redirectMsg);

  }
}
