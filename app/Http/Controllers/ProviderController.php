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
use Illuminate\Support\Facades\Crypt;
use App\Models\HealthProfessionalType;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProviderController extends Controller
{

    // public $category = ['all' => 1, 2 => 'asasa'];
    // public static const STATUS_FREE  = 1;

    // Counts Total Number of cases for particular Physician and having particular state
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
    /*
    $status type char
    $category type smallInt (1=> On, 0 => Off)
    $searchTerm type long char !null
    $providerId 
    */
    public function buildQuery($status, $category, $searchTerm, $providerId)
    {
        // Check for Status(whether it's single status or multiple)
        if (is_array($this->getStatusId($status))) {
            $query = RequestTable::with('requestClient')->whereIn('status', $this->getStatusId($status))->where('physician_id', $providerId);
        } else {
            $query = RequestTable::with('requestClient')->where('status', $this->getStatusId($status))->where('physician_id', $providerId);
        }

        // Filter by Category if not 'all' (These will enter condition only if there is any filter selected)
        if ($category !== 'all') {
            $query->where('request_type_id', $this->getCategoryId($category));
        }

        // Apply search condition(Enter condition only when any search query is requested)
        if (isset($searchTerm) && !empty($searchTerm)) {
            $query->whereHas('requestClient', function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%$searchTerm%")->orWhere('last_name', 'like', "%$searchTerm%");
            });
        }

        return $query;
    }

    // Method to retrieve cases based on status, category, and search term
    public function cases(Request $request, $status = 'new', $category = "all")
    {
        // $searchTerm = $request->search;

        // Use Session to filter by category and searchTerm
        $searchTerm = $request->session()->get('searchTerm', null);
        $category = $request->session()->get('category', 'all');

        $userData = Auth::user();
        $providerId = Provider::where('user_id', $userData->id)->first()->id;
        $count = $this->totalCasesCount($providerId);
        $query = $this->buildQuery($status, $category, $searchTerm, $providerId);

        $cases = $query->orderByDesc('id')->paginate(10);

        $viewName = 'providerPage.providerTabs.' . $status . 'Listing';
        return view($viewName, compact('cases', 'count', 'userData'));
    }

    // For Provider redirect to new State(By Default)
    public function providerDashboard()
    {
        return redirect('/provider/new');
    }

    // Display Provider Listing/Dashboard page as per the Tab Selected (By default it's "new")
    public function status(Request $request, $status = 'new')
    {
        // Forget from session whenever a new status is opened
        Session::forget(['searchTerm', 'category']);

        if ($status == 'new' || $status == 'pending' || $status == 'active' || $status == 'conclude') {
            return $this->cases($request, $status);
        } else {
            return view('errors.404');
        }
    }

    // Filter as per the button clicked in listing pages (Here we need both, the status and which button was clicked)
    public function filter(Request $request, $status = 'new', $category = 'all')
    {
        // Store category in the session
        $request->session()->put('category', $category);

        if ($status == 'new' || $status == 'pending' || $status == 'active' || $status == 'conclude') {
            if ($category == 'all' || $category == 'patient' || $category == 'family' || $category == 'business' || $category == 'concierge') {
                return $this->cases($request, $status, $category);
            } else {
                return view('errors.404');
            }
        } else {
            return view('errors.404');
        }

        return $this->cases($request, $status, $category);
    }

    // Search for specific keyword in first_name of requestTable and requestclient
    public function search(Request $request, $status = 'new', $category = 'all')
    {
        // store searchTerm in session
        $request->session()->put('searchTerm', $request->search);

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
        return redirect()->back()->with('transferredCase', 'Case Transferred to Admin');
    }

    // Show Provider Request Page
    public function viewCreateRequest()
    {
        return view('providerPage/providerRequest');
    }

    // Create Request Page for Provider
    public function createRequest(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'phone_number' => 'required',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'street' => 'required|min:3|max:30',
            'city' => 'required|min:5|max:30',
            'state' => 'required|min:5|max:30',
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
        $requestClient->email = $request->email;
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

        $user = Auth::user();
        $providerId = Provider::where('user_id', $user->id)->first()->id;

        // send email
        $emailAddress = $request->email;
        Mail::to($request->email)->send(new sendEmailAddress($emailAddress));

        EmailLog::create([
            'role_id' => 3,
            'request_id' =>  $requestTable->id,
            'recipient_name' => $request->first_name . " " . $request->last_name,
            'confirmation_number' => $confirmationNumber,
            'provider_id' => $providerId,
            'is_email_sent' => 1,
            'sent_tries' => 1,
            // 'action' => 5,
            'create_date' => now(),
            'sent_date' => now(),
            'email_template' => 'Create Account With Provided Email',
            'subject_name' => 'Create account by clicking on below link with below email address',
            'email' => $request->email,
        ]);

        return redirect()->route("provider.status", 'new')->withInput()->with('requestCreated', "Request Created Successfully!");
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
            // 'mobile' => 'sometimes|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/'
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
        return redirect()->route('provider.status', 'pending')->with('caseAccepted', "You have Successfully Accepted Case");
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

        return redirect()->route('provider.view.notes', compact('id'))->with('providerNoteAdded', 'Your Note Successfully Added');
    }

    // Send Mail to patient for creating request
    public function sendMail(Request $request)
    {
        // Generate the link using route() helper (assuming route parameter is optional)
        $link = route('submitRequest');

        // Validation 
        $request->validate([
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'phone_number' => 'required',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/'
        ]);

        // send SMS Logic
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

        $user = Auth::user();
        $providerId = Provider::where('user_id', $user->id)->first()->id;

        $name = $request->first_name . " " . $request->last_name;

        SMSLogs::create(
            [
                'sms_template' => "Hii ,Click on the below link to create request",
                'mobile_number' => $request->phone_number,
                'recipient_name' => $name,
                'provider_id' => $providerId,
                'created_date' => now(),
                'sent_date' => now(),
                'role_id' => 2,
                'is_sms_sent' => 1,
                'sent_tries' => 1,
                'action' => 1,
            ]
        );

        // Send Email Logic
        Mail::to($request->email)->send(new SendMail($request->all()));
        EmailLog::create([
            'role_id' => 2,
            'provider_id' => $providerId,
            'recipient_name' => $name,
            'email_template' => 'mail.blade.php',
            'subject_name' => 'Create Request Link',
            'email' => $request->email,
            'create_date' => now(),
            'sent_date' => now(),
            'is_email_sent' => true,
            'sent_tries' => 1,
            'action' => 1,
        ]);

        return redirect()->back()->with('linkSent', "Link Sent Successfully!");
    }

    // View Uploads as per the id 
    public function viewUpload(Request $request, $id = null)
    {
        try {
            $requestId = Crypt::decrypt($id);

            $data  = requestTable::where('id', $requestId)->first();
            $documents = RequestWiseFile::where('request_id', $requestId)->orderByDesc('id')->get();

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
    // Download any sinlge file function
    public function download($id = null)
    {
        $file = RequestWiseFile::where('id', $id)->first();
        $path = (public_path() . '/storage/' . $file->file_name);

        // if ($file->isEmpty()) {
        //     return redirect()->back()->with('noRecordFound', 'There are no records to Delete!');
        // }

        // if (!Storage::exists($path)) {
        //     return redirect()->back()->with('FileDoesNotExists', "File You are trying to download doesn't exists");
        //     // Handle case where file not found on disk
        // }

        return response()->download($path);
    }

    // Delete a single document from viewUploads page
    public function deleteDoc(Request $request, $id = null)
    {
        RequestWiseFile::where('id', $id)->delete();

        return redirect()->back();
    }

    // Perform different options as per the request (Delete All, Download All, Send Mail)
    public function operations(Request $request)
    {
        $email = request_Client::where('request_id', $request->requestId)->first()->email;
        // Delete All Documents or Delete the selected documents
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
            // Download All Documents or Download the selected documents
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
            $zipFile = uniqid() . "-" . 'documents.zip';

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
            // Send Mail of Selected Documents as attachment
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

            return redirect()->back()->with('mailDocsSent', 'Mail of all the selected documents is sent!');
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
            'business_contact' => 'required',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
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

    // Provider/Admin Send Agreement Link to Patient from Pending State
    public function sendAgreementLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required'
        ]);
        $clientData = RequestTable::with('requestClient')->where('id', $request->request_id)->first();

        $user = Auth::user();
        $provider = Provider::where('user_id', $user->id)->first();

        $providerId = DB::raw("NULL");
        $adminId = DB::raw("NULL");

        if ($provider) {
            $roleId = 2;
            $providerId = Provider::where('user_id', $user->id)->first()->id;
        } else {
            $roleId = 1;
            $adminId = Admin::where('user_id', $user->id)->first()->id;
        }

        $id = $request->request_id;
        EmailLog::create([
            'role_id' => $roleId,
            'request_id' => $request->request_id,
            'admin_id' => $adminId,
            'provider_id' => $providerId,
            'recipient_name' => $clientData->requestClient->first_name . " " . $clientData->requestClient->last_name,
            'email_template' => 'sendAgreementLink.blade.php',
            'subject_name' => 'Agreement Link Sent to Patient',
            'email' => $request->email,
            'confirmation_number' => $clientData->confirmation_no,
            'create_date' => now(),
            'sent_date' => now(),
            'is_email_sent' => 1,
            'sent_tries' => 1,
            'action' => 4,
        ]);

        SMSLogs::create(
            [
                'sms_template' => "Hii, Click on the given link to create request",
                'mobile_number' => $request->phone_number,
                'confirmation_number' => $clientData->confirmation_no,
                'recipient_name' => $clientData->requestClient->first_name . " " . $clientData->requestClient->last_name,
                'role_id' => $roleId,
                'admin_id' => $adminId,
                'request_id' => $request->request_id,
                'provider_id' => $providerId,
                'created_date' => now(),
                'sent_date' => now(),
                'is_sms_sent' => 1,
                'sent_tries' => 1,
                'action' => 4,
            ]
        );

        Mail::to($request->email)->send(new SendAgreement($clientData));

        // send SMS 
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $senderNumber = getenv("TWILIO_PHONE_NUMBER");

        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create(
                "+91 99780 71802", // to
                [
                    "body" => "Hii " .  $clientData->requestClient->first_name . " " . $clientData->requestClient->last_name . ", Click on the this link to open Agreement:" . url('/patientAgreement/' . $id),
                    "from" =>  $senderNumber
                ]
            );

        return redirect()->back()->with('agreementSent', 'Agreement sent to patient successfully!');
    }

    // View Conclude Care Page -> Display page and show data
    public function viewConcludeCare($id)
    {
        try {
            $requestId = Crypt::decrypt($id);

            $case = RequestTable::where('id', $requestId)->first();
            $docs = RequestWiseFile::where('request_id', $requestId)->get();

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
        return redirect()->route('provider.status', 'conclude')->with('CaseConcluded', 'Case Concluded Successfully!');
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
