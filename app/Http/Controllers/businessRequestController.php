<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Mail\SendEmailAddress;
use App\Services\CreateNewUserService;
use App\Services\CreateEmailLogService;
use App\Http\Requests\CreatePatientRequest;
use App\Http\Requests\CreateBusinessRequest;
use App\Services\BusinessRequestSubmitService;
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
  public function create(CreateBusinessRequest $request, CreatePatientRequest $createPatientRequest, BusinessRequestSubmitService $businessRequestSubmitService, CreateEmailLogService $createEmailLogService, CreateNewUserService $createNewUserService)
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
    $requestId = $businessRequestSubmitService->storeBusinessRequest($createPatientRequest);
    if ($isEmailStored === null) {
      $createEmailLogService->storeEmailLogs($request, $requestId);
    }
    $redirectMsg = $isEmailStored ? 'Request is Submitted' : 'Email for Create Account is Sent and Request is Submitted';

    return redirect()->route('submit.request')->with('message', $redirectMsg);
  }
}
