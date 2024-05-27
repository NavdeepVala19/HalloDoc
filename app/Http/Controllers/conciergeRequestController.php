<?php

namespace App\Http\Controllers;

use App\Helpers\ConfirmationNumber;
use App\Http\Requests\CreateConciergeRequest;
use App\Mail\SendEmailAddress;
use App\Models\Concierge;
use App\Models\RequestClient;
use App\Models\RequestConcierge;
use App\Models\RequestTable;
use App\Models\Users;
use App\Services\CreateNewUserService;
use App\Services\EmailLogService;
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
    public function create(CreateConciergeRequest $request, CreateNewUserService $createNewUserService, EmailLogService $emailLogService)
    {
        $isEmailStored = Users::where('email', $request->email)->first();

        $userId = $isEmailStored ? $isEmailStored->id : $createNewUserService->storeNewUser($request);

        // Generate confirmation number
        $confirmationNumber = ConfirmationNumber::generate($request);

        $requestTable = RequestTable::create([
            'user_id' => $userId,
            'request_type_id' => $request->request_type_id,
            'first_name' => $request->concierge_first_name,
            'last_name' => $request->concierge_last_name,
            'email' => $request->concierge_email,
            'phone_number' => $request->concierge_mobile,
            'status' => 1,
        ]);

        // Store client details in RequestClient table
        RequestClient::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'room' => $request->room,
            'request_id' => $requestTable->id,
            'notes' => $request->symptoms,
            'street' => $request->concierge_street,
            'city' => $request->concierge_city,
            'state' => $request->concierge_state,
            'zipcode' => $request->concierge_zip_code,
            'location' => $request->concierge_hotel_name,
        ]);

        $concierge = Concierge::create([
            'name' => $request->concierge_first_name,
            'address' => $request->concierge_hotel_name,
            'street' => $request->concierge_street,
            'city' => $request->concierge_city,
            'state' => $request->concierge_state,
            'zipcode' => $request->concierge_zip_code,
            'role_id' => 3,
        ]);

        RequestConcierge::create([
            'request_id' => $requestTable->id,
            'concierge_id' => $concierge->id,
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
