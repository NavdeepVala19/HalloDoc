<?php

namespace App\Http\Controllers;


use ZipArchive;
use App\Mail\SendMail;

// Different Models used in these Controller
use App\Models\EmailLog;
use App\Models\Provider;
use App\Mail\SendAgreement;
use App\Models\RequestNotes;
use App\Models\requestTable;
use Illuminate\Http\Request;
use App\Models\MedicalReport;

// For sending Mails
use App\Models\RequestStatus;
use App\Models\request_Client;
use App\Models\RequestWiseFile;
use Barryvdh\DomPDF\Facade\Pdf;

// DomPDF package used for the creation of pdf from the form
use Illuminate\Support\Facades\DB;


// To create zip, used to download multiple documents at once
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class ProviderController extends Controller
{
    public function totalCasesCount($providerId)
    {
        // Total count of cases as per the status (displayed in all listing pages)
        $newCasesCount = RequestStatus::where('status', 1)->where('TransToPhysicianId', $providerId)->count(); // unassigned case, assigned to provider but not accepted
        $pendingCasesCount = RequestStatus::where('status', 3)->where('physician_id', $providerId)->count(); //accepted by provider, pending state
        $activeCasesCount = RequestStatus::where('status', 4)->orWhere('status', 5)->where('physician_id', $providerId)->count(); //MDEnRoute(agreement sent and accepted by patient), MDOnSite(call type selected by provider)
        $concludeCasesCount = RequestStatus::where('status', 6)->where('physician_id', $providerId)->count();

        return [
            'newCase' => $newCasesCount,
            'pendingCase' => $pendingCasesCount,
            'activeCase' => $activeCasesCount,
            'concludeCase' => $concludeCasesCount
        ];
    }

    // provides all cases data as per status
    public function cases($status, $count, $userData, $providerId)
    {
        // *********************************************************** Working
        if ($status == 'new') {
            $cases = RequestStatus::with('request')->where('status', 1)->where('TransToPhysicianId', $providerId)->paginate(10);
            return view('providerPage.providerTabs.newListing', compact('cases', 'count', 'userData'));
        } else if ($status == 'pending') {
            $cases = RequestStatus::with('request')->where('status', 3)->where('physician_id', $providerId)->paginate(10);
            return view('providerPage.providerTabs.pendingListing', compact('cases', 'count', 'userData'));
        } else if ($status == 'active') {
            $cases = RequestStatus::with('request')->where('status', 4)->where('physician_id', $providerId)->orWhere('status', 5)->paginate(10);
            return view('providerPage.providerTabs.activeListing', compact('cases', 'count', 'userData'));
        } else if ($status == 'conclude') {
            $cases = RequestStatus::with('request')->where('status', 6)->where('physician_id', $providerId)->paginate(10);
            return view('providerPage.providerTabs.concludeListing', compact('cases', 'count', 'userData'));
        }
    }

    public function providerDashboard()
    {
        return redirect('/provider/new');
    }

    // Display Provider Listing/Dashboard page as per the Tab Selected (By default it's "new")
    public function status(Request $request, $status = 'new')
    {
        $userData = Auth::user();
        $providerId = Provider::where('user_id', $userData->id)->first()->id;
        // dd($userData);
        $count = $this->totalCasesCount($providerId);
        return $this->cases($status, $count, $userData, $providerId);
    }


    // Filter as per the button clicked in listing pages (Here we need both, the status and which button was clicked)
    public function filter(Request $request, $status = 'new', $category = 'all')
    {
        $userData = Auth::user();
        $providerId = Provider::where('user_id', $userData->id)->first()->id;

        $count = $this->totalCasesCount($providerId);

        // By default, category is all, and when any other button is clicked for filter that data will be passed to the view.
        if ($category == 'all') {
            // Retrieve data for all request type
            return $this->cases($status, $count, $userData, $providerId);
        } else {
            // Retrieve data for specific request type using request_type_id
            // Provides data as per the status and required category
            if ($status == 'new') {
                $cases = RequestStatus::where('status', 1)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('providerPage.providerTabs.newListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'pending') {
                $cases = RequestStatus::where('status', 3)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('providerPage.providerTabs.pendingListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'active') {
                $cases = RequestStatus::where('status', 4)->orWhere('status', 5)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('providerPage.providerTabs.activeListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'conclude') {
                $cases = RequestStatus::where('status', 6)->whereHas('request', function ($q) use ($category) {
                    $q->where('request_type_id', $this->getCategoryId($category));
                })->paginate(10);
                return view('providerPage.providerTabs.concludeListing', compact('cases', 'count', 'userData'));
            }
        }
    }

    // Search for specific keyword in first_name of requestTable 
    public function search(Request $request, $status = 'new', $category = 'all')
    {
        $userData = Auth::user();
        $providerId = Provider::where('user_id', $userData->id)->first()->id;
        $count = $this->totalCasesCount($providerId);

        // check for both status & category and fetch data for only the searched term  
        if ($category == 'all') {
            if ($status == 'new') {
                $cases = RequestStatus::where('status', 1)
                    ->whereHas('request', function ($q) use ($request) {
                        $q->where('first_name', 'like', '%' . $request->search . '%');
                        $q->orWhereHas('requestClient', function ($query) use ($request) {
                            $query->where('first_name', 'like', "%$request->search%");
                        });
                    })->paginate(10);
                return view('providerPage.providerTabs.newListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'pending') {
                $cases = RequestStatus::where('status', 3)
                    ->whereHas('request', function ($q) use ($request) {
                        $q->where('first_name', 'like', '%' . $request->search . '%');
                        $q->orWhereHas('requestClient', function ($query) use ($request) {
                            $query->where('first_name', 'like', "%$request->search%");
                        });
                    })->paginate(10);
                return view('providerPage.providerTabs.pendingListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'active') {
                $cases = RequestStatus::where('status', 4)->orWhere('status', 5)
                    ->whereHas('request', function ($q) use ($request) {
                        $q->where('first_name', 'like', '%' . $request->search . '%');
                        $q->orWhereHas('requestClient', function ($query) use ($request) {
                            $query->where('first_name', 'like', "%$request->search%");
                        });
                    })->paginate(10);

                return view('providerPage.providerTabs.activeListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'conclude') {
                $cases = RequestStatus::where('status', 6)->whereHas('request', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->search . '%');
                    $q->orWhereHas('requestClient', function ($query) use ($request) {
                        $query->where('first_name', 'like', "%$request->search%");
                    });
                })->paginate(10);
                return view('providerPage.providerTabs.concludeListing', compact('cases', 'count', 'userData'));
            }
        } else {
            if ($status == 'new') {
                $cases = RequestStatus::where('status', 1)
                    ->whereHas('request', function ($q) use ($request, $category) {
                        $q->where('request_type_id', $this->getCategoryId($category))
                            ->whereHas('requestClient', function ($query) use ($request) {
                                $query->where('first_name', 'like', '%' . $request->search . '%');
                            });
                    })->paginate(10);

                return view('providerPage.providerTabs.newListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'pending') {

                $cases = RequestStatus::where('status', 3)
                    ->whereHas('request', function ($q) use ($request, $category) {
                        $q->where('request_type_id', $this->getCategoryId($category))
                            ->whereHas('requestClient', function ($query) use ($request) {
                                $query->where('first_name', 'like', '%' . $request->search . '%');
                            });
                    })->paginate(10);

                return view('providerPage.providerTabs.pendingListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'active') {

                $cases = RequestStatus::where('status', 4)->orWhere('status', 5)
                    ->whereHas('request', function ($q) use ($request, $category) {
                        $q->where('request_type_id', $this->getCategoryId($category))
                            ->whereHas('requestClient', function ($query) use ($request) {
                                $query->where('first_name', 'like', '%' . $request->search . '%');
                            });
                    })->paginate(10);

                return view('providerPage.providerTabs.activeListing', compact('cases', 'count', 'userData'));
            } else if ($status == 'conclude') {

                $cases = RequestStatus::where('status', 6)
                    ->whereHas('request', function ($q) use ($request, $category) {
                        $q->where('request_type_id', $this->getCategoryId($category))
                            ->whereHas('requestClient', function ($query) use ($request) {
                                $query->where('first_name', 'like', '%' . $request->search . '%');
                            });
                    })->paginate(10);

                return view('providerPage.providerTabs.concludeListing', compact('cases', 'count', 'userData'));
            }
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

    // Assign Case
    public function assignCase(Request $request)
    {
        // dd($request->physician);
        RequestStatus::where('request_id', $request->requestId)->update([
            'TransToAdmin' => true,
            'physician_id' => null,
            'notes' => $request->notes
        ]);
        return redirect()->back();
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

        return redirect()->route("provider.dashboard");
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
        $userData = Auth::user();
        $provider = Provider::where('user_id', $userData->id)->first();
        return view('providerPage.providerProfile', compact('provider', 'userData'));
    }

    // Provider My Profile Data request for edit on submit
    public function providerData(Request $request)
    {
        $userData = Auth::user();
        // Fetch Provider data as per the provider logged in 
        Provider::where('user_id', $userData->id)->first();
        dd($userData);
    }

    // Accept Case 
    public function acceptCase($id = null)
    {
        $providerId = Provider::where('user_id', Auth::user()->id)->first();
        RequestStatus::where('request_id', $id)->update([
            'physician_id' => $providerId->id,
            'status' => 3,
            'TransToPhysicianId' => DB::raw('NULL')
        ]);
        return redirect()->route('provider.status', 'pending');
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
        EmailLog::insert([
            // 'provider_id' => specify provider id
            // 'email_template' =>,
            // 'subject_name' =>,
            'email' => $request->email,
            'is_email_sent' => true,
            'sent_tries' => 1,
            // 'sent_date' => ,
        ]);
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
