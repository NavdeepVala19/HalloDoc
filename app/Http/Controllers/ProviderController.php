<?php

namespace App\Http\Controllers;

use ZipArchive;
use Carbon\Carbon;
use App\Models\User;

// Different Models used in these Controller
use App\Models\Admin;
use App\Models\users;
use App\Mail\SendMail;
use App\Models\Orders;
use App\Models\Regions;
use App\Models\SMSLogs;
use Twilio\Rest\Client;
use App\Models\allusers;
use App\Models\EmailLog;
use App\Models\Provider;
use App\Mail\SendAgreement;
use App\Models\RequestNotes;
// For sending Mails
use App\Models\requestTable;
use Illuminate\Http\Request;
use App\Mail\ProviderRequest;
use App\Models\MedicalReport;
use App\Models\RequestStatus;
use App\Mail\sendEmailAddress;
use App\Models\request_Client;
// DomPDF package used for the creation of pdf from the form
use App\Models\PhysicianRegion;


// To create zip, used to download multiple documents at once
use App\Models\RequestWiseFile;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\DocsAttachmentMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\HealthProfessionalType;
use Illuminate\Support\Facades\Storage;


class ProviderController extends Controller
{
    public function totalCasesCount($providerId)
    {
        // Total count of cases as per the status (displayed in all listing pages)
        return [
            // unassigned case, assigned to provider but not accepted
            'newCase' => RequestTable::where('status', 1)->where('physician_id', $providerId)->count(),
            // Accepted by provider, pending state
            'pendingCase' => RequestTable::where('status', 3)->where('physician_id', $providerId)->count(),
            //MDEnRoute(agreement sent and accepted by patient), MDOnSite(call type selected by provider)
            'activeCase' => RequestTable::whereIn('status', [4, 5])->where('physician_id', $providerId)->count(),
            'concludeCase' => RequestTable::where('status', 6)->where('physician_id', $providerId)->count(),
        ];
    }

    // Build Query as per filters, search query or normal cases
    public function buildQuery($status, $category, $searchTerm, $providerId)
    {
        if (is_array($this->getStatusId($status))) {
            $query = RequestTable::with('requestClient')->whereIn('status', $this->getStatusId($status))->where('physician_id', $providerId);
        } else {
            $query = RequestTable::with('requestClient')->where('status', $this->getStatusId($status))->where('physician_id', $providerId);
        }

        // Filter by Category if not 'all'
        if ($category !== 'all') {
            $query->where('request_type_id', $this->getCategoryId($category));
        }

        // Apply search condition
        if ($searchTerm) {
            $query->whereHas('requestClient', function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%$searchTerm%");
            });
        }

