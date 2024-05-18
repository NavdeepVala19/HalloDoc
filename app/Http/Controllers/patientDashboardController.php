<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\RequestStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Services\PatientDashboardService;
use App\Http\Requests\CreatePatientRequest;


class patientDashboardController extends Controller
{
    // Display agreement page when clicked through email
    public function viewAgreement($data)
    {
        try {
            $id = Crypt::decrypt($data);
            $clientData = RequestTable::with('requestClient')->where('id', $id)->first();

            // if ($clientData->status == 4 || $clientData->status == 11) {
            if ($clientData->status >= 4) {
                return view('patientSite.agreementDone')->with(['caseStatus' => $clientData->status]);
            }
            if (!empty($clientData)) {
                return view("patientSite/patientAgreement", compact('clientData'));
            }
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    // Agreement Agreed by Patient
    public function agreeAgreement(Request $request)
    {
        $caseStatus = RequestTable::where('id', $request->requestId)->first()->status;
        if ($caseStatus == 4 || $caseStatus == 11) {
            return view('patientSite.agreementDone')->with('caseStatus', $caseStatus);
        }
        $physicianId = RequestTable::where('id', $request->requestId)->first()->physician_id;

        RequestTable::where('id', $request->requestId)->update([
            'status' => 4,
        ]);
        RequestStatus::create([
            'request_id' => $request->requestId,
            'status' => 4,
            'physician_id' => $physicianId,
        ]);

        return redirect()->back()->with('agreementAgreed', 'Agreement Agreed Successfully');
    }

    // Agreeemnt Cancelled by Patient
    public function cancelAgreement(Request $request)
    {
        $caseStatus = RequestTable::where('id', $request->requestId)->first()->status;

        if ($caseStatus == 4 || $caseStatus == 11) {
            return view('patientSite.agreementDone')->with('caseStatus', $caseStatus);
        }
        RequestTable::where('id', $request->requestId)->update([
            'status' => 11,
            'physician_id' => DB::raw("Null"),
            'declined_by' => 'Patient'
        ]);
        RequestStatus::create([
            'request_id' => $request->requestId,
            'status' => 11,
            'physician_id' => DB::raw("Null"),
            'notes' => $request->cancelReason,
        ]);
        return redirect()->back()->with('agreementCancelled', 'Agreement Cancelled Sucessfully');
    }

    /**
     * create me request in patient Dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createNewRequest()
    {
        $userData = Auth::user();
        $email = $userData["email"];

        return view("patientSite/patientNewRequest", compact('email'));
    }

    /**
     *  it stores request in request_client and request table 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createNewPatient(Request $request,PatientDashboardService $patientDashboardService)
    {
        $userData = Auth::user();
        $email = $userData["email"];

        $request->validate([
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'date_of_birth' => 'required|before:today',
            'phone_number' => 'required',
            'street' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9\s,_-]+?$/',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'state' => 'min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'zipcode' => 'digits:6|gte:1',
            'docs' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc,docx|max:2048',
            'symptoms' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 \-_,()]+$/',
            'room' => 'gte:1|nullable|max:1000'
        ]);


        $meRequestStored = $patientDashboardService->storeMeRequest($request,$email);
        return redirect()->route('patient.dashboard')->with('message', 'Request is Submitted');

    }


    /**
     * create someone else request from patient dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createSomeoneRequest()
    {
        return view("patientSite/patientSomeoneRequest");
    }


    /**
     * it stores request in request_client and request table and if user(patient) is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */

    public function createSomeOneElseRequest(CreatePatientRequest $request , PatientDashboardService $patientDashboardService)
    {

        $patientRequest = $patientDashboardService->storeSomeOneRequest($request);
        $redirectMsg = $patientRequest ? 'Request is Submitted' : 'Email for Create Account is Sent and Request is Submitted';

        return redirect()->route('patient.dashboard')->with('message', $redirectMsg);
     
    }


    /**
     * when patient login after creating account he/she will land to dashboard page,
     * which shows request created date ,request status and show if document is uploaded or not
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function patientDashboard()
    {
        $userData = Auth::user();
        $email = $userData["email"];

        $userId = Users::select('id')->where('email', $email);
        $data = RequestTable::with('requestWiseFile')->where('user_id', $userId)->orderBy('id', 'desc')->paginate(10);

        return view('patientSite/patientDashboard', compact('data', 'userData'));
    }
}
