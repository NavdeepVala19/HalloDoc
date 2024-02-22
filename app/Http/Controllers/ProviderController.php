<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

// Different Models used in these Controller
use App\Models\requestTable;
use App\Models\request_Client;
use App\Models\MedicalReport;
use App\Models\RequestNotes;
use App\Models\RequestWiseFile;

// For sending Mails
use App\Mail\SendMail;
use App\Mail\SendAgreement;
use Illuminate\Support\Facades\Mail;

// DomPDF package used for the creation of pdf from the form
use Barryvdh\DomPDF\Facade\Pdf;


// To create zip, used to download multiple documents at once
use ZipArchive;


class ProviderController extends Controller
{
    // Display Provider Listing/Dashboard page as per the Tab Selected (By default it's "new")
    public function status(Request $request, $status = 'new')
    {

        // Total count of cases as per the status (displayed in all listing pages)
        $newCasesCount = requestTable::with(['requestClient'])->where('status', 1)->count();
        $pendingCasesCount = requestTable::with(['requestClient'])->where('status', 2)->count();
        $activeCasesCount = requestTable::with(['requestClient'])->where('status', 3)->count();
        $concludeCasesCount = requestTable::with(['requestClient'])->where('status', 4)->count();

        $cases = requestTable::with(['requestClient'])->where('status', $this->getStatusId($status))->paginate(10);

        // As per the selected Tab, different view (listing pages) are rendered
        if ($this->getStatusId($status) == '1') {
            return view('providerPage.providerTabs.newListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '2') {
            return view('providerPage.providerTabs.pendingListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '3') {
            return view('providerPage.providerTabs.activeListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '4') {
            return view('providerPage.providerTabs.concludeListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        }
    }

    // Filter as per the button clicked in listing pages (Here we need both, the status and which button was clicked)
    public function filter(Request $request, $status = 'new', $category = 'all')
    {
        // Total count of cases as per the status (displayed in all listing pages)
        $newCasesCount = requestTable::with(['requestClient'])->where('status', 1)->count();
        $pendingCasesCount = requestTable::with(['requestClient'])->where('status', 2)->count();
        $activeCasesCount = requestTable::with(['requestClient'])->where('status', 3)->count();
        $concludeCasesCount = requestTable::with(['requestClient'])->where('status', 4)->count();

        // By default, category is all, and when any other button is clicked for filter that data will be passed to the view.
        if ($category == 'all') {
            // Retrieve data for all request type
            $cases = requestTable::with(['requestClient'])->where('status', $this->getStatusId($status))->paginate(10);
        } else {
            // Retrieve data for specific request type using request_type_id
            $cases = requestTable::with(['requestClient'])->where('status', $this->getStatusId($status))->where('request_type_id', $this->getCategoryId($category))->paginate(10);
        }

        if ($this->getStatusId($status) == '1') {
            return view('providerPage.providerTabs.newListing', compact('cases',  'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '2') {
            return view('providerPage.providerTabs.pendingListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '3') {
            return view('providerPage.providerTabs.activeListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '4') {
            return view('providerPage.providerTabs.concludeListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        }
    }

    // Search for specific keyword in first_name of requestTable 
    public function search(Request $request, $status = 'new', $category = 'all')
    {
        // Total count of cases as per the status (displayed in all listing pages)
        $newCasesCount = requestTable::with(['requestClient'])->where('status', 1)->count();
        $pendingCasesCount = requestTable::with(['requestClient'])->where('status', 2)->count();
        $activeCasesCount = requestTable::with(['requestClient'])->where('status', 3)->count();
        $concludeCasesCount = requestTable::with(['requestClient'])->where('status', 4)->count();


        // check for both status & category and fetch data for only the searched term  
        if ($category == 'all') {
            $cases = requestTable::with(['requestClient'])
                ->where('status', $this->getStatusId($status))
                ->where('first_name', 'like', '%' . $request->search . '%')->paginate(10);
        } else {
            $cases = requestTable::with(['requestClient'])
                ->where('status', $this->getStatusId($status))->where('request_type_id', $this->getCategoryId($category))
                ->where('first_name', 'like', '%' . $request->search . '%')->paginate(10);
        }

        if ($this->getStatusId($status) == '1') {
            return view('providerPage.providerTabs.newListing', compact('cases',  'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '2') {
            return view('providerPage.providerTabs.pendingListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '3') {
            return view('providerPage.providerTabs.activeListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        } else if ($this->getStatusId($status) == '4') {
            return view('providerPage.providerTabs.concludeListing', compact('cases', 'newCasesCount', 'pendingCasesCount', 'activeCasesCount', 'concludeCasesCount'));
        }
    }

    //Get category id from the name of category
    private function getCategoryId($category)
    {
        // mapping of category names to request_type_id
        $categoryMapping = [
            'patient' => 1,
            'family' => 2,
            'business' => 3,
            'concierge' => 4,
        ];
        return $categoryMapping[$category] ?? null;
    }

    //Get status id from the name of status
    private function getStatusId($status)
    {
        // mapping of status names to status
        $statusMapping = [
            'new' => 1,
            'pending' => 2,
            'active' => 3,
            'conclude' => 4,
        ];
        return $statusMapping[$status] ?? null;
    }

    public function viewCreateRequest()
    {
        return view('providerPage/providerRequest');
    }

    // Create Request Page for Provider
    public function createRequest(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'phone_number' => 'required',
                'email' => 'required|email',
                // 'dob' => 'required',
                'street' => 'required',
                'city' => 'required',
                'state' => 'required'
            ]);
        } catch (\Throwable $th) {
            dd($th);
        }
        $requestTable = new requestTable();
        $requestTable->request_type_id = $request->request_type_id;
        $requestTable->first_name = $request->first_name;
        $requestTable->last_name = $request->last_name;
        $requestTable->phone_number = $request->phone_number;
        $requestTable->email = $request->email;
        $requestTable->status = 1;
        $requestTable->is_urgent_email_sent = 0;
        $requestTable->is_mobile = 0;
        $requestTable->case_tag_physician = 0;
        $requestTable->patient_account_id = 0;
        $requestTable->created_user_id = 0;
        $requestTable->save();

        $requestClient = new request_Client();
        $requestClient->request_id = $requestTable->id;
        $requestClient->first_name = $request->first_name;
        $requestClient->last_name = $request->last_name;
        $requestClient->phone_number = $request->phone_number;
        $requestClient->street = $request->street;
        $requestClient->city = $request->city;
        $requestClient->state = $request->state;
        $requestClient->zipcode = $request->zip;
        $requestClient->room = $request->room;
        $requestClient->save();

        $requestNotes = new RequestNotes();
        $requestNotes->request_id = $requestTable->id;
        $requestNotes->AdministrativeNotes = $request->note;
        $requestNotes->created_by = 'physician';
        $requestNotes->save();

        return redirect()->route("provider-dashboard");
    }

    // Encounter pop-up consult selected, perform these operation
    public function encounter(Request $request)
    {
        requestTable::where('id', $request->caseId)->update(['status' => 4], ['call_type' => 'consult']);
        return redirect()->route('provider-status', ['status' => 'conclude']);
    }

    // show a new medical form or an existing one on clicking encounter button in conclude listing
    public function encounterFormView(Request $request, $id = "null")
    {
        $data = MedicalReport::where('request_id', $id)->first();
        return view('providerPage.encounterForm', compact('data', 'id'));
    }
    public function encounterForm(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'email' => 'required|email'
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
        ];
        $medicalReport = new MedicalReport();
        if ($report) {
            // Report Already exists, update report
            $report->update($array);
        } else {
            // Report does'nt exists, insert a new entry
            $medicalReport->create($array);
        }

        return redirect()->route('provider-status', ['status' => 'conclude']);
    }

    // Generate pdf on click
    public function generatePDF(Request $request, $id = null)
    {
        try {
            $data = MedicalReport::where('request_id', $id)->first();
            $pdf = PDF::loadView('providerPage.pdfForm', ['data' => $data]);

            return $pdf->download($data->first_name . "-medical.pdf");
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    // Show MyProfile Provider
    public function providerProfile()
    {
        return view('providerPage.providerProfile');
    }

    // Provider My Profile Data request for edit on submit
    public function providerData(Request $request)
    {
    }

    // show a particular case page as required
    public function viewCase($id)
    {
        $data = request_Client::where('id', $id)->first();
        return view('providerPage.pages.viewCase', compact('data'));
    }
    // show notes page for particular request
    public function viewNote($id = null)
    {
        return view('providerPage.pages.viewNotes');
    }

    // Send Mail
    public function sendMail(Request $request)
    {
        Mail::to($request->email)->send(new SendMail($request->all()));
        return redirect()->back();
    }

    // View Uploads as per the id 
    public function viewUpload(Request $request, $id = null)
    {
        $data  = requestTable::where('id', $id)->first();
        $documents = RequestWiseFile::get();

        return view('providerPage.pages.viewUploads', compact('data', 'documents'));
    }
    public function uploadDocument(Request $request, $id = null)
    {
        $path = $request->file('document')->storeAs('public', $request->file('document')->getClientOriginalName());
        RequestWiseFile::insert(['request_id' => $id, 'file_name' => $request->file('document')->getClientOriginalName()]);

        return redirect()->back();
    }
    public function download(Request $requet, $id = null)
    {
        $file = RequestWiseFile::where('id', $id)->first();
        $path = (public_path() . '/storage/' . $file->file_name);

        return response()->download($path);
    }

    public function deleteDoc(Request $request, $id = null)
    {
        RequestWiseFile::where('id', $id)->delete();

        return redirect()->back();
    }

    public function operations(Request $request)
    {
        if ($request->input('operation') == 'delete_all') {
            $ids = $request->input('selected');
            RequestWiseFile::whereIn('id', $ids)->delete();

            return redirect()->back();
        } else if ($request->input('operation') == 'download_all') {
            $ids = $request->input('selected');

            $zip = new ZipArchive;
            $zipFile = 'documents.zip';

            if ($zip->open(public_path($zipFile), ZipArchive::CREATE) === TRUE) {
                foreach ($ids as $id) {
                    $file = RequestWiseFile::where('id', $id)->first();
                    $path = (public_path() . '/storage/' . $file->file_name);

                    $zip->addFile($path, $file->file_name);
                }
                $zip->close();
            }
            return response()->download(public_path($zipFile))->deleteFileAfterSend(true);
        }
    }

    public function viewOrder(Request $request, $id = null)
    {
        return view('providerPage.pages.sendOrder', compact('id'));
    }


    public function sendAgreementLink(Request $request)
    {
        Mail::to($request->email)->send(new SendAgreement($request->all()));
        return redirect()->back();
    }
}
