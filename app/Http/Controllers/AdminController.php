<?php

namespace App\Http\Controllers;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Menu;

// Different Models used in these Controller
use App\Models\Role;
use App\Models\Admin;
use App\Models\Roles;

// Different Models used in these Controller
use App\Models\users;
use App\Mail\SendLink;
use App\Mail\SendMail;
use App\Models\Orders;
use App\Models\caseTag;

use App\Models\Regions;

// For sending Mails
use App\Models\SMSLogs;
use Twilio\Rest\Client;
use App\Models\allusers;
use App\Models\EmailLog;
use App\Models\Provider;
use App\Models\RoleMenu;
use App\Models\UserRoles;

// DomPDF package used for the creation of pdf from the form
use App\Mail\SendAgreement;
// To create zip, used to download multiple documents at once
use App\Models\AdminRegion;
use App\Models\BlockRequest;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\MedicalReport;
use App\Models\RequestClosed;
use App\Models\RequestStatus;
use App\Models\request_Client;
use App\Models\PhysicianRegion;
use App\Models\RequestBusiness;
use App\Models\RequestWiseFile;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\RequestConcierge;
use App\Models\HealthProfessional;
use Illuminate\Support\Facades\DB;
use App\Exports\SearchRecordExport;
use App\Mail\RequestSupportMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\HealthProfessionalType;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\RequestStack;


