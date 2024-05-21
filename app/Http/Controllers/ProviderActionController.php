<?php

namespace App\Http\Controllers;

use App\Http\Requests\EncounterFormRequest;
use App\Models\HealthProfessionalType;
use App\Models\MedicalReport;
use App\Models\Provider;
use App\Models\RequestNotes;
use App\Models\RequestStatus;
use App\Models\RequestTable;
use App\Models\RequestWiseFile;
use App\Services\CreateOrderService;
use App\Services\MedicalFormDataService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProviderActionController extends Controller
{
    /**
     * Accept Case by provider (case/request status will change to accepted)
     *
     * @param string $category different category names.
     *
     * @return int different types of request_type_id.
     */
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
            'TransToPhysicianId' => DB::raw('NULL'),
        ]);
        return redirect()->route('provider.status', 'pending')->with('successMessage', 'You have Successfully Accepted Case');
    }

    /**
     * Transfer a case from provider to admin.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function transferCase(Request $request)
    {
        $request->validate([
            'notes' => 'required|min:5|max:200',
        ]);
        // Retrieve provider ID from RequestTable based on the request ID
        $providerId = RequestTable::where('id', $request->requestId)->first()->physician_id;
        // Create a new record in RequestStatus for the transferred case
        RequestStatus::create([
            'request_id' => $request->requestId,
            'status' => 1,
            'TransToAdmin' => true,
            'physician_id' => $providerId,
            'notes' => $request->notes,
        ]);
        // Update RequestTable to reflect the transfer
        RequestTable::where('id', $request->requestId)->update([
            'physician_id' => DB::raw('NULL'),
            'status' => 1,
        ]);
        return redirect()->back()->with('successMessage', 'Case Transferred to Admin');
    }

    /**
     * Display the notes page for a particular request.
     *
     * @param string|null $id
     *
     * @return \Illuminate\View\View
     */
    public function viewNote($id = null)
    {
        try {
            // Decrypt the request ID
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

    /**
     * Store the physician's note in the database.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeNote(Request $request)
    {
        $request->validate([
            'physician_note' => 'required|min:5|max:200',
        ]);

        // Check if a note already exists for the request
        $requestNote = RequestNotes::where('request_id', $request->requestId)->first();

        // Update the existing note if found, otherwise create a new one
        // if (!empty($requestNote)) {
        if ($requestNote) {
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

    /**
     * View uploads associated with a particular request.
     *
     * @param string|null $id
     *
     * @return \Illuminate\View\View
     */
    public function viewUpload($id = null)
    {
        try {
            $requestId = Crypt::decrypt($id);

            $data = requestTable::where('id', $requestId)->first();
            $documents = RequestWiseFile::where('request_id', $requestId)->orderByDesc('id')->paginate(10);

            return view('providerPage.pages.viewUploads', compact('data', 'documents'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * Upload a document for a particular request.
     *
     * @param \Illuminate\Http\Request $request
     * @param string|null $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadDocument(Request $request, $id = null)
    {
        $request->validate([
            'document' => 'required',
        ], [
            'document.required' => 'Select an File to upload!',
        ]);

        $fileName = uniqid() . '_' . $request->file('document')->getClientOriginalName();
        $request->file('document')->storeAs('public', $fileName);

        $providerId = RequestTable::where('id', $id)->first()->physician_id;

        RequestWiseFile::create([
            'request_id' => $id,
            'file_name' => $fileName,
            'physician_id' => $providerId,
        ]);

        return redirect()->back()->with('uploadSuccessful', 'File Uploaded Successfully');
    }

    /**
     * Display the view case page for a selected request.
     *
     * @param string $id
     *
     * @return \Illuminate\View\View
     */
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

    /**
     * Display the view order page and show associated data.
     *
     * @param string|null $id
     *
     * @return \Illuminate\View\View
     */
    public function viewOrder($id = null)
    {
        try {
            $requestId = Crypt::decrypt($id);

            $data = RequestTable::where('id', $requestId)->first();
            $types = HealthProfessionalType::get();
            return view('providerPage.pages.sendOrder', compact('requestId', 'types', 'data'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * Send orders from the action menu.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendOrder(Request $request, CreateOrderService $createOrderService)
    {
        $request->validate([
            'profession' => 'required',
            'vendor_id' => 'required',
        ]);

        $createOrderService->createOrder($request);

        return redirect()->route('provider.status', 'active')->with('successMessage', 'Order Created Successfully!');
    }

    /**
     * Handle encounter pop-up actions based on selected action (consult, house_call).
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function encounter(Request $request)
    {
        $providerId = RequestTable::where('id', $request->requestId)->first()->physician_id;

        if ($request->house_call === '1') {
            RequestTable::where('id', $request->requestId)->update(['status' => 5, 'call_type' => 1]);
            RequestStatus::create([
                'request_id' => $request->requestId,
                'status' => 5,
                'physician_id' => $providerId,
            ]);
            return redirect()->route('provider.status', ['status' => 'active']);
        }
        if ($request->consult === '1') {
            RequestTable::where('id', $request->requestId)->update(['status' => 6, 'call_type' => 2]);
            RequestStatus::create([
                'request_id' => $request->requestId,
                'status' => 6,
                'physician_id' => $providerId,
            ]);
            return redirect()->route('provider.status', ['status' => 'conclude']);
        }
    }

    /**
     * Handle house call button clicked from the active listing page.
     *
     * @param string $requestId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function encounterHouseCall($requestId)
    {
        $providerId = RequestTable::where('id', $requestId)->first()->physician_id;
        RequestTable::where('id', $requestId)->update(['status' => 6]);
        RequestStatus::create([
            'request_id' => $requestId,
            'status' => 6,
            'physician_id' => $providerId,
        ]);
        return redirect()->route('provider.status', ['status' => 'conclude'])->with('successMessage', 'Case moved to conclude state.');
    }

    /**
     * Show a new medical form or an existing one when the encounter button is clicked in the conclude listing.
     *
     * @param string $id
     *
     * @return \Illuminate\View\View
     */
    public function encounterFormView($id = 'null')
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

    /**
     * Store encounter form (medical form) data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function encounterForm(EncounterFormRequest $request, MedicalFormDataService $medicalFormDataService)
    {
        $report = MedicalReport::where('request_id', $request->request_id)->first();

        $array = $medicalFormDataService->medicalFormData($request);

        $medicalReport = new MedicalReport();
        if ($report) {
            // Report Already exists, update report
            $report->update($array);
        } else {
            // Report does'nt exists, insert a new entry
            $medicalReport->create($array);
        }

        return redirect()->back()->with('encounterChangesSaved', 'Your changes have been Successfully Saved');
    }

    /**
     * Finalize encounter form (medical form) by provider (generate PDF and store in RequestWiseFile).
     *
     * @param string $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function encounterFinalized($id)
    {
        $data = MedicalReport::where('request_id', $id)->first();
        // if (empty($data)) {
        if (!$data) {
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
            $pdf->save(storage_path('app/encounterForm/' . $id . $data->first_name . '-medical.pdf'));
            RequestWiseFile::create([
                'request_id' => $id,
                'file_name' => $id .  $data->first_name . '-medical.pdf',
                'physician_id' => $providerId,
                'is_finalize' => true,
            ]);

            return redirect()->route('provider.status', $status === 6 ? 'conclude' : 'active')->with('successMessage', 'Form Finalized Successfully');
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }

    /**
     * Download the medical form (encounter finalized) - encounterFinalized pop-up action.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\View\View
     */
    public function downloadMedicalForm(Request $request)
    {
        $file = RequestWiseFile::where('request_id', $request->requestId)->where('is_finalize', 1)->first();

        try {
            return response()->download(storage_path('app/encounterForm/' . $file->file_name));
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }

    /**
     * Display the view conclude care page and show associated data.
     *
     * @param string $id
     *
     * @return \Illuminate\View\View
     */
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

    /**
     * Conclude care functionality.
     * Provider concludes care from conclude state which will move to toclose-state.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function concludeCare(Request $request)
    {
        $encounterForm = RequestWiseFile::where('request_id', $request->caseId)->where('is_finalize', true)->first();

        // if (empty($encounterForm)) {
        if (!$encounterForm) {
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
            'physician_id' => $providerId,
        ]);
        RequestNotes::where('request_id', $request->caseId)->update(['physician_notes' => $request->providerNotes]);
        return redirect()->route('provider.status', 'conclude')->with('successMessage', 'Case Concluded Successfully!');
    }

    /**
     * Upload document from conclude care page.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
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
