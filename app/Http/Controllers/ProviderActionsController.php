<?php

namespace App\Http\Controllers;

// DomPDF package used for the creation of pdf from the form
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;

// Models used in these controller
use App\Models\Orders;
use App\Models\Provider;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use App\Models\MedicalReport;
use App\Models\RequestStatus;
use App\Models\RequestWiseFile;
use App\Models\HealthProfessional;
use App\Models\HealthProfessionalType;

class ProviderActionsController extends Controller
{
    // Accept Case 
    public function acceptCase($id = null)
    {
        $providerId = Provider::where('user_id', Auth::user()->id)->first();
        RequestTable::where('id', $id)->update([
            'status' => 3,
        ]);
        RequestStatus::create([
            'request_id' => $id,
            'physician_id' => $providerId->id,
            'status' => 3,
            'admin_id' => DB::raw('NULL'),
            'TransToPhysicianId' => DB::raw('NULL')
        ]);
        return redirect()->route('provider.status', 'pending')->with('successMessage', "You have Successfully Accepted Case");
    }

    // Transfer Case
    public function transferCase(Request $request)
    {
        $request->validate([
            'notes' => 'required|min:5|max:200',
        ]);
        $providerId = RequestTable::where('id', $request->requestId)->first()->physician_id;
        RequestStatus::create([
            'request_id' => $request->requestId,
            'status' => 1,
            'TransToAdmin' => true,
            'physician_id' => $providerId,
            'notes' => $request->notes
        ]);
        RequestTable::where('id', $request->requestId)->update([
            'physician_id' => DB::raw("NULL"),
            'status' => 1
        ]);
        return redirect()->back()->with('successMessage', 'Case Transferred to Admin');
    }

