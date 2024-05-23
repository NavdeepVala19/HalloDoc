<?php

namespace App\Http\Controllers;

use App\Helpers\ConfirmationNumber;
use App\Http\Requests\CreatePatientRequest;
use App\Mail\SendEmailAddress;
use App\Models\Users;
use App\Services\CreateNewUserService;
use App\Services\EmailLogService;
use App\Services\RequestClientService;
use App\Services\RequestTableService;
use App\Services\RequestWiseFileService;
use Illuminate\Support\Facades\Mail;

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
    public function create(CreatePatientRequest $request, CreateNewUserService $createNewUserService, RequestTableService $requestTableService, RequestClientService $requestClientService, EmailLogService $emailLogService, RequestWiseFileService $requestWiseFileService)
    {
        // check if email already exists in users table
        $isEmailStored = Users::where('email', $request->email)->first();

        $userId = $isEmailStored ? $isEmailStored->id : $createNewUserService->storeNewUser($request);

        // Generate confirmation number
        $confirmationNumber = ConfirmationNumber::generate($request);

        $requestTable = $requestTableService->createEntry($request, $userId, $confirmationNumber);
        // Store client details in RequestClient table
        $requestClientService->createEntry($request, $requestTable->id);

        // Store documents in request_wise_file table
        if ($request->hasFile('docs')) {
            $requestWiseFileService->storeDoc($request, $requestTable->id);
        }
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