        return $query;
    }

    // Method to retrieve cases based on status, category, and search term
    public function cases(Request $request, $status = 'new', $category = "all")
    {
        $searchTerm = $request->search;
        $userData = Auth::user();
        $providerId = Provider::where('user_id', $userData->id)->first()->id;
        $count = $this->totalCasesCount($providerId);
        $query = $this->buildQuery($status, $category, $searchTerm, $providerId);

        $cases = $query->orderByDesc('id')->paginate(10);

        // dd($query->get());
        $viewName = 'providerPage.providerTabs.' . $status . 'Listing';
        return view($viewName, compact('cases', 'count', 'userData'));
    }

    public function providerDashboard()
    {
        return redirect('/provider/new');
    }

    // Display Provider Listing/Dashboard page as per the Tab Selected (By default it's "new")
    public function status(Request $request, $status = 'new')
    {
        return $this->cases($request, $status);
    }

    // Filter as per the button clicked in listing pages (Here we need both, the status and which button was clicked)
    public function filter(Request $request, $status = 'new', $category = 'all')
    {
        return $this->cases($request, $status, $category);
    }

    // Search for specific keyword in first_name of requestTable and requestclient
    public function search(Request $request, $status = 'new', $category = 'all')
    {
        return $this->cases($request, $status, $category);
    }

    //Get category id from the name of category
    private function getCategoryId($category)
    {
        // mapping of category names to request_type_id
        $categoryMapping = [
            'patient' => 1,
            'family' => 2,
            'concierge' => 3,
            'business' => 4,
        ];
        return $categoryMapping[$category] ?? null;
    }
    // Get Status Id from the name 
    private function getStatusId($status)
    {
        $statusMapping = [
            'new' => 1,
            'pending' => 3,
            'active' => [4, 5],
            'conclude' => 6,
        ];
        return $statusMapping[$status];
    }

    // Assign Case
    public function transferCase(Request $request)
    {
        $request->validate([
            'notes' => 'required',
        ]);
        RequestTable::where('id', $request->requestId)->update([
            'physician_id' => DB::raw("NULL"),
        ]);
        $providerId = RequestTable::where('id', $request->requestId)->first()->physician_id;
        RequestStatus::create([
            'request_id' => $request->requestId,
            'status' => 3,
            'TransToAdmin' => true,
            'physician_id' => $providerId,
            'notes' => $request->notes
        ]);
        return redirect()->back()->with('transferredCase', 'Case Transferred to Another Physician');
    }

    public function viewCreateRequest()
    {
        return view('providerPage/providerRequest');
    }

    // Create Request Page for Provider
    public function createRequest(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'string|min:2|max:30',
            'phone_number' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'email' => 'required|email',
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'digits:6',
        ]);

        $isEmailStored = users::where('email', $request->email)->pluck('email');

        if ($request->email != $isEmailStored) {
            // store email and phoneNumber in users table
            $requestEmail = new users();
            $requestEmail->username = $request->first_name . " " . $request->last_name;
            $requestEmail->email = $request->email;
            $requestEmail->phone_number = $request->phone_number;
            $requestEmail->save();

            // store all details of patient in allUsers table
            $requestUsers = new allusers();
            $requestUsers->user_id = $requestEmail->id;
            $requestUsers->first_name = $request->first_name;
            $requestUsers->last_name = $request->last_name;
            $requestUsers->email = $request->email;
            $requestUsers->mobile = $request->phone_number;
            $requestUsers->street = $request->street;
            $requestUsers->city = $request->city;
            $requestUsers->state = $request->state;
            $requestUsers->zipcode = $request->zipcode;
            $requestUsers->save();
        }

        $requestTable = new requestTable();
        $requestTable->status = 1;
        $requestTable->user_id = $requestEmail->id;
        $requestTable->request_type_id = $request->request_type_id;
        $requestTable->first_name = $request->first_name;
        $requestTable->last_name = $request->last_name;
        $requestTable->email = $request->email;
        $requestTable->phone_number = $request->phone_number;
        $requestTable->save();

        $requestClient = new request_Client();
        $requestClient->request_id = $requestTable->id;
        $requestClient->first_name = $request->first_name;
        $requestClient->last_name = $request->last_name;
        $requestClient->phone_number = $request->phone_number;
        $requestClient->date_of_birth = $request->dob;
        $requestClient->street = $request->street;
        $requestClient->city = $request->city;
        $requestClient->state = $request->state;
        $requestClient->zipcode = $request->zip;
        $requestClient->room = $request->room;
        $requestClient->save();

        $requestNotes = new RequestNotes();
        $requestNotes->request_id = $requestTable->id;
        $requestNotes->physician_notes = $request->note;
        $requestNotes->created_by = 'physician';
        $requestNotes->save();

        $currentTime = Carbon::now();
        $currentDate = $currentTime->format('Y');

        $todayDate = $currentTime->format('Y-m-d');
        $entriesCount = RequestTable::whereDate('created_at', $todayDate)->count();

        $uppercaseStateAbbr = strtoupper(substr($request->state, 0, 2));
        $uppercaseLastName = strtoupper(substr($request->last_name, 0, 2));
        $uppercaseFirstName = strtoupper(substr($request->first_name, 0, 2));

        $confirmationNumber = $uppercaseStateAbbr . $currentDate . $uppercaseLastName . $uppercaseFirstName  . '00' . $entriesCount;

        if (!empty($requestTable->id)) {
            $requestTable->update(['confirmation_no' => $confirmationNumber]);
        }

        // send email
        $emailAddress = $request->email;
        Mail::to($request->email)->send(new sendEmailAddress($emailAddress));

        EmailLog::create([
            'role_id' => 3,
            'request_id' =>  $requestTable->id,
            'confirmation_number' => $confirmationNumber,
            'is_email_sent' => 1,
            'sent_tries' => 1,
            'create_date' => now(),
            'sent_date' => now(),
            'email_template' => $request->email,
            'subject_name' => 'Create account by clicking on below link with below email address',
            'email' => $request->email,
        ]);

        return redirect()->route("provider.dashboard")->withInput();
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
        $data = MedicalReport::where('request_id', $id)->first();
        $requestData = RequestTable::where('id', $id)->first();
        return view('providerPage.encounterForm', compact('data', 'id', 'requestData'));
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

            return redirect()->route('provider.status', $status == 6 ? 'conclude' : 'active')->with('formFinalized', "Form Finalized Successfully");
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    // Download MedicalForm - encounterFinalized pop-up action
    public function downloadMedicalForm(Request $request)
    {
        $file = RequestWiseFile::where('request_id', $request->requestId)->where('is_finalize', 1)->first();

        return response()->download(storage_path('app/encounterForm/' . $file->file_name));
    }

    // Show MyProfile Provider
    public function providerProfile()
    {
        $userData = Auth::user();
        $provider = Provider::where('user_id', $userData->id)->first();
        $regions = Regions::get();
        $physicianRegions = PhysicianRegion::where('provider_id', $provider->id)->get();
        return view('providerPage.providerProfile', compact('provider', 'userData', 'regions', 'physicianRegions'));
    }
    // Reset Password Provider
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:5'
        ]);
        $userId = Provider::where('id', $request->providerId)->first()->user_id;

        User::where('id', $userId)->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password Reset Successfully');
    }
    // Provider send Mail to Admin for changes in Profile
    public function editProfileMessage(Request $request)
    {
        $admin = Admin::where('id', 1)->first();
        $provider = Provider::where('id', $request->providerId)->first();

        EmailLog::create([
            'role_id' => 2,
            'provider_id' => $request->providerId,
            'subject_name' => 'Request from Provider to Edit Profile',
            'create_date' => now(),
            'sent_date' => now(),
            'is_email_sent' => 1,
            'action' => 3,
            'recipient_name' => $admin->first_name . " " . $admin->last_name,
            'email_template' => 'email.providerRequest',
            'email' => $admin->email,
            'sent_tries' => 1,
        ]);

        Mail::to($admin->email)->send(new ProviderRequest($admin, $provider, $request));

        return redirect()->back()->with('mailSentToAdmin', 'Email Sent to Admin - to make requested changes!');
    }

    // Accept Case 
    public function acceptCase($id = null)
    {
        $providerId = Provider::where('user_id', Auth::user()->id)->first();
        RequestTable::where('id', $id)->update(['status' => 3]);
        RequestStatus::create([
            'request_id' => $id,
            'physician_id' => $providerId->id,
            'status' => 3,
            'admin_id' => DB::raw('NULL'),
            'TransToPhysicianId' => DB::raw('NULL')
        ]);
        return redirect()->route('provider.status', 'pending')->with('caseAccepted', "You have Successfully Accepted Case");
    }

    // show a particular case page as required
    public function viewCase($id)
    {
        $data = RequestTable::where('id', $id)->first();
        return view('providerPage.pages.viewCase', compact('data'));
    }
    // show notes page for particular request
    public function viewNote($id = null)
    {
        $data = RequestTable::where('id', $id)->first();
        $note = RequestNotes::where('request_id', $id)->first();
        $adminAssignedCase = RequestStatus::with('transferedPhysician')->where('request_id', $id)->where('status', 1)->whereNotNull('TransToPhysicianId')->orderByDesc('id')->first();
        $providerTransferedCase = RequestStatus::with('provider')->where('request_id', $id)->where('status', 3)->where('TransToAdmin', true)->orderByDesc('id')->first();
        $adminTransferedCase = RequestStatus::with('transferedPhysician')->where('request_id', $id)->where('status', 1)->whereNotNull('TransToPhysicianId')->orderByDesc('id')->first();
        return view('providerPage.pages.viewNotes', compact('id', 'note', 'adminAssignedCase', 'providerTransferedCase', 'adminTransferedCase', 'data'));
    }

    // Store the note in physician_note
    public function storeNote(Request $request)
    {
        $request->validate([
            'physician_note' => 'required'
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

        return redirect()->route('provider.view.notes', compact('id'))->with('providerNoteAdded', 'Your Note Successfully Added');
    }

    // Send Mail
    public function sendMail(Request $request)
    {
        // Generate the link using route() helper (assuming route parameter is optional)
        $link = route('submitRequest');

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email'
        ]);

        // send SMS 
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $senderNumber = getenv("TWILIO_PHONE_NUMBER");

        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create(
                "+91 99780 71802", // to
                [
                    "body" => "Hii $request->first_name $request->last_name, Click on the this link to create request:$link",
                    "from" =>  $senderNumber
                ]
            );

        $name = $request->first_name . " " . $request->last_name;

        SMSLogs::create(
            [
                'mobile_number' => $request->phone_number,
                'created_date' => now(),
                'sent_date' => now(),
                'role_id' => 2,
                'recipient_name' => $name,
                'sent_tries' => 1,
                'is_sms_sent' => 1,
                'action' => 1,
                'sms_template' => "Hii ,Click on the below link to create request"
            ]
        );

        Mail::to($request->email)->send(new SendMail($request->all()));
        EmailLog::create([
            'role_id' => 2,
            // 'provider_id' => Auth::user()->id,
            'recipient_name' => $name,
            'subject_name' => 'Send mail to patient for submitting request',
            'is_email_sent' => true,
            'action' => 1,
            'sent_tries' => 1,
            'sent_date' => now(),
            'email_template' => 'mail.blade.php',
            'subject_name' => 'Create Request Link',
            'email' => $request->email,
        ]);
        return redirect()->back()->with('linkSent', "Link Sent Successfully!");
    }

    // View Uploads as per the id 
    public function viewUpload(Request $request, $id = null)
    {
        $data  = requestTable::where('id', $id)->first();
        $documents = RequestWiseFile::where('request_id', $id)->orderByDesc('id')->get();

        return view('providerPage.pages.viewUploads', compact('data', 'documents'));
    }
    public function uploadDocument(Request $request, $id = null)
    {
        $request->validate([
            'document' => 'required'
        ], [
            'document.required' => 'Select an File to upload!'
        ]);
        $providerId = RequestTable::where('id', $id)->first()->physician_id;
        $path = $request->file('document')->storeAs('public', $request->file('document')->getClientOriginalName());
        RequestWiseFile::create([
            'request_id' => $id,
            'file_name' => $request->file('document')->getClientOriginalName(),
            'physician_id' => $providerId,
        ]);

        return redirect()->back()->with('uploadSuccessful', "File Uploaded Successfully");
    }
    public function download($id = null)
    {
        $file = RequestWiseFile::where('id', $id)->first();
        $path = (public_path() . '/storage/' . $file->file_name);

        // if ($file->isEmpty()) {
        //     return redirect()->back()->with('noRecordFound', 'There are no records to Delete!');
        // }

        if (!Storage::exists($path)) {
            // Handle case where file not found on disk
            return redirect()->back()->with('FileDoesNotExists', "File You are trying to download doesn't exists");
        }

        return response()->download($path);
    }

    public function deleteDoc(Request $request, $id = null)
    {
        RequestWiseFile::where('id', $id)->delete();

        return redirect()->back();
    }

    public function operations(Request $request)
    {

        $email = request_Client::where('request_id', $request->requestId)->first()->email;
        if ($request->input('operation') == 'delete_all') {
            if (empty($request->input('selected'))) {
                $data = RequestWiseFile::where('request_id', $request->requestId)->get();
                if ($data->isEmpty()) {
                    return redirect()->back()->with('noRecordFound', 'There are no records to Delete!');
                }
                $ids = RequestWiseFile::where('request_id', $request->requestId)->get()->pluck('id')->toArray();
            } else {
                $ids = $request->input('selected');
            }
            RequestWiseFile::whereIn('id', $ids)->delete();

            return redirect()->back();
        } else if ($request->input('operation') == 'download_all') {
            if (empty($request->input('selected'))) {
                $data = RequestWiseFile::where('request_id', $request->requestId)->get();
                if ($data->isEmpty()) {
                    return redirect()->back()->with('noRecordFound', 'There are no records to download!');
                }
                $ids = RequestWiseFile::where('request_id', $request->requestId)->get()->pluck('id')->toArray();
            } else {
                $ids = $request->input('selected');
            }

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
        } else if ($request->input('operation') == 'send_mail') {

            $data = RequestWiseFile::where('request_id', $request->requestId)->get();
            if ($data->isEmpty()) {
                return redirect()->back()->with('noRecordFound', 'There are no records to Send Mail!');
            }

            $request->validate([
                'selected' => 'required'
            ], [
                'selected.required' => 'Please select at least one record.'
            ]);

            $ids = $request->input('selected');

            $zip = new ZipArchive;
            $zipFile = $email . '.zip';

            if ($zip->open(public_path($zipFile), ZipArchive::CREATE) === TRUE) {
                foreach ($ids as $id) {
                    $file = RequestWiseFile::where('id', $id)->first();
                    $path = (public_path() . '/storage/' . $file->file_name);

                    $zip->addFile($path, $file->file_name);
                }
                $zip->close();
            }
            EmailLog::create([
                'role_id' => 1,
                'is_email_sent' => true,
                'sent_tries' => 1,
                'sent_date' => now(),
                'email_template' => 'mail.blade.php',
                'subject_name' => 'Documets Link Sent',
                'email' => $email,
            ]);
            Mail::to($email)->send(new DocsAttachmentMail($email, $zipFile));
            // return response()->download(public_path($zipFile))->deleteFileAfterSend(true);

            return redirect()->back()->with('mailDocsSent', 'Mail of all the selected documents is sent!');
        }
    }

    public function viewOrder(Request $request, $id = null)
    {
        $data = RequestTable::where('id', $id)->first();
        $types = HealthProfessionalType::get();
        return view('providerPage.pages.sendOrder',  compact('id', 'types', 'data'));
    }

    // Send orders from action menu 
    public function sendOrder(Request $request)
    {
        $request->validate([
            'profession' => 'required',
            'vendor_id' => 'required',
            'business_contact' => 'required',
            'email' => 'required|email',
            'fax_number' => 'required',

        ]);
        Orders::create([
            'vendor_id' => $request->vendor_id,
            'request_id' => $request->requestId,
            'fax_number' => $request->fax_number,
            'business_contact' => $request->business_contact,
            'email' => $request->email,
            'prescription' => $request->prescription,
            'no_of_refill' => $request->refills,
        ]);

        return redirect()->route('provider.status', 'active')->with('orderPlaced', 'Order Created Successfully!');
    }


    public function sendAgreementLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone_number' => 'required'
        ]);
        $clientData = RequestTable::with('requestClient')->where('id', $request->request_id)->first();
        $id = $request->request_id;
        EmailLog::create([
            'role_id' => 2,
            'provider_id' => $request->providerId,
            'subject_name' => 'Agreement Link Sent to Patient',
            'create_date' => now(),
            'sent_date' => now(),
            'is_email_sent' => 1,
            'action' => 4,
            'recipient_name' => $clientData->requestClient->first_name . " " . $clientData->requestClient->last_name,
            'email_template' => 'sendAgreementLink.blade.php',
            'email' => $request->email,
            'sent_tries' => 1,
        ]);
        Mail::to($request->email)->send(new SendAgreement($clientData));

        // send SMS 
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $senderNumber = getenv("TWILIO_PHONE_NUMBER");

        $twilio = new Client($sid, $token);

        // $message = $twilio->messages
        //     ->create(
        //         "+91 99780 71802", // to
        //         [
        //             "body" => "Hii " .  $clientData->requestClient->first_name . $clientData->requestClient->last_name . ", Click on the this link to open Agreement:" . url('/patientAgreement/' . $id),
        //             "from" =>  $senderNumber
        //         ]
        //     );

        SMSLogs::create(
            [
                'mobile_number' => $request->phone_number,
                'created_date' => now(),
                'sent_date' => now(),
                'role_id' => 2,
                'sent_tries' => 1,
                'is_sms_sent' => 1,
                'action' => 4,
                'sms_template' => "Hii ,Click on the below link to create request"
            ]
        );
        return redirect()->back()->withErrors('email', 'phone_number');
    }

    public function viewConcludeCare($id)
    {
        $case = RequestTable::where('id', $id)->first();
        $docs = RequestWiseFile::where('request_id', $id)->get();
        return view('providerPage.concludeCare', compact('case', 'docs'));
    }

    public function concludeCare(Request $request)
    {
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

        return redirect()->route('provider.status', 'conclude')->with('CaseConcluded', 'Case Concluded Successfully!');
    }
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
