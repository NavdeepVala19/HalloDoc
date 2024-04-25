<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use App\Models\Orders;
use App\Models\caseTag;
use App\Models\Regions;
use App\Models\Provider;
use App\Models\BlockRequest;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use App\Models\MedicalReport;
use App\Models\RequestClosed;
use App\Models\RequestStatus;
use App\Models\request_Client;
use App\Models\PhysicianRegion;
use App\Models\RequestWiseFile;
use App\Models\HealthProfessional;
use App\Models\HealthProfessionalType;

class AdminActionsController extends Controller
{
    // Assign case - All physician Regions
    public function physicianRegions()
    {
        $regions = Regions::get();
        return response()->json($regions);
    }

    // AJAX call for Physician listing in dropdown selection
    public function getPhysicians($id = null)
    {
        $physiciansId = PhysicianRegion::where('region_id', $id)->pluck('provider_id')->toArray();
        $physicians = Provider::whereIn('id', $physiciansId)->get()->toArray();
        return response()->json($physicians);
    }

    // AJAX call for (Remaining) Physician for listing in dropdown selection 
    public function getNewPhysicians($requestId, $regionId)
    {
        $oldPhysicianId = RequestTable::where('id', $requestId)->where('status', 3)->orderByDesc('id')->first()->physician_id;
        $physiciansId = PhysicianRegion::where('region_id', $regionId)->pluck('provider_id')->toArray();
        $physicians = Provider::whereIn('id', $physiciansId)->whereNot('id', $oldPhysicianId)->get()->toArray();
        return response()->json($physicians);
    }

    // Admin assign Case to provider  
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
        return redirect()->back()->with('assigned', "Case Assigned Successfully to physician - {$physicianName}");
    }

    // Admin Transfer Case to another physician
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
        return redirect()->back()->with('transferredCase', 'Case Transferred to Another Physician');
    }

    // fetch all caseTag data from its table and show in cancelCase PopUp
    public function cancelCaseOptions()
    {
        $reasons = caseTag::all();
        return response()->json($reasons);
    }
    // Store cancel case request_id, status(cancelled), adminId, & Notes(reason) in requestStatusLog
    public function cancelCase(Request $request)
    {
        $request->validate([
            'case_tag' => 'required|in:1,2,3,4',
            'reason' => 'required|min:5|max:200'
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

    // Admin Blocks patient
    public function blockCase(Request $request)
    {
        $request->validate([
            'block_reason' => 'required|max:100'
        ]);

        // Block patient phone number, email, requestId and reason given by admin stored in block_request table
        $client = request_Client::where('request_id', $request->requestId)->first();
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
        return redirect()->back()->with('CaseBlocked', 'Case Blocked Successfully!');
    }

    // View case
    public function viewCase($id)
    {
        try {
            $requestId = Crypt::decrypt($id);
            $data = RequestTable::where('id', $requestId)->first();
            if (empty($data)) {
                return redirect()->back()->with('wrongCase', "Case doesn't exist");
            }
            return view('adminPage.pages.viewCase', compact('data'));
        } catch (\Throwable $th) {
            //throw $th;
            return view('errors.404');
        }
    }

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

        request_Client::where('request_id', $request->requestId)->update([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'date_of_birth' => $dateOfBirth,
            'notes' => $patientNotes
        ]);

        return redirect()->back()->with('caseEdited', "Information updated successfully!");
    }

    // View Notes
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

    // Store Admin Note to display in ViewNotes Page
    public function storeNote(Request $request)
    {
        $request->validate([
            'admin_note' => 'required||min:5|max:200'
        ]);
        $requestNote = RequestNotes::where('request_id', $request->requestId)->first();
        if (!empty($requestNote)) {
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

    // Display View Upload Page with the data
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

    // Upload Document from viewUpload Page
    public function uploadDocument(Request $request, $id = null)
    {
        $request->validate([
            'document' => 'required|mimes:png,jpg,jpeg,doc,docx,pdf|max:5242880'
        ], [
            'document.required' => 'Select an File to upload!'
        ]);
        $fileName = uniqid() . '_' . $request->file('document')->getClientOriginalName();

        $path = $request->file('document')->storeAs('public', $fileName);
        RequestWiseFile::create([
            'request_id' => $id,
            'file_name' => $fileName,
            'admin_id' => 1,
        ]);

        return redirect()->back()->with('uploadSuccessful', "File Uploaded Successfully");
    }

    // show a new medical form or an existing one when clicked encounter button in conclude listing
    public function encounterFormView(Request $request, $id = "null")
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

    // Store Encounter Form (Medical Form) data, changes made by admin
    public function encounterForm(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:3|max:15|alpha',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'mobile' => 'sometimes'
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

    // Clear Case -> change status for particular case to "Clear"
    public function clearCase(Request $request)
    {
        RequestTable::where('id', $request->requestId)->update([
            'status' => 8,
        ]);
        RequestStatus::create([
            'request_id' => $request->requestId,
            'status' => 8,
        ]);
        return redirect()->back()->with('caseCleared', 'Case Cleared Successfully');
    }

    // Show Close Case Page with Details
    public function closeCase(Request $request, $id = null)
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
    // Close Case -> particular case will move from toClose state to unpaid state1
    public function closeCaseData(Request $request)
    {
        if ($request->input('closeCaseBtn') == 'Save') {
            $request->validate([
                'phone_number' => 'required',
                'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/'
            ]);
            request_Client::where('request_id', $request->requestId)->update([
                'phone_number' => $request->phone_number,
                'email' => $request->email
            ]);
        } else if ($request->input('closeCaseBtn') == 'Close Case') {
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
            return redirect()->route('admin.status', 'unpaid')->with('caseClosed', 'Case Closed Successfully!');
        }
        return redirect()->back();
    }

    // Display ViewOrder Page with the details
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

    // Send orders from action menu 
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

        return redirect()->route('admin.status', $status == 4 || $status == 5 ? 'active' : ($status == 6 ? 'conclude' : 'toclose'))->with('orderPlaced', 'Order Created Successfully!');
    }

    public function downloadEncounterForm($requestId)
    {
        $encounterFile = RequestWiseFile::where('request_id', $requestId)->where('is_finalize', true)->first()->file_name;

        $path = (storage_path() . '/app/encounterForm/' . $encounterFile);
        return  response()->download($path);
    }
}
