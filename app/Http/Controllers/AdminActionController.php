<?php

namespace App\Http\Controllers;

use App\Models\BlockRequest;
use App\Models\Orders;
use App\Models\CaseTag;
use App\Models\Regions;
use App\Models\Provider;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use App\Models\MedicalReport;
use App\Models\RequestClosed;
use App\Models\RequestStatus;
use App\Models\RequestClient;
use App\Models\RequestWiseFile;
use App\Models\PhysicianRegion;
use App\Models\HealthProfessional;
use App\Models\HealthProfessionalType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AdminActionController extends Controller
{
    /**
     * Assign case - All physician Regions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function physicianRegions()
    {
        $regions = Regions::get();
        return response()->json($regions);
    }

    /**
     * AJAX call for Physician listing in dropdown selection
     *
     * @param  int|null  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPhysicians($id = null)
    {
        $physiciansId = PhysicianRegion::where('region_id', $id)->pluck('provider_id')->toArray();
        $physicians = Provider::whereIn('id', $physiciansId)->get()->toArray();
        return response()->json($physicians);
    }

    /**
     * AJAX call for (Remaining) Physician for listing in dropdown selection
     *
     * @param  int  $requestId
     * @param  int  $regionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNewPhysicians($requestId, $regionId)
    {
        $oldPhysicianId = RequestTable::where('id', $requestId)->where('status', 3)->orderByDesc('id')->first()->physician_id;
        $physiciansId = PhysicianRegion::where('region_id', $regionId)->pluck('provider_id')->toArray();
        $physicians = Provider::whereIn('id', $physiciansId)->whereNot('id', $oldPhysicianId)->get()->toArray();
        return response()->json($physicians);
    }

    /**
     * Assign a case to a provider (physician).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignCase(Request $request)
    {
        $request->validate([
            'physician' => 'required|numeric',
            'assign_note' => 'required|min:5|max:200'

        ]);
        RequestTable::where('id', $request->requestId)->update(['physician_id' => $request->physician]);
        RequestStatus::create([
            'request_id' => $request->requestId,
            'TransToPhysicianId' => $request->physician,
            'status' => 1,
            'admin_id' => 1,
            'notes' => $request->assign_note
        ]);

        $physician = Provider::where('id', $request->physician)->first();
        $physicianName = $physician->first_name . " " . $physician->last_name;
        return redirect()->back()->with('successMessage', "Case Assigned Successfully to physician - {$physicianName}");
    }

    /**
     * Admin transfer a case to another physician.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function transferCase(Request $request)
    {
        $request->validate([
            'physician' => 'required|numeric',
            'notes' => 'required|min:5|max:200'
        ]);

        $providerId = RequestTable::where('id', $request->requestId)->first()->physician_id;
        RequestStatus::create([
            'request_id' => $request->requestId,
            'physician_id' => $providerId,
            'TransToPhysicianId' => $request->physician,
            'status' => "3",
            'admin_id' => '1',
            'notes' => $request->notes
        ]);
        RequestTable::where('id', $request->requestId)->update([
            'status' => 3,
            'physician_id' => $request->physician
        ]);
        return redirect()->back()->with('successMessage', 'Case Transferred to Another Physician');
    }

    /**
     * Fetch all case tag data from its table and show in cancel case popup.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelCaseOptions()
    {
        $reasons = CaseTag::all();
        return response()->json($reasons);
    }

    /**
     * Store cancel case request_id, status(cancelled), adminId, & Notes(reason) in requestStatusLog.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelCase(Request $request)
    {
        $request->validate([
            'case_tag' => 'required|in:1,2,3,4',
            'reason' => 'nullable|min:5|max:200'
        ]);
        RequestTable::where('id', $request->requestId)->update([
            'status' => 2,
            'case_tag' => $request->case_tag
        ]);
        RequestStatus::create([
            'request_id' => $request->requestId,
            'status' => '2',
            'notes' => $request->reason
        ]);

        return redirect()->back()->with('successMessage', 'Case Cancelled (Moved to ToClose State)');
    }

    /**
     * Admin Blocks patient.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function blockCase(Request $request)
    {
        $request->validate([
            'block_reason' => 'required|min:5|max:200'
        ]);

        // Block patient phone number, email, requestId and reason given by admin stored in block_request table
        $client = RequestClient::where('request_id', $request->requestId)->first();
        BlockRequest::create([
            'request_id' => $request->requestId,
            'reason' => $request->block_reason,
            'phone_number' => $client->phone_number,
            'email' => $client->email
        ]);
        RequestTable::where('id', $request->requestId)->update([
            'status' => 10,
        ]);
        RequestStatus::create([
            'request_id' => $request->requestId,
            'status' => 10,
            'notes' => $request->block_reason,
        ]);
        return redirect()->back()->with('successMessage', 'Case Blocked Successfully!');
    }

    /**
     * View a case.
     *
     * @param  string  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewCase($id)
    {
        try {
            $requestId = Crypt::decrypt($id);
            $data = RequestTable::where('id', $requestId)->first();

            return view('adminPage.pages.viewCase', compact('data'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * Edit case information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editCase(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'dob' => 'required',
        ]);


        $firstName = $request->first_name;
        $lastName = $request->last_name;
        $dateOfBirth = $request->dob;
        $patientNotes = $request->patient_notes;

        RequestTable::where('id', $request->requestId)->where('request_type_id', 1)->update([
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);

        RequestClient::where('request_id', $request->requestId)->update([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'date_of_birth' => $dateOfBirth,
            'notes' => $patientNotes
        ]);

        return redirect()->back()->with('caseEdited', "Information updated successfully!");
    }

    /**
     * View notes for a case.
     *
     * @param  string $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewNote($id)
    {
        try {
            $requestId = Crypt::decrypt($id);

            $data = RequestTable::where('id', $requestId)->first();

            $note = RequestNotes::where('request_id', $requestId)->first();
            $adminAssignedCase = RequestStatus::with('transferedPhysician')->where('request_id', $requestId)->where('status', 1)->whereNotNull('TransToPhysicianId')->orderByDesc('id')->first();
            $providerTransferedCase = RequestStatus::with('provider')->where('request_id', $requestId)->where('status', 1)->where('TransToAdmin', true)->orderByDesc('id')->first();
            $adminTransferedCase = RequestStatus::with('transferedPhysician')->where('request_id', $requestId)->where('admin_id', 1)->where('status', 3)->whereNotNull('TransToPhysicianId')->orderByDesc('id')->first();
            return view('adminPage.pages.viewNotes', compact('requestId', 'note', 'adminAssignedCase', 'providerTransferedCase', 'adminTransferedCase', 'data'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * Store an admin note to display in the ViewNotes page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeNote(Request $request)
    {
        $request->validate([
            'admin_note' => 'required||min:5|max:200'
        ]);
        $requestNote = RequestNotes::where('request_id', $request->requestId)->first();
        // if (!empty($requestNote)) {
        if ($requestNote) {
            RequestNotes::where('request_id', $request->requestId)->update([
                'admin_notes' => $request->admin_note,
            ]);
        } else {
            RequestNotes::create([
                'request_id' => $request->requestId,
                'admin_notes' => $request->admin_note,
            ]);
        }

        $id = $request->requestId;
        $id = Crypt::encrypt($id);

        return redirect()->route('admin.view.note', compact('id'))->with('adminNoteAdded', 'Your Note Successfully Added');
    }

    /**
     * Display the view upload page with the data.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function viewUpload($id)
    {
        try {
            $requestId = Crypt::decrypt($id);
            $data  = requestTable::where('id', $requestId)->first();
            $documents = RequestWiseFile::where('request_id', $requestId)->orderByDesc('id')->paginate(10);
            return view('adminPage.pages.viewUploads', compact('data', 'documents'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * Upload a document from the view upload page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadDocument(Request $request, $id = null)
    {
        $request->validate([
            'document' => 'required|mimes:png,jpg,jpeg,doc,docx,pdf|max:5242880'
        ], [
            'document.required' => 'Select an File to upload!'
        ]);
        $fileName = uniqid() . '_' . $request->file('document')->getClientOriginalName();

        $request->file('document')->storeAs('public', $fileName);

        RequestWiseFile::create([
            'request_id' => $id,
            'file_name' => $fileName,
            'admin_id' => 1,
        ]);

        return redirect()->back()->with('uploadSuccessful', "File Uploaded Successfully");
    }

    /**
     * Show a new medical form or an existing one when the encounter button is clicked in the conclude listing.
     *
     * @param  string|null  $id
     * @return \Illuminate\View\View
     */
    public function encounterFormView($id = "null")
    {
        try {
            $requestId = Crypt::decrypt($id);

            $data = MedicalReport::where('request_id', $requestId)->first();
            $requestData = RequestTable::where('id', $requestId)->first();
            return view('adminPage.adminEncounterForm', compact('data', 'requestId', 'requestData'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * Store Encounter Form (Medical Form) data, changes made by admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function encounterForm(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'location' => 'required',
            'date_of_birth' => 'required',
            'service_date' => 'required',
            'mobile' => 'required',
            'allergies' => 'required|min:5|max:200',
            'treatment_plan' => 'required|min:5|max:200',
            'medication_dispensed' => 'required|min:5|max:200',
            'procedure' => 'required|min:5|max:200',
            'followUp' => 'required|min:5|max:200',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
        ]);

        $report = MedicalReport::where("request_id", $request->request_id)->first();

        $array = [
            'request_id' => $request->request_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'location' => $request->location,
            'service_date' => $request->service_date,
            'date_of_birth' => $request->date_of_birth,
            'mobile' => $request->mobile,
            'present_illness_history' => $request->present_illness_history,
            'medical_history' => $request->medical_history,
            'medications' => $request->medications,
            'allergies' => $request->allergies,
            'temperature' => $request->temperature,
            'heart_rate' => $request->heart_rate,
            'repository_rate' => $request->repository_rate,
            'sis_BP' => $request->sis_BP,
            'dia_BP' => $request->dia_BP,
            'oxygen' => $request->oxygen,
            'pain' => $request->pain,
            'heent' => $request->heent,
            'cv' => $request->cv,
            'chest' => $request->chest,
            'abd' => $request->abd,
            'extr' => $request->extr,
            'skin' => $request->skin,
            'neuro' => $request->neuro,
            'other' => $request->other,
            'diagnosis' => $request->diagnosis,
            'treatment_plan' => $request->treatment_plan,
            'medication_dispensed' => $request->medication_dispensed,
            'procedure' => $request->procedure,
            'followUp' => $request->followUp,
            'is_finalize' => false
        ];
        $medicalReport = new MedicalReport();
        if ($report) {
            // Report Already exists, update report
            $report->update($array);
        } else {
            // Report does'nt exists, insert a new entry
            $medicalReport->create($array);
        }

        return redirect()->back()->with('encounterChangesSaved', "Your changes have been Successfully Saved");
    }

    /**
     * Clear Case - Change status for a particular case to "Clear".
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearCase(Request $request)
    {
        RequestTable::where('id', $request->requestId)->update([
            'status' => 8,
        ]);
        RequestStatus::create([
            'request_id' => $request->requestId,
            'status' => 8,
        ]);
        return redirect()->back()->with('successMessage', 'Case Cleared Successfully');
    }

    /**
     * Show Close Case Page with Details.
     *
     * @param  string|null  $id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function closeCase($id = null)
    {
        try {
            $requestId = Crypt::decrypt($id);

            $data = RequestTable::where('id', $requestId)->first();
            $files = RequestWiseFile::where('request_id', $requestId)->get();
            return view('adminPage.pages.closeCase', compact('data', 'files'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }


    /**
     * Close Case -> particular case will move from toClose state to unpaid state.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function closeCaseData(Request $request)
    {
        if ($request->input('closeCaseBtn') === 'Save') {
            $request->validate([
                'phone_number' => 'required',
                'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/'
            ]);
            RequestClient::where('request_id', $request->requestId)->update([
                'phone_number' => $request->phone_number,
                'email' => $request->email
            ]);
        } elseif ($request->input('closeCaseBtn') === 'Close Case') {
            $physicianId = RequestTable::where('id', $request->requestId)->first()->physician_id;
            RequestTable::where('id', $request->requestId)->update(['status' => 9]);
            RequestStatus::create([
                'request_id' => $request->requestId,
                'status' => 9,
                'physician_id' => $physicianId,
            ]);
            $statusId = RequestStatus::where('request_id', $request->requestId)->orderByDesc('id')->first()->id;
            RequestClosed::create([
                'request_id' => $request->requestId,
                'request_status_id' => $statusId
            ]);
            return redirect()->route('admin.status', 'unpaid')->with('successMessage', 'Case Closed Successfully!');
        }
        return redirect()->back();
    }

    /**
     * Display ViewOrder Page with the details.
     *
     * @param  string|null  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function viewOrder($id = null)
    {
        try {
            $requestId = Crypt::decrypt($id);

            $data = RequestTable::where('id', $requestId)->first();
            $types = HealthProfessionalType::get();
            return view('adminPage.pages.sendOrder', compact('requestId', 'types', 'data'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * Send orders from action menu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendOrder(Request $request)
    {
        $request->validate([
            'profession' => 'required',
            'vendor_id' => 'required',
        ]);

        $healthProfessional = HealthProfessional::where('id', $request->vendor_id)->first();
        Orders::create([
            'vendor_id' => $request->vendor_id,
            'request_id' => $request->requestId,
            'fax_number' => $healthProfessional->fax_number,
            'business_contact' => $healthProfessional->business_contact,
            'email' => $healthProfessional->email,
            'prescription' => $request->prescription,
            'no_of_refill' => $request->refills,
        ]);

        $status = RequestTable::where('id', $request->requestId)->first()->status;

        return redirect()->route('admin.status', $status === 4 || $status === 5 ? 'active' : ($status === 6 ? 'conclude' : 'toclose'))->with('successMessage', 'Order Created Successfully!');
    }

    /**
     * Download the encounter form.
     *
     * @param  int  $requestId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadEncounterForm($requestId)
    {
        $encounterFile = RequestWiseFile::where('request_id', $requestId)->where('is_finalize', true)->first()->file_name;

        $path = storage_path() . '/app/encounterForm/' . $encounterFile;
        return response()->download($path);
    }
}