class AdminController extends Controller
{
    public function totalCasesCount()
    {
        // Total count of cases as per the status (displayed in all listing pages)
        return [
            // unassigned case, assigned to provider but not accepted
            'newCase' => RequestTable::where('status', 1)->count(),
            //accepted by provider, pending state
            'pendingCase' => RequestTable::where('status', 3)->count(),
            //MDEnRoute(agreement sent and accepted by patient), MDOnSite(call type selected by provider)
            'activeCase' => RequestTable::whereIn('status', [4, 5])->count(),
            'concludeCase' => RequestTable::where('status', 6)->count(),
            'tocloseCase' => RequestTable::whereIn('status', [2, 7, 11])->count(),
            'unpaidCase' => RequestTable::where('status', 9)->count(),
        ];
    }
    // Build Query as per filters, search query or normal cases
    public function buildQuery($status, $category, $searchTerm)
    {
        if (is_array($this->getStatusId($status))) {
            $query = RequestTable::with('requestClient')->whereIn('status', $this->getStatusId($status));
        } else {
            $query = RequestTable::with('requestClient')->where('status', $this->getStatusId($status));
        }

        // Filter by Category if not 'all'
        if ($category !== 'all') {
            $query->where('request_type_id', $this->getCategoryId($category));
        }

        // Apply search condition
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('first_name', 'like', "%$searchTerm%")
                    ->orWhereHas('requestClient', function ($q) use ($searchTerm) {
                        $q->where('first_name', 'like', "%$searchTerm%");
                    });
            });
        }
        return $query;
    }

    // Method to retrieve cases based on status, category, and search term
    public function cases(Request $request, $status = 'new', $category = "all")
    {
        $searchTerm = $request->search;
        $userData = Auth::user();
        $count = $this->totalCasesCount();
        $query = $this->buildQuery($status, $category, $searchTerm);

        $cases = $query->orderByDesc('id')->paginate(10);

        // dd($query->get());
        $viewName = 'adminPage.adminTabs.admin' . ucfirst($status) . 'Listing';
        return view($viewName, compact('cases', 'count', 'userData'));
    }

    // Admin dashboard
    public function adminDashboard()
    {
        return redirect('/admin/new');
    }

    // Display Provider Listing/Dashboard page as per the Tab Selected (By default it's "new")
    public function status(Request $request, $status = 'new')
    {
        return $this->cases($request, $status);
    }

    // Filter as per the button clicked in listing pages (Here we need both, the status and which button was clicked)
    public function adminFilter(Request $request, $status = 'new', $category = 'all')
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
    private function getStatusId($status)
    {
        $statusMapping = [
            'new' => 1,
            'pending' => 3,
            'active' => [4, 5],
            'conclude' => 6,
            'toclose' => [2, 7, 11],
            'unpaid' => 9,
        ];
        return $statusMapping[$status];
    }

    // Assign case - All physician Regions
    public function physicianRegions()
    {
        $regions = Regions::get();
        return response()->json($regions);
    }

    public function getPhysicians($id = null)
    {
        $physiciansId = PhysicianRegion::where('region_id', $id)->pluck('provider_id')->toArray();
        $physicians = Provider::whereIn('id', $physiciansId)->get()->toArray();
        return response()->json($physicians);
    }
    // assign Case login 
    public function assignCase(Request $request)
    {
        $request->validate([
            'physician' => 'required|numeric',
            'assign_note' => 'required'

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

    public function transferCase(Request $request)
    {
        $request->validate([
            'physician' => 'required|numeric',
            'notes' => 'required'
        ]);
        RequestTable::where('id', $request->requestId)->update([
            'status' => 1,
            'physician_id' => $request->physician
        ]);
        RequestStatus::create([
            'request_id' => $request->requestId,
            'TransToPhysicianId' => $request->physician,
            'status' => "1",
            'admin_id' => '1',
            'notes' => $request->notes
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
            'case_tag' => 'required|in:1,2,3,4'
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

        return redirect()->back()->with('caseCancelled', 'Case Cancelled Successfully!');
    }

    // Admin Blocks patient
    public function blockCase(Request $request)
    {
        $request->validate([
            'block_reason' => 'required'
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
        $data = RequestTable::where('id', $id)->first();
        return view('adminPage.pages.viewCase', compact('data'));
    }

    // View Notes
    public function viewNote($id)
    {
        $data = RequestTable::where('id', $id)->first();
        $note = RequestNotes::where('request_id', $id)->first();
        $adminAssignedCase = RequestStatus::with('transferedPhysician')->where('request_id', $id)->where('status', 1)->whereNotNull('TransToPhysicianId')->orderByDesc('id')->first();
        $providerTransferedCase = RequestStatus::with('provider')->where('request_id', $id)->where('status', 3)->where('TransToAdmin', true)->orderByDesc('id')->first();
        $adminTransferedCase = RequestStatus::with('transferedPhysician')->where('request_id', $id)->where('status', 1)->whereNotNull('TransToPhysicianId')->orderByDesc('id')->first();
        // dd($providerTransferedCase);
        return view('adminPage.pages.viewNotes', compact('id', 'note', 'adminAssignedCase', 'providerTransferedCase', 'adminTransferedCase', 'data'));
    }

    public function storeNote(Request $request)
    {
        $request->validate([
            'admin_note' => 'required'
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

        return redirect()->route('admin.view.note', compact('id'))->with('adminNoteAdded', 'Your Note Successfully Added');
    }

    public function viewUpload($id)
    {
        $data  = requestTable::where('id', $id)->first();
        $documents = RequestWiseFile::where('request_id', $id)->orderByDesc('id')->get();
        return view('adminPage.pages.viewUploads', compact('data', 'documents'));
    }
    public function uploadDocument(Request $request, $id = null)
    {
        $request->validate([
            'document' => 'required'
        ], [
            'document.required' => 'Select an File to upload!'
        ]);
        // $providerId = RequestTable::where('id', $id)->first()->physician_id;
        $path = $request->file('document')->storeAs('public', $request->file('document')->getClientOriginalName());
        RequestWiseFile::create([
            'request_id' => $id,
            'file_name' => $request->file('document')->getClientOriginalName(),
            'admin_id' => 1,
        ]);

        return redirect()->back()->with('uploadSuccessful', "File Uploaded Successfully");
    }

    // show a new medical form or an existing one when clicked encounter button in conclude listing
    public function encounterFormView(Request $request, $id = "null")
    {
        $data = MedicalReport::where('request_id', $id)->first();
        $requestData = RequestTable::where('id', $id)->first();
        return view('adminPage.adminEncounterForm', compact('data', 'id', 'requestData'));
    }

    public function encounterForm(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'email' => 'required|email',
            'mobile' => 'sometimes|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/'
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

    // ****************** This code is for Sending Link ************************

    public function sendMail(Request $request)
    {

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email'
        ]);

        $firstname = $request->first_name;
        $lastname = $request->last_name;

        // Route name 
        $routeName = 'submitRequest';

        // Generate the link using route() helper (assuming route parameter is optional)
        $link = route($routeName);

        Mail::to($request->email)->send(new SendLink($request->all()));

        // send SMS 
        $sid = getenv("TWILIO_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $senderNumber = getenv("TWILIO_PHONE_NUMBER");

        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create(
                "+91 99780 71802", // to
                [
                    "body" => "Hii $firstname $lastname, Click on the this link to create request:$link",
                    "from" =>  $senderNumber
                ]
            );

        EmailLog::create([
            'role_id' => 1,
            'is_email_sent' => true,
            'sent_tries' => 1,
            'sent_date' => now(),
            'email_template' => 'mail.blade.php',
            'subject_name' => 'Create Request Link',
            'email' => $request->email,
            'recipient_name'=>$request->first_name . ' ' . $request->last_name,
        ]);

        SMSLogs::create([
                'role_id' => 1,
                'mobile_number' => $request->phone_number,
                'created_date' => now(),
                'sent_date' => now(),
                'recipient_name' => $request->first_name  . ' ' . $request->last_name,
                'sent_tries' => 1,
                'is_sms_sent' => 1,
                'action' => 1,
                'sms_template' => "Hii ,Click on the below link to create request"
            ]
        );

        return redirect()->back()->with('linkSent', "Link Sent Successfully!");
    }


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

    public function closeCase(Request $request, $id = null)
    {
        $data = RequestTable::where('id', $id)->first();
        $files = RequestWiseFile::where('request_id', $id)->get();
        return view('adminPage.pages.closeCase', compact('data', 'files'));
    }
    public function closeCaseData(Request $request)
    {
        if ($request->input('closeCaseBtn') == 'Save') {
            $request->validate([
                'phone_number' => 'required',
                'email' => 'required|email'
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

    // Show Partners page in Admin
    public function viewPartners($id = null)
    {
        if ($id == null || $id == '0') {
            $vendors = HealthProfessional::with('healthProfessionalType')->orderByDesc('id')->paginate(10);
        } else if ($id) {
            $vendors = HealthProfessional::with('healthProfessionalType')->where('profession', $id)->orderByDesc('id')->paginate(10);
        }
        $professions = HealthProfessionalType::get();

        return view('adminPage.partners.partners', compact('vendors', 'professions', 'id'));
    }

    // For Searching and filtering Partners
    public function searchPartners(Request $request)
    {
        $search = $request->get('search');
        $id = $request->get('profession');
        $page = $request->query('page') ?? 1; // Default to page 1 if no page number provided

        // dd($request->page);

        $query = HealthProfessional::with('healthProfessionalType');

        if ($search) {
            $query->where('vendor_name', 'like', "%$search%");
        }

        if ($id != 0) {
            $query->where('profession', $id);
            if ($search) {
                $query->where('profession', $id)->where('vendor_name', 'like', "%$search%");
            }
        }

        $vendors = $query->orderByDesc('id')->paginate(10, ['*'], 'page', $page);
        $professions = HealthProfessionalType::get();

        return view('adminPage.partners.partners', compact('vendors', 'professions', 'id'));
    }

    // Add Business page
    public function addBusinessView()
    {
        $types = HealthProfessionalType::get();
        return view('adminPage.partners.addBusiness', compact('types'));
    }

    // Add Business Logic
    public function addBusiness(Request $request)
    {
        $request->validate([
            'business_name' => 'required',
            'profession' => 'required|numeric',
            'fax_number' => 'required|numeric',
            'mobile' => 'required',
            'email' => 'required',
            'business_contact' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
        ]);
        HealthProfessional::create([
            'vendor_name' => $request->business_name,
            'profession' => $request->profession,
            'fax_number' => $request->fax_number,
            'phone_number' => $request->mobile,
            'email' => $request->email,
            'business_contact' => $request->business_contact,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'address' => $request->street,
        ]);

        return redirect()->route('admin.partners')->with('businessAdded', 'Business Added Successfully!');
    }

    // update Business Page
    public function updateBusinessView($id)
    {
        // HealthProfessional Id whose value need to be updated
        $vendor = HealthProfessional::where('id', $id)->first();
        $professions = HealthProfessionalType::get();
        return view('adminPage.partners.updateBusiness', compact("vendor", 'professions'));
    }
    public function updateBusiness(Request $request)
    {
        HealthProfessional::where('id', $request->vendor_id)->update([
            'vendor_name' => $request->buisness_name,
            'profession' => $request->profession,
            'fax_number' => $request->fax_number,
            'phone_number' => $request->mobile,
            'email' => $request->email,
            'business_contact' => $request->business_contact,
            'address' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip
        ]);
        return redirect()->back()->with('changesSaved', 'Changes Saved Successfully!');
    }

    public function viewOrder($id = null)
    {
        $data = RequestTable::where('id', $id)->first();
        $types = HealthProfessionalType::get();
        return view('adminPage.pages.sendOrder', compact('id', 'types', 'data'));
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

        $status = RequestTable::where('id', $request->requestId)->first()->status;

        return redirect()->route('admin.status', $status == 4 || $status == 5 ? 'active' : ($status == 6 ? 'conclude' : 'toclose'))->with('orderPlaced', 'Order Created Successfully!');
    }
    public function deleteBusiness($id = null)
    {
        HealthProfessional::where('id', $id)->delete();
        return redirect()->back();
    }

    // Fetch business values (health_professional values) as per the profession selected in Send Orders page
    public function fetchBusiness(Request $request, $id)
    {
        $business = HealthProfessional::where('profession', $id)->get();

        return response()->json($business);
    }
    public function fetchBusinessData($id)
    {
        $businessData = HealthProfessional::where('id', $id)->first();

        return response()->json($businessData);
    }

    // Access Page
    public function accessView()
    {
        $roles = Role::orderByDesc('id')->get();
        return view('adminPage.access.access', compact('roles'));
    }
    public function createRoleView()
    {
        $menus = Menu::get();
        return view('adminPage.access.createRole', compact('menus'));
    }

    public function fetchRoles($id = null)
    {
        if ($id == 0) {
            $menus = Menu::get();
            return response()->json($menus);
        } else if ($id == 1) {
            $menus = Menu::where('account_type', 'Admin')->get();
            return response()->json($menus);
        } else if ($id == 2) {
            $menus = Menu::where('account_type', 'Physician')->get();
            return response()->json($menus);
        }
    }

    public function createAccess(Request $request)
    {
        $request->validate([
            'role_name' => 'required',
            'role' => 'required',
            'menu_checkbox' => 'required'
        ]);
        if ($request->role_name == 1) {
            $roleId = Role::insertGetId(['name' => $request->role, 'account_type' => 'admin']);
        } else if ($request->role_name == 2) {
            $roleId = Role::insertGetId(['name' => $request->role, 'account_type' => 'physician']);
        }

        foreach ($request->input('menu_checkbox') as $key => $value) {
            RoleMenu::create([
                'role_id' => $roleId,
                'menu_id' => $value
            ]);
        }
        return redirect()->route('admin.access.view');
    }

    public function deleteAccess($id = null)
    {
        Role::where('id', $id)->delete();
        return redirect()->back();
    }

    public function editAccess($id = null)
    {
        $role = Role::where('id', $id)->first();
        $roleMenus = RoleMenu::where('role_id', $id)->get();
        $menus = Menu::where('account_type', $role->account_type)->get();
        return view('adminPage.access.editAccess', compact('role', 'roleMenus', 'menus'));
    }
    public function editAccessData(Request $request)
    {
        $request->validate([
            'role_name' => 'required',
            'role' => 'required',
            'menu_checkbox' => 'required'
        ]);

        RoleMenu::where('role_id', $request->roleId)->delete();

        foreach ($request->input('menu_checkbox') as $key => $value) {
            RoleMenu::create([
                'role_id' => $request->roleId,
                'menu_id' => $value
            ]);
        }
        return redirect()->route('admin.access.view')->with('accessEdited', 'Your Changes Are successfully Saved!');
    }



    // Records Page
    public function searchRecordsView()
    {

        // This combinedData is the combination of data from RequestClient,Request,RequestNotes,Provider,RequestStatus and Status

        $combinedData = request_Client::distinct()->select([
            'request.request_type_id',
            'request_client.first_name',
            'request_client.id',
            'request_client.email',
            DB::raw('DATE(request_client.created_at) as created_date'),
            'request_client.phone_number',
            'request_client.street',
            'request_client.city',
            'request_client.state',
            'request_client.zipcode',
            'request_notes.patient_notes',
            'request_notes.physician_notes',
            'request_notes.admin_notes',
            'request_status.status',
            'provider.first_name as physician_first_name',
        ])
            ->join('request', 'request.id', '=', 'request_client.request_id')
            ->leftJoin('request_notes', 'request_notes.request_id', '=', 'request_client.request_id')
            ->leftJoin('request_status', 'request_status.request_id', '=', 'request_client.request_id')
            ->leftJoin('provider', function ($join) {
                $join->on('request.physician_id', '=', 'provider.id');
            })
            ->leftJoin('status', 'status.id', '=', 'request_status.status')
            ->paginate(10);


        Session::forget('request_status');
        Session::forget('request_type');

        return view('adminPage.records.searchRecords', compact('combinedData'));
    }


    public function searchRecordSearching(Request $request)
    {
      
        $combinedData = $this->exportFilteredSearchRecord($request)->paginate(10);

        $session = session([
            'patient_name' => $request->patient_name,
            'from_date_of_service' => $request->from_date_of_service,
            'to_date_of_service' => $request->to_date_of_service,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'provider_name' => $request->provider_name,
        ]);


        if (!empty($request->request_status)) {
            Session::put('request_status', $request->request_status);
        } else {
            Session::forget('request_status');
        }

        if (!empty($request->request_type)) {
            Session::put('request_type', $request->request_type);
        } else {
            Session::forget('request_type');
        }

        return view('adminPage.records.searchRecords', compact('combinedData'));
    }

    public function exportFilteredSearchRecord($request)
    {
        $combinedData = request_Client::distinct()->select([
            'request_client.first_name',
            'request.request_type_id',
            DB::raw('DATE(request_client.created_at) as created_date'),
            'request_client.email',
            'request_client.phone_number',
            'request_client.street',
            'request_client.city',
            'request_client.state',
            'request_client.zipcode',
            'request_status.status',
            'provider.first_name as physician_first_name',
            'request_notes.physician_notes',
            'request_notes.admin_notes',
            'request_notes.patient_notes',
            'request_client.id',
        ])
            ->join('request', 'request.id', '=', 'request_client.request_id')
            ->leftJoin('request_notes', 'request_notes.request_id', '=', 'request_client.request_id')
            ->leftJoin('request_status', 'request_status.request_id', '=', 'request_client.request_id')
            ->leftJoin('provider', function ($join) {
                $join->on('request.physician_id', '=', 'provider.id');
            })
            ->leftJoin('status', 'status.id', '=', 'request_status.status');

        if (!empty($request->patient_name)) {
            $combinedData = $combinedData->where('request_client.first_name', 'like', '%' . $request->patient_name . '%');
        }
        if (!empty($request->email)) {
            $combinedData = $combinedData->orWhere('request_client.email', "like", "%" . $request->email . "%");
        }
        if (!empty($request->phone_number)) {
            $combinedData = $combinedData->orWhere('request_client.phone_number', "like", "%" . $request->phone_number . "%");
        }
        if (!empty($request->request_type)) {
            $combinedData = $combinedData->orWhere('request.request_type_id', "like", "%" . $request->request_type . "%");
        }
        if (!empty($request->provider_name)) {
            $combinedData = $combinedData->orWhere('provider.first_name', "like", "%" . $request->provider_name . "%");
        }
        if (!empty($request->request_status)) {
            $combinedData = $combinedData->orWhere('request_status.status', "like", "%" . $request->request_status . "%");
        }
        if (!empty($request->from_date_of_service)) {
            $combinedData = $combinedData->orWhere('request_client.created_at', "like", "%" . $request->from_date_of_service . "%");
        }

        return $combinedData;
    }


    public function downloadFilteredData(Request $request)
    {
        $data = $this->exportFilteredSearchRecord($request);
        $export = new SearchRecordExport($data);

        return Excel::download($export, 'filtered_data.xls');
    }



    public function deleteSearchRecordData($id)
    {
        $deleteData = request_Client::where('id', $id)->forceDelete();
        $getRequestId = request_Client::select('request_id')->where('id', $id)->first();
        $deleteRequestTableData = Request::where('id', $getRequestId)->forceDelete();
        $deleteDocuments = RequestWiseFile::where('request_id', $getRequestId)->forceDelete();
        $deleteRequestStatus = RequestStatus::where('request_id', $getRequestId)->forceDelete();
        $deleteRequestBusiness = RequestBusiness::where('request_id', $getRequestId)->forceDelete();
        $deleteRequestConcierge = RequestConcierge::where('request_id', $getRequestId)->forceDelete();
        $deleteBlockData = BlockRequest::where('request_id', $getRequestId)->forceDelete();

        return redirect()->back();
    }



    public function emailRecordsView()
    {
        $emails = EmailLog::with(['roles'])->orderByDesc('id')->paginate(10);
        $roleId = null; // Initialize with null
        $receiverName = null; // Initialize with null
        $email = null; // Initialize with null
        $createdDate = null; // Initialize with null
        $sentDate = null; // Initialize with null
        return view('adminPage.records.emailLogs', compact('emails', 'roleId', 'receiverName', 'email', 'createdDate', 'sentDate'));
    }
    public function searchEmail(Request $request)
    {
        $roleId = $request->get('role_id');
        $receiverName = $request->get('receiver_name');
        $email = $request->get('email');
        $createdDate = $request->get('created_date');
        $sentDate = $request->get('sent_date');


        $emails = EmailLog::query();

        // Filter based on role_id (if provided)
        if ($roleId) {
            $emails->where('role_id', $roleId);
        }

        // Filter based on receiver_name (like operator)
        if ($receiverName) {
            $emails->where('recipient_name', 'LIKE', "%{$receiverName}%");
        }

        // Filter based on email (like operator)
        if ($email) {
            $emails->where('email', 'LIKE', "%{$email}%");
        }

        // Filter based on created_date (exact match)
        if ($createdDate) {
            $emails->where('created_at', 'Like',  "%$createdDate%");
        }

        // Filter based on sent_date (exact match)
        if ($sentDate) {
            $emails->where('sent_date', 'Like',  "%$sentDate%");
        }

        // Paginate results (10 items per page)
        $emails = $emails->paginate(10);

        return view('adminPage.records.emailLogs', compact('emails', 'roleId', 'receiverName', 'email', 'createdDate', 'sentDate'));
    }
    public function smsRecordsView()
    {
        $sms = SMSLogs::paginate(10);
        Session::forget('role_type');
        return view('adminPage.records.smsLogs', compact('sms'));
    }

    public function searchSMSLogs(Request $request)
    {
        $sms = SMSLogs::select();

        if (!empty($request->receiver_name)) {
            $sms = $sms->where('sms_log.recipient_name', 'like', '%' . $request->receiver_name . '%');
        }
        if (!empty($request->phone_number)) {
            $sms = $sms->orWhere('sms_log.mobile_number', "like", "%" . $request->phone_number . "%");
        }
        if (!empty($request->created_date)) {
            $sms = $sms->orWhere('sms_log.created_date', "like", "%" . $request->created_date . "%");
        }
        if (!empty($request->sent_date)) {
            $sms = $sms->orWhere('sms_log.sent_date', "like", "%" . $request->sent_date . "%");
        }
        if (!empty($request->role_type)) {
            $sms = $sms->orWhere('sms_log.role_id', "like", "%" . $request->role_type . "%");
        }
        $sms = $sms->paginate(10);


        $session = session(
            [
                'receiver_name' => $request->input('receiver_name'),
                'phone_number' => $request->input('phone_number'),
                'created_date' => $request->input('created_date'),
                'sent_date' => $request->input('sent_date'),
            ]
        );

        if (!empty($request->role_type)) {
            Session::put('role_type', $request->role_type);
        } else {
            Session::forget('role_type');
        }

        return view('adminPage.records.smsLogs', compact('sms'));
    }

    public function blockHistoryView()
    {
        $blockData = BlockRequest::select(
            'block_request.phone_number',
            'block_request.email',
            'block_request.id',
            'block_request.is_active',
            'block_request.request_id',
            DB::raw('DATE(block_request.created_at) as created_date'),
            'block_request.reason',
            'request_client.first_name as patient_name',
        )
            ->leftJoin('request_client', 'block_request.request_id', 'request_client.request_id')
            ->paginate(10);

        return view('adminPage.records.blockHistory', compact('blockData'));
    }

    public function updateBlockHistoryIsActive(Request $request)
    {
        $block = BlockRequest::find($request->blockId);

        $block->update(['is_active' => $request->is_active]);
    }


    public function unBlockPatientInBlockHistoryPage($id)
    {
        $statusUpdateRequestTable = RequestTable::where('id', $id)->update(['status'=>1]);
        $statusUpdateRequestStatus = RequestStatus::where('request_id',$id)->update(['status'=>1]);
        
        $unBlockData = BlockRequest::where('request_id', $id)->delete();
        return redirect()->back()->with('message','patient is unblock');
    }

    public function blockHistroySearchData(Request $request)
    {
        $blockData = BlockRequest::select(
            'request_client.first_name as patient_name',
            'block_request.id',
            'block_request.phone_number',
            'block_request.email',
            'block_request.is_active',
            'block_request.reason',
            DB::raw('DATE(block_request.created_at) as created_date'),
        )
            ->leftJoin('request_client', 'block_request.request_id', 'request_client.request_id');

        if (!empty($request->patient_name)) {
            $blockData = $blockData->where('request_client.first_name', 'like', '%' . $request->patient_name . '%');
        }
        if (!empty($request->email)) {
            $blockData = $blockData->orWhere('block_request.email', "like", "%" . $request->email . "%");
        }
        if (!empty($request->phone_number)) {
            $blockData = $blockData->orWhere('block_request.phone_number', "like", "%" . $request->phone_number . "%");
        }
        if (!empty($request->date)) {
            $blockData = $blockData->orWhere('block_request.created_at', "like", "%" . $request->date . "%");
        }
        $blockData = $blockData->paginate(10);


        $session = session([
            'patient_name' => $request->patient_name,
            'date' => $request->date,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return view('adminPage.records.blockHistory', compact('blockData'));
    }

    public function patientHistoryView()
    {
        $patients = request_Client::paginate(10);
        return view('adminPage.records.patientHistory', compact('patients'));
    }
    public function searchPatientData(Request $request)
    {
        $patients = request_Client::where('first_name', 'LIKE',  "%$request->first_name%")
            ->when($request->last_name, function ($query) use ($request) {
                $query->where('last_name', 'LIKE', "%$request->last_name%");
            })
            ->when($request->email, function ($query) use ($request) {
                $query->where('email', 'LIKE',  "%$request->email%");
            })
            ->when($request->phone_number, function ($query) use ($request) {
                $query->where('phone_number', 'LIKE', "%$request->phone_number%");
            })->paginate(10);

        return view('adminPage.records.patientHistory', compact('patients'));
    }
    public function patientRecordsView($id = null)
    {

        $email = request_Client::where('id', $id)->pluck('email')->first();
        $data = request_Client::where('email', $email)->get();
        $status = RequestStatus::with(['statusTable', 'provider'])->where('request_id', $id)->first();
        // dd($status);
        return view('adminPage.records.patientRecords', compact('data', 'status'));
    }

    // Cancel History Page
    public function viewCancelHistory()
    {
        $cancelCases = RequestStatus::with('request')->where('status', 2)->get();
        return view('adminPage.records.cancelHistory', compact('cancelCases'));
    }
    // search cancel case in Cancel History Page
    public function searchCancelCase(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $date = $request->date;
        $phone_number = $request->phone_number;
        // dd($request->name);
        $query = RequestStatus::where('status', 2);

        $query->whereHas('request', function ($query) use ($request) {
            $query->whereHas('requestClient', function ($clientQuery) use ($request) {
                $clientQuery->where(function ($subQuery) use ($request) {
                    $subQuery->where('first_name', 'LIKE', "%$request->name%")->orWhere('last_name', 'LIKE', "%$request->name%");
                })->when($request->email, function ($query) use ($request) {
                    return $query->where('email', 'LIKE', "%$request->email%");
                })->when($request->phone_number, function ($query) use ($request) {
                    return $query->where('phone_number', 'LIKE', "%$request->phone_number%");
                })->when($request->date, function ($query) use ($request) {
                    return $query->where('request.updated_at', $request->date);
                });
            });
        });

        $cancelCases = $query->get();

        return view('adminPage.records.cancelHistory', compact('cancelCases'));
    }
    public function patientViews()
    {
        return view('adminPage.records.patientRecords');
    }


    public function UserAccess()
    {
        $userAccessData = allusers::select('roles.name', 'allusers.first_name', 'allusers.mobile', 'allusers.status', 'allusers.user_id')
            ->leftJoin('user_roles', 'user_roles.user_id', '=', 'allusers.user_id')
            ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id')
            ->whereIn('user_roles.role_id', [1, 2])
            ->paginate(10);

        return view('adminPage.access.userAccess', compact('userAccessData'));
    }

    public function UserAccessEdit($id)
    {
        $UserAccessRoleName = Roles::select('name')
            ->leftJoin('user_roles', 'user_roles.role_id', 'roles.id')
            ->where('user_roles.user_id', $id)
            ->get();

        if ($UserAccessRoleName->first()->name == 'admin') {
            return redirect()->route('adminProfile', ['id' => $id]);
        } else if ($UserAccessRoleName->first()->name == 'physician') {
            $getProviderId = Provider::where('user_id', $id);
            return redirect()->route('adminEditProvider', ['id' => $getProviderId->first()->id]);
        }
    }

    public function FilterUserAccessAccountTypeWise(Request $request)
    {
        $account = $request->selectedAccount == "all" ? '' : $request->selectedAccount;

        $userAccessDataFiltering = allusers::select('roles.name', 'allusers.first_name', 'allusers.mobile', 'allusers.status', 'allusers.user_id')
            ->leftJoin('user_roles', 'user_roles.user_id', '=', 'allusers.user_id')
            ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id')
            ->whereIn('user_roles.role_id', [1, 2]);

        if (!empty($account) && isset($account)) {
            $userAccessDataFiltering = $userAccessDataFiltering->where('roles.name', '=', $account);
        }
        $userAccessDataFiltering = $userAccessDataFiltering->paginate(10);


        $data = view('adminPage.access.userAccessFiltering')->with('userAccessDataFiltering', $userAccessDataFiltering)->render();

        return response()->json(['html' => $data]);
    }


    public function sendRequestSupport(Request $request)
    {
        $requestMessage = $request->contact_msg;
        Mail::to('recipient@example.com')->send(new RequestSupportMessage($requestMessage));
        return redirect()->back();
    }


    // fetching regions from regions table and show in All Regions drop-down button
    public function fetchRegions()
    {
        $fetchedRegions = Regions::get();
        return response()->json($fetchedRegions);
    }

    // *****  fetching only that data which is filter-by All-Regions drop-down button  ****
    public function filterPatientByRegion(Request $request)
    {
        $status = $request->status;
        $regionId = $request->regionId;
        $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();

        $cases = [];
        if ($status == 'new') {
            $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                $query->where('state', 'like', '%' . $regionName . '%');
            })->where('status', 1)->get();
        } else if ($status == 'pending') {
            $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                $query->where('state', 'like', '%' . $regionName . '%');
            })->where('status', 3)->get();
        } else if ($status == 'active') {
            $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                $query->where('state', 'like', '%' . $regionName . '%');
            })->where('status', 5)->get();
        } else if ($status == 'conclude') {
            $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                $query->where('state', 'like', '%' . $regionName . '%');
            })->where('status', 6)->get();
        } else if ($status == 'toclose') {
            $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                $query->where('state', 'like', '%' . $regionName . '%');
            })->where('status', 7)->get();
        } else if ($status == 'unpaid') {
            $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                $query->where('state', 'like', '%' . $regionName . '%');
            })->where('status', 9)->get();
        }

        // Format the data as needed (optional)
        $formattedData = [];
        foreach ($cases as $patient) {
            $formattedData[] = [
                'request_id' => $patient->request->id,
                'request_type_id' => $patient->request->request_type_id,
                'first_name' => $patient->requestClient->first_name,
                'last_name' => $patient->requestClient->last_name,
                'date_of_birth' => $patient->requestClient->date_of_birth,
                'requestor' => $patient->request->first_name,
                'created_at' => $patient->requestClient->created_at,
                'phone_number' => $patient->requestClient->phone_number,
                'street' => $patient->requestClient->street,
                'city' => $patient->requestClient->city,
                'state' => $patient->requestClient->state,
            ];
        }
        $data = view('adminPage.adminTabs.regions-filter-new')->with('cases', $formattedData)->render();
        return response()->json(['html' => $data]);
    }


    public function filterPatientByRegionActiveState($selectedId)
    {
        $regionName = Regions::where('id', $selectedId)->pluck('region_name')->first();

        $patientData = request_Client::with('request')->where('state', $regionName)->get();

        // Format the data as needed (optional)
        $formattedData = [];
        foreach ($patientData as $patient) {
            $formattedData[] = [
                'request_id' => $patient->request->id,
                'request_type_id' => $patient->request->request_type_id,
                'first_name' => $patient->first_name,
                'last_name' => $patient->last_name,
                'date_of_birth' => $patient->date_of_birth,
                'requestor' => $patient->request->first_name,
                'physician_name' => $patient->request->last_name,
                'created_at' => $patient->created_at,
                'phone_number' => $patient->phone_number,
                'street' => $patient->street,
                'city' => $patient->city,
                'state' => $patient->state,
            ];
        }

        $data = view('adminPage.adminTabs.regions-filter-active')->with('cases', $formattedData)->render();
        return response()->json(['html' => $data]);
    }


    public function filterPatientByRegionConcludeState($selectedId)
    {

        $regionName = Regions::where('id', $selectedId)->pluck('region_name')->first();

        $patientData = request_Client::with('request')->where('state', $regionName)->get();

        // Format the data as needed (optional)
        $formattedData = [];
        foreach ($patientData as $patient) {
            $formattedData[] = [
                'request_id' => $patient->request->id,
                'request_type_id' => $patient->request->request_type_id,
                'first_name' => $patient->first_name,
                'last_name' => $patient->last_name,
                'date_of_birth' => $patient->date_of_birth,
                'requestor' => $patient->request->first_name,
                'physician_name' => $patient->request->last_name,
                'created_at' => $patient->created_at,
                'phone_number' => $patient->phone_number,
                'street' => $patient->street,
                'city' => $patient->city,
                'state' => $patient->state,
            ];
        }

        $data = view('adminPage.adminTabs.regions-filter-conclude')->with('cases', $formattedData)->render();
        return response()->json(['html' => $data]);
    }
    public function filterPatientByRegionToCloseState($selectedId)
    {

        $regionName = Regions::where('id', $selectedId)->pluck('region_name')->first();

        $patientData = request_Client::with('request')->where('state', $regionName)->get();

        // Format the data as needed (optional)
        $formattedData = [];
        foreach ($patientData as $patient) {
            $formattedData[] = [
                'request_id' => $patient->request->id,
                'request_type_id' => $patient->request->request_type_id,
                'first_name' => $patient->first_name,
                'last_name' => $patient->last_name,
                'date_of_birth' => $patient->date_of_birth,
                'requestor' => $patient->request->first_name,
                'physician_name' => $patient->request->last_name,
                'created_at' => $patient->created_at,
                'phone_number' => $patient->phone_number,
                'street' => $patient->street,
                'city' => $patient->city,
                'state' => $patient->state,
            ];
        }

        $data = view('adminPage.adminTabs.regions-filter-to-close')->with('cases', $formattedData)->render();
        return response()->json(['html' => $data]);
    }

    public function filterPatientByRegionUnPaidState($selectedId)
    {

        $regionName = Regions::where('id', $selectedId)->pluck('region_name')->first();

        $patientData = request_Client::with('request')->where('state', $regionName)->get();

        // Format the data as needed (optional)
        $formattedData = [];
        foreach ($patientData as $patient) {
            $formattedData[] = [
                'request_id' => $patient->request->id,
                'request_type_id' => $patient->request->request_type_id,
                'first_name' => $patient->first_name,
                'last_name' => $patient->last_name,
                'date_of_birth' => $patient->date_of_birth,
                'requestor' => $patient->request->first_name,
                'physician_name' => $patient->request->last_name,
                'created_at' => $patient->created_at,
                'phone_number' => $patient->phone_number,
                'street' => $patient->street,
                'city' => $patient->city,
                'state' => $patient->state,
            ];
        }

        $data = view('adminPage.adminTabs.regions-filter-unpaid')->with('cases', $formattedData)->render();
        return response()->json(['html' => $data]);
    }


    public function adminAccount()
    {
        $regions = Regions::get();
        return view("adminPage.createAdminAccount", compact('regions'));
    }

    public function createAdminAccount(Request $request)
    {
        $request->validate([
            'user_name' => 'required',
            'password' => 'required',
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'required|min:2|max:30',
            'email' => 'required|email|',
            'confirm_email' => 'required|email|',
            'phone_number' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'address1' => 'min:2|max:50',
            'address2' => 'min:2|max:50',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'zip' => 'digits:6',
            'alt_mobile' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'role'=>'required',
        ]);
        dd('ss');

        // Store Data in users table

        $adminCredentialsData = new users();
        $adminCredentialsData->username = $request->user_name;
        $adminCredentialsData->password = Hash::make($request->password);
        $adminCredentialsData->email = $request->email;
        $adminCredentialsData->phone_number = $request->phone_number;
        $adminCredentialsData->save();

        // Store Data in Admin Table

        $storeAdminData = new Admin();
        $storeAdminData->user_id = $adminCredentialsData->id;
        $storeAdminData->first_name = $request->first_name;
        $storeAdminData->last_name = $request->last_name;
        $storeAdminData->email = $request->email;
        $storeAdminData->mobile = $request->phone_number;
        $storeAdminData->address1 = $request->address1;
        $storeAdminData->address2 = $request->address2;
        $storeAdminData->city = $request->city;
        $storeAdminData->zip = $request->zip;
        $storeAdminData->alt_phone = $request->alt_mobile;
        $storeAdminData->status ='pending';
        $storeAdminData->role_id = $request->role;
        $storeAdminData->regions_id = $request->state;

        $storeAdminData->save();

        // foreach ($request->region_id as $region) {
        //     AdminRegion::create([
        //         'admin_id' => $storeAdminData->id,
        //         'region_id' => $region
        //     ]);
        // }

        // $data = AdminRegion::where('admin_id', $storeAdminData->id)->pluck('id')->toArray();
        // $ids = implode(',', $data);


        // make entry in user_roles table to identify the user(whether it is admin or physician)
        $user_roles = new UserRoles();
        $user_roles->user_id = $adminCredentialsData->id;
        $user_roles->role_id = 1;
        $user_roles->save();


        // store data in allusers table 
        $adminAllUserData = new allusers();
        $adminAllUserData->user_id = $adminCredentialsData->id;
        $adminAllUserData->first_name = $request->first_name;
        $adminAllUserData->last_name = $request->last_name;
        $adminAllUserData->email = $request->email;
        $adminAllUserData->street = $request->address1;
        $adminAllUserData->city = $request->city;
        $adminAllUserData->zipcode = $request->zip;
        $adminAllUserData->status = 'pending';
        $adminAllUserData->save();

        return redirect()->route('admin.user.access');

    }


    public function fetchRegionsForState()
    {
        $fetchedRegions = Regions::get();
        return response()->json($fetchedRegions);
    }

    public function fetchRolesForAdminAccountCreate(){
        $fetchedRoles = Role::select('id','name')->where('account_type', 'admin')->get();
        return response()->json($fetchedRoles);
    }

}