    // show notes page for particular request
    public function viewNote($id = null)
    {
        try {
            $requestId = Crypt::decrypt($id);

            $data = RequestTable::where('id', $requestId)->first();
            $note = RequestNotes::where('request_id', $requestId)->first();
            $adminAssignedCase = RequestStatus::with('transferedPhysician')->where('request_id', $requestId)->where('status', 1)->whereNotNull('TransToPhysicianId')->orderByDesc('id')->first();
            $providerTransferedCase = RequestStatus::with('provider')->where('request_id', $requestId)->where('status', 1)->where('TransToAdmin', true)->orderByDesc('id')->first();
            $adminTransferedCase = RequestStatus::with('transferedPhysician')->where('request_id', $requestId)->where('admin_id', 1)->where('status', 3)->whereNotNull('TransToPhysicianId')->orderByDesc('id')->first();
            return view('providerPage.pages.viewNotes', compact('requestId', 'note', 'adminAssignedCase', 'providerTransferedCase', 'adminTransferedCase', 'data'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    // Store the note in physician_note
    public function storeNote(Request $request)
    {
        $request->validate([
            'physician_note' => 'required|min:5|max:200'
        ]);
        $requestNote = RequestNotes::where('request_id', $request->requestId)->first();
        if (!empty($requestNote)) {
            RequestNotes::where('request_id', $request->requestId)->update([
                'physician_notes' => $request->physician_note,
            ]);
        } else {
            RequestNotes::create([
                'request_id' => $request->requestId,
                'physician_notes' => $request->physician_note,
            ]);
        }
        $id = $request->requestId;

        $id = Crypt::encrypt($id);

        return redirect()->route('provider.view.notes', compact('id'))->with('providerNoteAdded', 'Your Note Successfully Added');
    }

    // View Uploads as per the id 
    public function viewUpload(Request $request, $id = null)
    {
        try {
            $requestId = Crypt::decrypt($id);

            $data  = requestTable::where('id', $requestId)->first();
            $documents = RequestWiseFile::where('request_id', $requestId)->orderByDesc('id')->paginate(10);

            return view('providerPage.pages.viewUploads', compact('data', 'documents'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    // View upload page upload Document feature
    public function uploadDocument(Request $request, $id = null)
    {
        $request->validate([
            'document' => 'required'
        ], [
            'document.required' => 'Select an File to upload!'
        ]);

        $fileName = uniqid() . '_' . $request->file('document')->getClientOriginalName();
        $path = $request->file('document')->storeAs('public', $fileName);

        $providerId = RequestTable::where('id', $id)->first()->physician_id;

        RequestWiseFile::create([
            'request_id' => $id,
            'file_name' => $fileName,
            'physician_id' => $providerId,
        ]);

        return redirect()->back()->with('uploadSuccessful', "File Uploaded Successfully");
    }

    // show a particular case page as required
    public function viewCase($id)
    {
        try {
            $requestId = Crypt::decrypt($id);

            $data = RequestTable::where('id', $requestId)->first();
            return view('providerPage.pages.viewCase', compact('data'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    // View Order Page -> Display page and show data
    public function viewOrder(Request $request, $id = null)
    {
        try {
            $requestId = Crypt::decrypt($id);

            $data = RequestTable::where('id', $requestId)->first();
            $types = HealthProfessionalType::get();
            return view('providerPage.pages.sendOrder',  compact('requestId', 'types', 'data'));
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

        return redirect()->route('provider.status', 'active')->with('successMessage', 'Order Created Successfully!');
    }

    // Encounter pop-up as per action (consult, hous_call) selected perform particular tasks 
    public function encounter(Request $request)
    {
        $providerId = RequestTable::where('id', $request->requestId)->first()->physician_id;

        if ($request->house_call == 1) {
            RequestTable::where('id', $request->requestId)->update(['status' => 5, 'call_type' => 1]);
            RequestStatus::create([
                'request_id' => $request->requestId,
                'status' => 5,
                'physician_id' => $providerId,
            ]);
            return redirect()->route('provider.status', ['status' => 'active']);
        } else if ($request->consult == 1) {
            RequestTable::where('id', $request->requestId)->update(['status' => 6, 'call_type' => 2]);
            RequestStatus::create([
                'request_id' => $request->requestId,
                'status' => 6,
                'physician_id' => $providerId,
            ]);
            return redirect()->route('provider.status', ['status' => 'conclude']);
        }
    }
    // HouseCall button clicked from active listing page
    public function encounterHouseCall($requestId)
    {
        $providerId = RequestTable::where('id', $requestId)->first()->physician_id;
        RequestTable::where('id', $requestId)->update(['status' => 6]);
        RequestStatus::create([
            'request_id' => $requestId,
            'status' => 6,
            'physician_id' => $providerId,
        ]);
        return redirect()->route('provider.status', ['status' => 'conclude']);
    }
    // show a new medical form or an existing one when clicked encounter button in conclude listing
    public function encounterFormView(Request $request, $id = "null")
    {
        try {
            $requestId = Crypt::decrypt($id);

            $data = MedicalReport::where('request_id', $requestId)->first();
            $requestData = RequestTable::where('id', $requestId)->first();
            return view('providerPage.encounterForm', compact('data', 'requestId', 'requestData'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    // Store Encounter Form (Medical Form) Data 
    public function encounterForm(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:3|max:15|alpha',
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

    // Encounter Form (Medical Form Finalized) By provider (Generate PDF and Store in RequestWiseFile)
    public function encounterFinalized($id)
    {
        $data = MedicalReport::where('request_id', $id)->first();
        if (empty($data)) {
            return redirect()->back()->with('saveFormToFinalize', 'First Create and Save Form to Finalize it!')->withInput();
        }
        try {
            $status = RequestTable::where('id', $id)->first()->status;
            MedicalReport::where('request_id', $id)->update(['is_finalize' => true]);

            $data = MedicalReport::where('request_id', $id)->first();
            $pdf = PDF::loadView('providerPage.pdfForm', compact('data'));

            // Create the directory if it doesn't exist
            if (!File::exists(storage_path('app/encounterForm'))) {
                File::makeDirectory(storage_path('app/encounterForm'));
            }

            $providerId = RequestTable::where('id', $id)->first()->physician_id;
            $pdf->save(storage_path('app/encounterForm/' . $id . $data->first_name . "-medical.pdf"));
            RequestWiseFile::create([
                'request_id' => $id,
                'file_name' => $id .  $data->first_name . "-medical.pdf",
                'physician_id' => $providerId,
                'is_finalize' => true,
            ]);

            return redirect()->route('provider.status', $status == 6 ? 'conclude' : 'active')->with('successMessage', "Form Finalized Successfully");
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    // Download MedicalForm - encounterFinalized pop-up action
    public function downloadMedicalForm(Request $request)
    {
        $file = RequestWiseFile::where('request_id', $request->requestId)->where('is_finalize', 1)->first();

        try {
            return response()->download(storage_path('app/encounterForm/' . $file->file_name));
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }

    // View Conclude Care Page -> Display page and show data
    public function viewConcludeCare($id)
    {
        try {
            $requestId = Crypt::decrypt($id);

            $case = RequestTable::where('id', $requestId)->first();
            $docs = RequestWiseFile::where('request_id', $requestId)->paginate(10);

            return view('providerPage.concludeCare', compact('case', 'docs'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    // Conclude Care functionality -> Provider Conclude care from conclude state which will move to toclose-state
    public function concludeCare(Request $request)
    {
        $encounterForm = RequestWiseFile::where('request_id', $request->caseId)->where('is_finalize', true)->first();

        if (empty($encounterForm)) {
            return redirect()->back()->with('encounterFormRequired', 'Encounter Form need to be finalized to conclude Case!');
        }

        $providerId = RequestTable::where('id', $request->caseId)->first()->physician_id;
        RequestTable::where('id', $request->caseId)->update([
            'status' => 7,
            'completed_by_physician' => true,
        ]);

        RequestStatus::create([
            'request_id' => $request->caseId,
            'status' => 7,
            'physician_id' => $providerId
        ]);
        RequestNotes::where('request_id', $request->caseId)->update(['physician_notes' => $request->providerNotes]);
        return redirect()->route('provider.status', 'conclude')->with('successMessage', 'Case Concluded Successfully!');
    }

    // Upload Document from conclude Care page
    public function uploadDocsConcludeCare(Request $request)
    {
        $providerId = RequestTable::where('id', $request->caseId)->first()->physician_id;
        $request->file('document')->storeAs('public', $request->file('document')->getClientOriginalName());

        RequestWiseFile::create([
            'request_id' => $request->caseId,
            'file_name' => $request->file('document')->getClientOriginalName(),
            'physician_id' => $providerId,
        ]);

        return redirect()->back()->with('fileUploaded', 'File Uploaded Successfully!');
    }
}
