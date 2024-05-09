<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

// Different Models used in these Controller
use App\Models\Role;
use App\Models\Admin;
use App\Models\Roles;
use App\Models\Menu;
use App\Models\Users;
use App\Models\Regions;
use App\Models\SMSLogs;
use App\Models\AllUsers;
use App\Models\EmailLog;
use App\Models\Provider;
use App\Models\RoleMenu;
use App\Models\UserRoles;
use App\Models\AdminRegion;
use App\Models\ShiftDetail;
use App\Models\BlockRequest;
use App\Models\RequestTable;
use App\Models\RequestStatus;
use App\Models\RequestClient;
use App\Models\RequestBusiness;
use App\Models\RequestWiseFile;
use App\Models\RequestConcierge;
use App\Models\HealthProfessional;
use App\Models\HealthProfessionalType;

// For sending Mails
use App\Mail\SendLink;
use Twilio\Rest\Client;
use App\Mail\RequestSupportMessage;

// Export Data with Excel
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NewStatusExport;
use App\Exports\ActiveStatusExport;
use App\Exports\SearchRecordExport;
use App\Exports\UnPaidStatusExport;
use App\Exports\PendingStatusExport;
use App\Exports\ToCloseStatusExport;
use App\Exports\ConcludeStatusExport;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Listing (Cases with different status, category and searchTerm)
    |--------------------------------------------------------------------------
    |
    | Admin Listing pages functionality
    |   1. Total Cases Count
    |   2. Different Status selection
    |   3. Different category selection
    |   4. Search Term request
    */
    public const CATEGORY_PATIENT = 1;
    public const CATEGORY_FAMILY = 2;
    public const CATEGORY_CONCIERGE = 3;
    public const CATEGORY_BUSINESS = 4;

    public const STATUS_NEW = 1;
    public const STATUS_PENDING = 3;
    public const STATUS_ACTIVE = [4, 5];
    public const STATUS_CONCLUDE = 6;
    public const STATUS_TOCLOSE = [2, 7, 11];
    public const STATUS_UNPAID = 9;

    /**
     * Get category id from the name of category
     *
     * @param string $category different category names.
     * @return int different types of request_type_id.
     */
    private function getCategoryId($category)
    {
        // mapping of category names to request_type_id
        $categoryMapping = [
            'patient' => self::CATEGORY_PATIENT,
            'family' => self::CATEGORY_FAMILY,
            'concierge' => self::CATEGORY_CONCIERGE,
            'business' => self::CATEGORY_BUSINESS,
        ];
        return $categoryMapping[$category] ?? null;
    }
    /**
     * Get status id from the name of status
     *
     * @param string $status different status names.
     * @return int status in Id.
     */
    private function getStatusId($status)
    {
        $statusMapping = [
            'new' => self::STATUS_NEW,
            'pending' => self::STATUS_PENDING,
            'active' => self::STATUS_ACTIVE,
            'conclude' => self::STATUS_CONCLUDE,
            'toclose' => self::STATUS_TOCLOSE,
            'unpaid' => self::STATUS_UNPAID,
        ];
        return $statusMapping[$status];
    }

    // For Admin redirect to new State(By Default)
    public function adminDashboard()
    {
        return redirect('/admin/new');
    }

    /**
     *  Counts Total Number of cases for different status
     *
     * @return int array[] total number of cases, as per the status.
     */
    public function totalCasesCount()
    {
        // Total count of cases as per the status (displayed in all listing pages)
        return [
            // unassigned case(Status = 1) -> assigned to provider but not accepted
            'newCase' => RequestTable::where('status', self::STATUS_NEW)->count(),
            // pending state(Status = 3) -> Accepted by provider
            'pendingCase' => RequestTable::where('status', self::STATUS_PENDING)->count(),
            // Active State(Status = 4,5) -> MDEnRoute(agreement sent and accepted by patient), MDOnSite(call type selected by provider[HouseCall])
            'activeCase' => RequestTable::whereIn('status', self::STATUS_ACTIVE)->count(),
            // Conclude State(Status = 6) -> when consult selected during Encounter pop-up or HouseCall Completed
            'concludeCase' => RequestTable::where('status', self::STATUS_CONCLUDE)->count(),
            // toClose State(Status = 2,7,11) -> when provider conclude care or when admin cancel case or agreement cancelled by patient, it moves to ToClose state
            'tocloseCase' => RequestTable::whereIn('status', self::STATUS_TOCLOSE)->count(),
            // toClose State(Status = 9) -> when Admin close case, it will move to unpaid state
            'unpaidCase' => RequestTable::where('status', self::STATUS_UNPAID)->count(),
        ];
    }

    /**
     *  Build Query as per filters, search query or normal cases
     *
     * @param string $status status of the cases [new, active, pending, conclude].
     * @param string $category category of the cases [all, patient, family, business, concierge].
     * @param string $searchTerm search term to filter the cases.
     * @return object $query formed as per the status, category selected, any search term entered
     */
    public function buildQuery($status, $category, $searchTerm, $regionId)
    {
        // Check for Status(whether it's single status or multiple)
        if (is_array($this->getStatusId($status))) {
            $query = RequestTable::with('requestClient')->whereIn('status', $this->getStatusId($status));
        } else {
            $query = RequestTable::with('requestClient')->where('status', $this->getStatusId($status));
        }

        // Filter by Category if not 'all'
        if ($category !== 'all') {
            $query->where('request_type_id', $this->getCategoryId($category));
        }

        // apply regions filter
        if ($regionId !== 'all_regions') {
            $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();
            $query->whereHas('requestClient', function ($q) use ($regionName) {
                $q->where('state', 'like', "%{$regionName}%");
            });
        }

        // Apply search condition
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('first_name', 'like', "%{$searchTerm}%")->orWhere('last_name', 'like', "%{$searchTerm}%")
                    ->orWhereHas('requestClient', function ($q) use ($searchTerm) {
                        $q->where('first_name', 'like', "%{$searchTerm}%")->orWhere('last_name', 'like', "%{$searchTerm}%");
                    });
            });
        }
        return $query;
    }

    /**
     * Method to retrieve cases based on status, category, and search term
     *
     * @param \illuminate\HTTP\Request $request
     * @param string $status different status names.
     * @param string $category different category names.
     * @return \illuminate\View\View
     */
    public function cases(Request $request, $status = 'new', $category = "all")
    {
        // $searchTerm = $request->search;
        // Use Session to filter by category and searchTerm
        $category = $request->session()->get('category', 'all');
        $searchTerm = $request->session()->get('searchTerm', null);
        $regionId = $request->session()->get('regionId', 'all_regions');

        $userData = Auth::user();
        $count = $this->totalCasesCount();
        $query = $this->buildQuery($status, $category, $searchTerm, $regionId);

        $cases = $query->orderByDesc('id')->paginate(10);
        $viewName = 'adminPage.adminTabs.admin' . ucfirst($status) . 'Listing';
        // $viewName = 'adminPage.adminTabs.filter-' . ucfirst($status);
        // $data = view($viewName, compact('cases', 'count', 'userData'))->render();
        // return response()->json(['html' => $data]);
        return view($viewName, compact('cases', 'count', 'userData'));
    }

    /**
     * Display Provider Listing/Dashboard page as per the Tab Selected (By default it's "new")
     *
     * @param \illuminate\HTTP\Request $request
     * @param string $status different status names.
     * @return \illuminate\View\View
     */
    public function status(Request $request, $status = 'new')
    {
        // Forget from session whenever a new status is opened
        Session::forget(['searchTerm', 'category', 'regionId']);
        if ($status === 'new' || $status === 'pending' || $status === 'active' || $status === 'conclude' || $status === 'toclose' || $status === 'unpaid') {
            return $this->cases($request, $status);
        }
        return view('errors.404');
    }

    /**
     * Filter as per the button clicked in listing pages (Here we need both, the status and which button was clicked)
     *
     * @param \illuminate\HTTP\Request $request
     * @param string $status different status names.
     * @param string $category different category names.
     * @return \illuminate\View\View
     */
    public function adminFilter(Request $request, $status = 'new', $category = 'all')
    {
        // Store category in the session
        $request->session()->put('category', $category);

        if ($status === 'new' || $status === 'pending' || $status === 'active' || $status === 'conclude' || $status === 'toclose' || $status === 'unpaid' && $category === 'all' || $category === 'patient' || $category === 'family' || $category === 'business' || $category === 'concierge') {
            return $this->cases($request, $status, $category);
        }
        return view('errors.404');
    }

    /**
     * Search for searchTerm request in first_name & last_name of requestclient or RequestTable
     *
     * @param \illuminate\Http\Request $request
     * @param string $status different status names.
     * @param string $category different category names.
     * @return \illuminate\View\View
     */
    public function search(Request $request, $status = 'new', $category = 'all')
    {
        // store searchTerm in session
        $request->session()->put('searchTerm', $request->search);

        return $this->cases($request, $status, $category);
    }

    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    |
    | Admin Dashboard different functionality
    |   1. Send Link (sendMail)
    |   2. Create Request
    |   3. Export
    |   4. Export All
    |   5. Request DTY Support
    */

    // -------------------- 1. Send Link (sendMail) -------------------
    /**
     * Send Mail to patient with link to create request page
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
    public function sendMail(Request $request)
    {
        $request->validate([
            'first_name' => 'required|alpha|min:5|max:30',
            'last_name' => 'required|alpha|min:5|max:30',
            'phone_number' => 'required',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/'
        ]);

        $firstname = $request->first_name;
        $lastname = $request->last_name;

        // Route name
        $routeName = 'submitRequest';

        // Generate the link using route() helper (assuming route parameter is optional)
        $link = route($routeName);

        try {
            Mail::to($request->email)->send(new SendLink($request->all()));
        } catch (\Throwable $th) {
            return view('errors.500');
        }

        try {
            // send SMS
            $sid = getenv("TWILIO_SID");
            $token = getenv("TWILIO_AUTH_TOKEN");
            $senderNumber = getenv("TWILIO_PHONE_NUMBER");

            $twilio = new Client($sid, $token);

            $twilio->messages
                ->create(
                    "+91 99780 71802", // to
                    [
                        "body" => "Hii {$firstname} {$lastname}, Click on the this link to create request:{$link}",
                        "from" =>  $senderNumber
                    ]
                );
        } catch (\Throwable $th) {
            return view('errors.500');
        }

        EmailLog::create([
            'role_id' => 1,
            'is_email_sent' => true,
            'sent_tries' => 1,
            'create_date' => now(),
            'sent_date' => now(),
            'email_template' => 'mail.blade.php',
            'subject_name' => 'Create Request Link',
            'email' => $request->email,
            'recipient_name' => $request->first_name . ' ' . $request->last_name,
            'action' => 1
        ]);

        SMSLogs::create(
            [
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

        return redirect()->back()->with('successMessage', "Link Sent Successfully!");
    }
    // -------------------- 2. Create Request -------------------------
    // -------------------- 3. Export ---------------------------------
    // -------------------- 4. Export All -----------------------------
    // -------------------- 5. Request DTY Support --------------------

    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard -> Menu-Bar different pages
    |--------------------------------------------------------------------------
    |
    | Admin Dashboard Menu-bar pages functionality
    |   1. Provider Location
    |   2. My Profile
    |   3. Providers
    |       3.1 : Provider
    |       3.2 : Scheduling
    |   4. Partners
    |   5. Access
    |       5.1 : User Access
    |       5.2 : Account Access
    |   6. Records
    |       6.1 : Search Records
    |       6.2 : Email Logs
    |       6.3 : SMS Logs
    |       6.4 : Patient Records
    |       6.5 : Blocked History
    */

    // -------------------- 1. Provider Location --------------------
    // -------------------- 2. My Profile ---------------------------
    // -------------------- 3. Providers ----------------------------
    // --------- 3.1 : Provider ----------
    // --------- 3.2 : Scheduling --------
    // Admin Scheduling -> Implementation and functionality is in Scheduling Controller
    // -------------------- 4. Partners -----------------------------
    /**
     * Display Partners/Vendors page
     *
     * @param int $id healthProfessionalType id to show selection on dropdown
     * @return \Illuminate\View\View partners page
     */
    public function viewPartners($id = null)
    {
        if ($id == null || $id == '0') {
            $vendors = HealthProfessional::with('healthProfessionalType')->orderByDesc('id')->paginate(10);
        } elseif ($id) {
            $vendors = HealthProfessional::with('healthProfessionalType')->where('profession', $id)->orderByDesc('id')->paginate(10);
        }
        $professions = HealthProfessionalType::get();
        $search = null;

        return view('adminPage.partners.partners', compact('vendors', 'professions', 'id', 'search'));
    }

    /**
     * For Searching and filtering Partners
     *
     * Filtering partners based on healthProfessionalType or by search term query to search by business name
     *
     * @param Request $request
     * @return \Illuminate\View\View partners page
     */
    public function searchPartners(Request $request)
    {
        $search = $request->get('search');
        $id = $request->get('profession');
        $page = $request->query('page') ?? 1; // Default to page 1 if no page number provided

        $query = HealthProfessional::with('healthProfessionalType');

        if ($search) {
            $query->where('vendor_name', 'like', "%{$search}%");
        }

        if ($id !== 0) {
            $query->where('profession', $id);
            if ($search) {
                $query->where('profession', $id)->where('vendor_name', 'like', "%{$search}%");
            }
        }

        $vendors = $query->orderByDesc('id')->paginate(10, ['*'], 'page', $page);
        $professions = HealthProfessionalType::get();

        return view('adminPage.partners.partners', compact('vendors', 'professions', 'id', 'search'));
    }

    /**
     * Display Add Business page
     *
     * @return \Illuminate\View\View partners page
     */
    public function addBusinessView()
    {
        $types = HealthProfessionalType::get();
        return view('adminPage.partners.addBusiness', compact('types'));
    }

    /**
     * Add Business entry in partners/vendors
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
    public function addBusiness(Request $request)
    {
        $request->validate([
            'business_name' => 'required',
            'profession' => 'required|numeric',
            'fax_number' => 'required|numeric',
            'mobile' => 'required',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
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

    /**
     * Display the form to update the business page.
     *
     * @param string $id
     * @return \Illuminate\Contracts\View\View
     */
    public function updateBusinessView($id)
    {
        try {
            $caseId = Crypt::decrypt($id);
            // HealthProfessional Id whose value need to be updated
            $vendor = HealthProfessional::where('id', $caseId)->first();
            $professions = HealthProfessionalType::get();
            return view('adminPage.partners.updateBusiness', compact("vendor", 'professions'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * Update business data based on the provided request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBusiness(Request $request)
    {
        $request->validate([
            'business_name' => 'required',
            'profession' => 'required|numeric',
            'fax_number' => 'required|numeric',
            'mobile' => 'required',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'business_contact' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
        ]);

        HealthProfessional::where('id', $request->vendor_id)->update([
            'vendor_name' => $request->business_name,
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

    /**
     * Delete a business from the vendors page.
     *
     * @param int|null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteBusiness($id = null)
    {
        HealthProfessional::where('id', $id)->delete();
        return redirect()->back();
    }
    // -------------------- 5. Access -------------------------------
    // --------- 5.1 : User Access --------
    // --------- 5.2 : Account Access -----

    /**
     * Display the access page.
     *
     * @return \Illuminate\View\View
     */
    public function accessView()
    {
        $roles = Role::orderByDesc('id')->get();
        return view('adminPage.access.access', compact('roles'));
    }

    /**
     * Display the create role page.
     *
     * @return \Illuminate\View\View
     */
    public function createRoleView()
    {
        $menus = Menu::get();
        return view('adminPage.access.createRole', compact('menus'));
    }

    /**
     * Fetch roles data from the Menu table based on the given ID.
     *
     * @param int|null $id The ID of the account type (optional).
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchRoles($id = null)
    {
        if ($id === 0) {
            $menus = Menu::get();
            return response()->json($menus);
        }
        if ($id === 1) {
            $menus = Menu::where('account_type', 'Admin')->get();
            return response()->json($menus);
        }
        if ($id === 2) {
            $menus = Menu::where('account_type', 'Physician')->get();
            return response()->json($menus);
        }
    }

    /**
     * Creating different Access for different roles
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing role information and menu checkboxes.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAccess(Request $request)
    {
        $request->validate([
            'role_name' => 'required',
            'role' => 'required',
            'menu_checkbox' => 'required'
        ]);
        if ($request->role_name === 1) {
            $roleId = Role::insertGetId(['name' => $request->role, 'account_type' => 'admin']);
        } elseif ($request->role_name === 2) {
            $roleId = Role::insertGetId(['name' => $request->role, 'account_type' => 'physician']);
        }

        foreach ($request->input('menu_checkbox') as $value) {
            RoleMenu::create([
                'role_id' => $roleId,
                'menu_id' => $value
            ]);
        }
        return redirect()->route('admin.access.view')->with('accessOperation', 'New access created successfully!');
    }

    /**
     * Deletes a complete role.
     *
     * @param int|null $id The ID of the role to be deleted.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAccess($id = null)
    {
        Role::where('id', $id)->delete();
        return redirect()->back()->with('accessOperation', 'Access role deleted successfully!');
    }

    /**
     * Displays the edit Access Page with pre-filled data.
     *
     * @param int|null $id The ID of the role to be edited.
     * @return \Illuminate\View\View
     */
    public function editAccess($id = null)
    {
        try {
            $roleId = Crypt::decrypt($id);

            $role = Role::where('id', $roleId)->first();
            $roleMenus = RoleMenu::where('role_id', $roleId)->get();
            $menus = Menu::where('account_type', $role->account_type)->get();
            return view('adminPage.access.editAccess', compact('role', 'roleMenus', 'menus'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * Edit Access of a role previously created.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing the role data.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editAccessData(Request $request)
    {
        $request->validate([
            'role_name' => 'required',
            'role' => 'required',
            'menu_checkbox' => 'required'
        ]);

        Role::where('id', $request->roleId)->update([
            'name' => $request->role,
            // 'account_type' => $request->role,
        ]);

        RoleMenu::where('role_id', $request->roleId)->delete();

        foreach ($request->input('menu_checkbox') as $value) {
            RoleMenu::create([
                'role_id' => $request->roleId,
                'menu_id' => $value
            ]);
        }
        return redirect()->route('admin.access.view')->with('accessOperation', 'Your Changes Are successfully Saved!');
    }
    // -------------------- 6. Records -------------------------------
    // --------- 6.1 : Search Records -----
    // --------- 6.2 : Email Logs ---------
    /**
     * Display EmailLogs pages with all the log data.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function emailRecordsView()
    {
        $emails = EmailLog::with(['roles'])->orderByDesc('id')->paginate(10);

        return view('adminPage.records.emailLogs', compact('emails'));
    }

    /**
     * Search/filter EmailLogs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function searchEmail(Request $request)
    {
        $roleId = $request->get('role_id');
        $receiverName = $request->get('receiver_name');
        $email = $request->get('email');
        $createdDate = $request->get('created_date');
        $sentDate = $request->get('sent_date');

        // Retrieve pagination parameters from the request
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

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
            $emails->where('created_at', 'Like', "%{$createdDate}%");
        }

        // Filter based on sent_date (exact match)
        if ($sentDate) {
            $emails->where('sent_date', 'Like', "%{$sentDate}%");
        }

        // Paginate results (10 items per page)
        $emails = $emails->paginate($perPage, ['*'], 'page', $page);
        $emails->appends(request()->query());

        return view('adminPage.records.emailLogs', compact('emails', 'roleId', 'receiverName', 'email', 'createdDate', 'sentDate'));
    }
    // --------- 6.3 : SMS Logs -----------
    // --------- 6.4 : Patient Records ----
    /**
     * Display the patient history page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function patientHistoryView()
    {
        $patients = RequestClient::paginate(10);

        return view('adminPage.records.patientHistory', compact('patients'));
    }

    /**
     * Search patient data based on provided criteria.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function searchPatientData(Request $request)
    {
        $firstName = $request->first_name;
        $lastName = $request->last_name;
        $email = $request->email;
        $phoneNumber = $request->phone_number;

        $patients = RequestClient::query();

        if ($firstName) {
            $patients->where('first_name', 'LIKE', "%{$firstName}%");
        }
        if ($lastName) {
            $patients->where('last_name', 'LIKE', "%{$lastName}%");
        }
        if ($email) {
            $patients->where('email', 'LIKE', "%{$email}%");
        }
        if ($phoneNumber) {
            $patients->where('phone_number', 'LIKE', "%{$phoneNumber}%");
        }

        $patients = $patients->paginate(10);

        return view('adminPage.records.patientHistory', compact('patients', 'firstName', 'lastName', 'email', 'phoneNumber'));
    }

    /**
     * Display patient records view.
     *
     * @param  string|null  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function patientRecordsView($id = null)
    {
        try {
            $id = Crypt::decrypt($id);
            $email = RequestClient::where('id', $id)->pluck('email')->first();
            $data = RequestClient::with(['request'])->where('email', $email)->get();

            $requestId = RequestClient::where('id', $id)->first()->request_id;
            $documentCount = RequestWiseFile::where('request_id', $requestId)->get()->count();
            $isFinalize = RequestWiseFile::where('request_id', $requestId)->where('is_finalize', true)->first();

            return view('adminPage.records.patientRecords', compact('data', 'documentCount', 'isFinalize'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * Display patient records page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function patientViews()
    {
        return view('adminPage.records.patientRecords');
    }

    // --------- 6.5 : Blocked History ----



    /**
     * list of  search records
     * it list patient name,email,mobile,address,zip,date of service ,close case date,request type,request status,provider name,
     * physician note,admin note,patient note
     */
    public function searchRecordsView()
    {
        // This combinedData is the combination of data from RequestClient,Request,RequestNotes,Provider

        $combinedData = RequestClient::distinct()->select([
            'request.request_type_id',
            'request_client.first_name',
            'request_client.id',
            'request_client.email',
            'request_client.phone_number',
            'request_client.street',
            'request_client.city',
            'request_client.state',
            'request_client.zipcode',
            'request_client.notes',
            'request_notes.physician_notes',
            'request_notes.admin_notes',
            'request.status',
            'provider.first_name as physician_first_name',
            DB::raw('DATE(request_client.created_at) as created_date'),
            DB::raw('DATE(request_closed.created_at) as closed_date'),
        ])
            ->join('request', 'request.id', '=', 'request_client.request_id')
            ->leftJoin('request_notes', 'request_notes.request_id', '=', 'request_client.request_id')
            ->leftJoin('provider', function ($join) {
                $join->on('request.physician_id', '=', 'provider.id');
            })
            ->leftJoin('request_closed', 'request_closed.request_id', '=', 'request_client.request_id')
            ->orderByDesc('id')
            ->paginate(10);

        Session::forget('request_status');
        Session::forget('request_type');

        return view('adminPage.records.searchRecords', compact('combinedData'));
    }

    // *search records filtering

    /**
     *@param $request the input which is use to filter data in search records

     * filter records as per input
     */
    public function searchRecordSearching(Request $request)
    {
        // Retrieve pagination parameters from the request
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $combinedData = $this->exportFilteredSearchRecord($request)->paginate($perPage, ['*'], 'page', $page);

        session([
            'patient_name' => $request->patient_name,
            'from_date_of_service' => $request->from_date_of_service,
            'to_date_of_service' => $request->to_date_of_service,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'provider_name' => $request->provider_name,
        ]);

        // if (!empty($request->request_status)) {
        if ($request->has('request_status')) {
            Session::put('request_status', $request->request_status);
        } else {
            Session::forget('request_status');
        }

        // if (!empty($request->request_type)) {
        if ($request->has('request_type')) {
            Session::put('request_type', $request->request_type);
        } else {
            Session::forget('request_type');
        }

        return view('adminPage.records.searchRecords', compact('combinedData'));
    }



    /**
     *@param $request the input which is use to filter data in search records

     * common function for filtering and exporting to excel
     * it filter as per request
     */

    public function exportFilteredSearchRecord($request)
    {
        $todayDate = now();

        $combinedData = RequestClient::distinct()->select([
            'request_client.first_name',
            'request.request_type_id',
            'request_client.email',
            'request_client.phone_number',
            'request_client.street',
            'request_client.city',
            'request_client.state',
            'request_client.zipcode',
            'request.status',
            'provider.first_name as physician_first_name',
            'request_notes.physician_notes',
            'request_notes.admin_notes',
            'request_notes.patient_notes',
            'request_client.id',
            DB::raw('DATE(request_client.created_at) as created_date'),
            DB::raw('DATE(request_closed.created_at) as closed_date'),
        ])
            ->join('request', 'request.id', '=', 'request_client.request_id')
            ->leftJoin('request_notes', 'request_notes.request_id', '=', 'request_client.request_id')
            ->leftJoin('provider', function ($join) {
                $join->on('request.physician_id', '=', 'provider.id');
            })
            ->leftJoin('request_closed', 'request_closed.request_id', '=', 'request_client.request_id')
            ->orderByDesc('id');

        // if (!empty($request->patient_name)) {
        if ($request->patient_name) {
            $combinedData->where('request_client.first_name', 'like', '%' . $request->patient_name . '%');
        }
        // if (!empty($request->email)) {
        if ($request->email) {
            $combinedData->where('request_client.email', "like", "%" . $request->email . "%");
        }
        // if (!empty($request->phone_number)) {
        if ($request->phone_number) {
            $combinedData->where('request_client.phone_number', "like", "%" . $request->phone_number . "%");
        }
        // if (!empty($request->request_type)) {
        if ($request->request_type) {
            $combinedData->where('request.request_type_id', $request->request_type);
        }
        // if (!empty($request->provider_name)) {
        if ($request->provider_name) {
            $combinedData->where('provider.first_name', "like", "%" . $request->provider_name . "%");
        }
        // if (!empty($request->request_status)) {
        if ($request->request_status) {
            $combinedData->where('request.status', $request->request_status);
        }
        // if (!empty($request->from_date_of_service)) {
        if ($request->from_date_of_service) {
            $combinedData->whereBetween('request_client.created_at', [$request->from_date_of_service, $todayDate]);
        }
        // if (!empty($request->to_date_of_service)) {
        if ($request->to_date_of_service) {
            $combinedData->where('request_client.created_at', "<", $request->to_date_of_service);
        }
        // if (!empty($request->from_date_of_service) && !empty($request->to_date_of_service)) {
        if ($request->from_date_of_service && $request->to_date_of_service) {
            $combinedData->whereBetween('request_client.created_at', [$request->from_date_of_service, $request->to_date_of_service,]);
        }
        return $combinedData;
    }

    /**
     *@param $request the input which is use to filter data in search records

     * export data to excel
     */

    public function downloadFilteredData(Request $request)
    {
        $data = $this->exportFilteredSearchRecord($request);

        if ($data->get()->isEmpty()) {
            return back()->with('message', 'no records to export to Excel');
        } else {
            $export = new SearchRecordExport($data);
            return Excel::download($export, 'search_record_filtered_data.xls');
        }
    }

    /**
     *@param $id  id of request table

     * delete record permanently from request client ,request,block_request,request_concierge,request_business,request_status,request_wise_file
     */

    public function deleteSearchRecordData($id)
    {
        $getRequestId = RequestClient::select('request_id')->where('id', $id)->first()->request_id;

        RequestWiseFile::where('request_id', $getRequestId)->forceDelete();
        RequestStatus::where('request_id', $getRequestId)->forceDelete();
        RequestBusiness::where('request_id', $getRequestId)->forceDelete();
        RequestConcierge::where('request_id', $getRequestId)->forceDelete();
        BlockRequest::where('request_id', $getRequestId)->forceDelete();
        RequestTable::where('id', $getRequestId)->forceDelete();
        RequestClient::where('id', $id)->forceDelete();

        return redirect()->back()->with('message', 'record is permanently delete');
    }


    /**
     * listing of sms
     *
     * it list receipient name ,action,role_name,mobile,create_date,sent_date,confirmation_number,is_sent_sent_tries
     */

    public function smsRecordsView()
    {
        $sms = SMSLogs::orderByDesc('id')->paginate(10);
        Session::forget('role_type');
        return view('adminPage.records.smsLogs', compact('sms'));
    }

    /**
     *@param $request the input which is enter by admin to filter data

     * filter sms logs
     */

    public function searchSMSLogs(Request $request)
    {
        // Retrieve pagination parameters from the request
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $sms = SMSLogs::select();

        // if (!empty($request->receiver_name)) {
        if ($request->receiver_name) {
            $sms->where('sms_log.recipient_name', 'like', '%' . $request->receiver_name . '%');
        }
        // if (!empty($request->phone_number)) {
        if ($request->phone_number) {
            $sms->where('sms_log.mobile_number', "like", "%" . $request->phone_number . "%");
        }
        // if (!empty($request->created_date)) {
        if ($request->created_date) {
            $sms->where('sms_log.created_date', "like", "%" . $request->created_date . "%");
        }
        // if (!empty($request->sent_date)) {
        if ($request->sent_date) {
            $sms->where('sms_log.sent_date', "like", "%" . $request->sent_date . "%");
        }
        // if (!empty($request->role_type)) {
        if ($request->role_type) {
            $sms->where('sms_log.role_id', "like", "%" . $request->role_type . "%");
        }
        $sms = $sms->paginate($perPage, ['*'], 'page', $page);

        session(
            [
                'receiver_name' => $request->input('receiver_name'),
                'phone_number' => $request->input('phone_number'),
                'created_date' => $request->input('created_date'),
                'sent_date' => $request->input('sent_date'),
            ]
        );

        // if (!empty($request->role_type)) {
        if ($request->role_type) {
            Session::put('role_type', $request->role_type);
        } else {
            Session::forget('role_type');
        }

        return view('adminPage.records.smsLogs', compact('sms'));
    }


    // *Block History

    /**
     * List of block request
     *
     * it list patient name,mobile,email,created date and notes
     */
    public function blockHistoryView()
    {
        $blockData = BlockRequest::select(
            'block_request.phone_number',
            'block_request.email',
            'block_request.id',
            'block_request.is_active',
            'block_request.request_id',
            'block_request.reason',
            'request_client.first_name as patient_name',
            DB::raw('DATE(block_request.created_at) as created_date'),
        )
            ->leftJoin('request_client', 'block_request.request_id', 'request_client.request_id')
            ->orderByDesc('id')
            ->paginate(10);

        return view('adminPage.records.blockHistory', compact('blockData'));
    }

    /**
     *@param $request the input which is enter by admin to filter data

     * it filter data according to request
     */
    public function blockHistroySearchData(Request $request)
    {
        $blockData = BlockRequest::select(
            'request_client.first_name as patient_name',
            'block_request.id',
            'block_request.phone_number',
            'block_request.email',
            'block_request.is_active',
            'block_request.reason',
            'block_request.request_id',
            DB::raw('DATE(block_request.created_at) as created_date'),
        )
            ->leftJoin('request_client', 'block_request.request_id', 'request_client.request_id');

        // if (!empty($request->patient_name)) {
        if ($request->patient_name) {
            $blockData->where('request_client.first_name', 'like', '%' . $request->patient_name . '%');
        }
        // if (!empty($request->email)) {
        if ($request->email) {
            $blockData->where('block_request.email', "like", "%" . $request->email . "%");
        }
        // if (!empty($request->phone_number)) {
        if ($request->phone_number) {
            $blockData->where('block_request.phone_number', "like", "%" . $request->phone_number . "%");
        }
        // if (!empty($request->date)) {
        if ($request->date) {
            $blockData->where('block_request.created_at', "like", "%" . $request->date . "%");
        }
        $blockData = $blockData->paginate(10);

        session([
            'patient_name' => $request->patient_name,
            'date' => $request->date,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return view('adminPage.records.blockHistory', compact('blockData'));
    }

    // * Block history update isActive

    /**
     *@param $request the input which is check or uncheck by admin

     * it check and unccheck checkbox in is_Active columns of listing through ajax
     */
    public function updateBlockHistoryIsActive(Request $request)
    {
        $block = BlockRequest::find($request->blockId);
        $block->update(['is_active' => $request->is_active]);
    }


    //* unblock in block history

    /**
     *@param $id  id of request table

     * unblock patient and set status 1 in request_Status and request table
     */
    public function unBlockPatientInBlockHistoryPage($id)
    {
        RequestTable::where('id', $id)->update(['status' => 1]);
        RequestStatus::where('request_id', $id)->update(['status' => 1]);

        BlockRequest::where('request_id', $id)->delete();
        return redirect()->back()->with('message', 'patient is unblock');
    }


    /**
     *listing of user access page
     */
    public function UserAccess()
    {
        $userAccessData = AllUsers::select('roles.name', 'allusers.first_name', 'allusers.mobile', 'allusers.status', 'allusers.user_id')
            ->leftJoin('user_roles', 'user_roles.user_id', '=', 'allusers.user_id')
            ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id')
            ->whereIn('user_roles.role_id', [1, 2])
            ->paginate(10);

        return view('adminPage.access.userAccess', compact('userAccessData'));
    }


    // * route user as per account type

    /**
     *@param $id  id of user table

     * route admin to edit account page as per accountType(admin/provider)
     */
    public function UserAccessEdit($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $userAccessRoleName = Roles::select('name')
                ->leftJoin('user_roles', 'user_roles.role_id', 'roles.id')
                ->where('user_roles.user_id', $id)
                ->get();

            if ($userAccessRoleName->first()->name === 'admin') {
                return redirect()->route('edit.admin.profile', ['id' =>  Crypt::encrypt($id)]);
            } elseif ($userAccessRoleName->first()->name === 'physician') {
                $getProviderId = Provider::where('user_id', $id);
                return redirect()->route('admin.edit.providers', ['id' => Crypt::encrypt($getProviderId->first()->id)]);
            }
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }


    // * ajax rendering in user access page

    /**
     * filtering listing in user access page through ajax
     */
    public function FilterUserAccessAccountTypeWise(Request $request)
    {
        $account = $request->selectedAccount === "all" ? '' : $request->selectedAccount;

        $userAccessDataFiltering = AllUsers::select('roles.name', 'allusers.first_name', 'allusers.mobile', 'allusers.status', 'allusers.user_id')
            ->leftJoin('user_roles', 'user_roles.user_id', '=', 'allusers.user_id')
            ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id')
            ->whereIn('user_roles.role_id', [1, 2]);

        if ($account) {
            $userAccessDataFiltering = $userAccessDataFiltering->where('roles.name', '=', $account);
        }
        $userAccessDataFiltering = $userAccessDataFiltering->paginate(10);

        $data = view('adminPage.access.userAccessFiltering')->with('userAccessDataFiltering', $userAccessDataFiltering)->render();

        return response()->json(['html' => $data]);
    }


    /**
     * same as above in mobile view
     */
    public function FilterUserAccessAccountTypeWiseMobileView(Request $request)
    {

        $account = $request->selectedAccount === "all" ? '' : $request->selectedAccount;

        $userAccessDataFiltering = AllUsers::select('roles.name', 'allusers.first_name', 'allusers.mobile', 'allusers.status', 'allusers.user_id')
            ->leftJoin('user_roles', 'user_roles.user_id', '=', 'allusers.user_id')
            ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id')
            ->whereIn('user_roles.role_id', [1, 2]);

        if ($account) {
            $userAccessDataFiltering = $userAccessDataFiltering->where('roles.name', '=', $account);
        }
        $userAccessDataFiltering = $userAccessDataFiltering->paginate(10);

        $data = view('adminPage.access.userAccessFilterMobileView')->with('userAccessDataFiltering', $userAccessDataFiltering)->render();

        return response()->json(['html' => $data]);
    }


    /**
     *@param $request the input which is enter by user

     * send email to all unscheduled physician
     */
    public function sendRequestSupport(Request $request)
    {
        $request->validate([
            'contact_msg' => 'required|min:5|max:100',
        ]);

        $currentDate = now()->toDateString();
        $currentTime = now()->format('H:i');

        $onCallShifts = ShiftDetail::with('getShiftData')->where('shift_date', $currentDate)->where('start_time', '<=', $currentTime)->where('end_time', '>=', $currentTime)->get();

        $onCallPhysicianIds = $onCallShifts->whereNotNull('getShiftData.physician_id')->pluck('getShiftData.physician_id')->unique()->toArray();

        $offDutyPhysicians = Provider::whereNotIn('id', $onCallPhysicianIds)->pluck('email')->toArray();

        $requestMessage = $request->contact_msg;

        try {
            if ($offDutyPhysicians) {
                foreach ($offDutyPhysicians as $offDutyPhysician) {
                    try {
                        Mail::to($offDutyPhysician)->send(new RequestSupportMessage($requestMessage));
                    } catch (\Throwable $th) {
                        return view('errors.500');
                    }
                }
                return redirect()->back()->with('message', 'message is sent');
            } else {
                return redirect()->back()->with('message', 'No unschedule physician available!');
            }
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }

    /**
     *fetch region from region table and show in all region drop down button
     */
    
    public function fetchRegions()
    {
        $fetchedRegions = Regions::get();
        return response()->json($fetchedRegions);
    }

    /**
     *displaying create admin account page
     */
    public function adminAccount()
    {
        $regions = Regions::get();
        return view("adminPage.createAdminAccount", compact('regions'));
    }

    /**
     *@param $request the input which is enter by user

     * it stores data in admin ,users,allusers and make role_id 1 in user_roles
     */

    public function createAdminAccount(Request $request)
    {
        $request->validate([
            'user_name' => 'required|alpha|min:3|max:40',
            'password' => 'required|min:8|max:20|regex:/^\S(.*\S)?$/',
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'email' => 'required|email|min:2|max:40|unique:App\Models\Users,email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'confirm_email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'address1' => 'required|min:2|max:30|regex:/^[a-zA-Z0-9\s,_-]+?$/',
            'address2' => 'required|min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'zip' => 'digits:6',
            'alt_mobile' => 'required|max_digits:10|min_digits:10',
            'role' => 'required',
            'state' => 'required',
        ]);

        // Store Data in users table

        $adminCredentialsData = new Users();
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
        $storeAdminData->status = 'pending';
        $storeAdminData->role_id = $request->role;
        $storeAdminData->region_id = $request->state;

        $storeAdminData->save();

        foreach ($request->region_id as $region) {
            AdminRegion::create([
                'admin_id' => $storeAdminData->id,
                'region_id' => $region
            ]);
        }

        // make entry in user_roles table to identify the user(whether it is admin or physician)
        $user_roles = new UserRoles();
        $user_roles->user_id = $adminCredentialsData->id;
        $user_roles->role_id = 1;
        $user_roles->save();

        // store data in allusers table
        $adminAllUserData = new AllUsers();
        $adminAllUserData->user_id = $adminCredentialsData->id;
        $adminAllUserData->first_name = $request->first_name;
        $adminAllUserData->last_name = $request->last_name;
        $adminAllUserData->email = $request->email;
        $adminAllUserData->street = $request->address1;
        $adminAllUserData->city = $request->city;
        $adminAllUserData->zipcode = $request->zip;
        $adminAllUserData->mobile = $request->phone_number;
        $adminAllUserData->status = 'pending';
        $adminAllUserData->save();

        return redirect()->route('admin.user.access');
    }


    /**
     *fetch state for admin account create through ajax
     */

    public function fetchRegionsForState()
    {
        $fetchedRegions = Regions::get();
        return response()->json($fetchedRegions);
    }


    /**
     *fetch roles for admin account create through ajax
     */
    public function fetchRolesForAdminAccountCreate()
    {
        $fetchedRoles = Role::select('id', 'name')->where('account_type', 'admin')->get();
        return response()->json($fetchedRoles);
    }

    /**
     *common function for filtering and exporting data in admin listing
     */

    public function fetchQuery($status, $category, $searchTerm, $region)
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
                $query->where('first_name', 'like', "%{$searchTerm}%")->orWhere('last_name', 'like', "%{$searchTerm}%")
                    ->orWhereHas('requestClient', function ($q) use ($searchTerm) {
                        $q->where('first_name', 'like', "%{$searchTerm}%")->orWhere('last_name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Filter Regions
        if ($region) {
            $query->whereHas('requestClient', function ($query) use ($region) {
                $query->where('state', 'like', '%' . $region . '%');
            })->where('status', $this->getStatusId($status));
        }

        return $query;
    }


    /**
     *@param $request which contains region_id,status,category,search_value

     * it filter data in admin new listing through regions
     */

    public function filterPatientNew(Request $request)
    {
        $request->session()->put('regionId', $request->regionId);

        $status = $request->status;
        $category = $request->category_value;
        $search = $request->session()->get('searchTerm', null);

        $regionId = $request->session()->get('regionId');


        if ($regionId === 'all_regions') {
            $cases = $this->buildQuery($status, $category, $search, $regionId)->orderByDesc('id')->paginate(10);
        } else {
            $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();
            $cases = $this->fetchQuery($status, $category, $search, $regionName)->orderByDesc('id')->paginate(10);
        }

        $data = view('adminPage.adminTabs.filter-new')->with('cases', $cases)->render();
        return response()->json(['html' => $data]);
    }


    /**
     *@param $request which contains region_id,status,category,search_value

     * it filter data in admin pending listing through regions
     */
    public function filterPatientPending(Request $request)
    {
        $request->session()->put('regionId', $request->regionId);

        $status = $request->status;
        $category = $request->category_value;
        $search = $request->search_value;

        $regionId = $request->session()->get('regionId');

        if ($regionId === 'all_regions') {
            $cases = $this->buildQuery($status, $category, $search, $regionId)->orderByDesc('id')->paginate(10);
        } else {
            $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();
            $cases = $this->fetchQuery($status, $category, $search, $regionName)->orderByDesc('id')->paginate(10);
        }

        $data = view('adminPage.adminTabs.filter-pending')->with('cases', $cases)->render();
        return response()->json(['html' => $data]);
    }

    /**
     *@param $request which contains region_id,status,category,search_value

     * it filter data in admin active listing through regions
     */
    public function filterPatientActive(Request $request)
    {
        $request->session()->put('regionId', $request->regionId);

        $status = $request->status;
        $category = $request->category_value;
        $search = $request->search_value;

        $regionId = $request->session()->get('regionId');

        if ($regionId === 'all_regions') {
            $cases = $this->buildQuery($status, $category, $search, $regionId)->orderByDesc('id')->paginate(10);
        } else {
            $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();
            $cases = $this->fetchQuery($status, $category, $search, $regionName)->orderByDesc('id')->paginate(10);
        }

        $data = view('adminPage.adminTabs.filter-active')->with('cases', $cases)->render();
        return response()->json(['html' => $data]);
    }


    /**
     *@param $request which contains region_id,status,category,search_value

     * it filter data in admin conclude listing through regions
     */
    public function filterPatientConclude(Request $request)
    {
        $request->session()->put('regionId', $request->regionId);

        $status = $request->status;
        $category = $request->category_value;
        $search = $request->search_value;

        $regionId = $request->session()->get('regionId');

        if ($regionId === 'all_regions') {
            $cases = $this->buildQuery($status, $category, $search, $regionId)->orderByDesc('id')->paginate(10);
        } else {
            $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();
            $cases = $this->fetchQuery($status, $category, $search, $regionName)->orderByDesc('id')->paginate(10);
        }

        $data = view('adminPage.adminTabs.filter-conclude')->with('cases', $cases)->render();
        return response()->json(['html' => $data]);
    }


    /**
     *@param $request which contains region_id,status,category,search_value

     * it filter data in admin toclose listing through regions
     */
    public function filterPatientToClose(Request $request)
    {
        $request->session()->put('regionId', $request->regionId);

        $status = $request->status;
        $category = $request->category_value;
        $search = $request->search_value;

        $regionId = $request->session()->get('regionId');

        if ($regionId === 'all_regions') {
            $cases = $this->buildQuery($status, $category, $search, $regionId)->orderByDesc('id')->paginate(10);
        } else {
            $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();
            $cases = $this->fetchQuery($status, $category, $search, $regionName)->orderByDesc('id')->paginate(10);
        }

        $data = view('adminPage.adminTabs.filter-toClose')->with('cases', $cases)->render();
        return response()->json(['html' => $data]);
    }


    /**
     *@param $request which contains region_id,status,category,search_value

     * it filter data in admin unpaid listing through regions
     */
    public function filterPatientUnpaid(Request $request)
    {
        $request->session()->put('regionId', $request->regionId);

        $status = $request->status;
        $category = $request->category_value;
        $search = $request->search_value;

        $regionId = $request->session()->get('regionId');

        if ($regionId === 'all_regions') {
            $cases = $this->buildQuery($status, $category, $search, $regionId)->orderByDesc('id')->paginate(10);
        } else {
            $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();
            $cases = $this->fetchQuery($status, $category, $search, $regionName)->orderByDesc('id')->paginate(10);
        }

        $data = view('adminPage.adminTabs.filter-unpaid')->with('cases', $cases)->render();
        return response()->json(['html' => $data]);
    }



    /**
     *@param $request which contains region_id,category,search_value

     * it export data to excel in admin new listing
     */

    public function exportNew(Request $request)
    {
        $status = 'new';
        $category = $request->filter_category;
        $search = $request->filter_search;
        $region = $request->filter_region;

        $regionId = $request->session()->get('regionId');

        if ($region === "All Regions") {
            $exportNewData = $this->buildQuery($status, $category, $search, $regionId);
        } else {
            $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();
            $exportNewData = $this->fetchQuery($status, $category, $search, $regionName);
        }

        if ($exportNewData->get()->isEmpty()) {
            return back()->with('successMessage', 'no cases found to export in Excel');
        } else {
            $exportNew = new NewStatusExport($exportNewData);
            return Excel::download($exportNew, 'NewData.xls');
        }
    }


    /**
     *@param $request which contains region_id,category,search_value

     * it export data to excel in admin pending listing
     */
    public function exportPending(Request $request)
    {
        $status = 'pending';
        $category = $request->filter_category;
        $search = $request->filter_search;
        $region = $request->filter_region;

        $regionId = $request->session()->get('regionId');


        if ($region == "All Regions") {
            $exportPendingData = $this->buildQuery($status, $category, $search, $regionId);
        } else {
            $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();
            $exportPendingData = $this->fetchQuery($status, $category, $search, $regionName);
        }

        if ($exportPendingData->get()->isEmpty()) {
            return back()->with('successMessage', 'no cases found to export in Excel');
        } else {
            $exportPending = new PendingStatusExport($exportPendingData);
            return Excel::download($exportPending, 'PendingData.xls');
        }
    }

    /**
     *@param $request which contains region_id,category,search_value

     * it export data to excel in admin active listing
     */
    public function exportActive(Request $request)
    {
        $status = 'active';
        $category = $request->filter_category;
        $search = $request->filter_search;
        $region = $request->filter_region;

        $regionId = $request->session()->get('regionId');


        if ($region == "All Regions") {
            $exportActiveData = $this->buildQuery($status, $category, $search, $regionId);
        } else {
            $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();
            $exportActiveData = $this->fetchQuery($status, $category, $search, $regionName);
        }

        if ($exportActiveData->get()->isEmpty()) {
            return back()->with('successMessage', 'no cases found to export in Excel');
        } else {
            $exportActive = new ActiveStatusExport($exportActiveData);
            return Excel::download($exportActive, 'ActiveData.xls');
        }
    }


    /**
     *@param $request which contains region_id,category,search_value

     * it export data to excel in admin conclude listing
     */
    public function exportConclude(Request $request)
    {
        $status = 'conclude';
        $category = $request->filter_category;
        $search = $request->filter_search;
        $region = $request->filter_region;

        $regionId = $request->session()->get('regionId');

        if ($region == "All Regions") {
            $exportConcludeData = $this->buildQuery($status, $category, $search, $regionId);
        } else {
            $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();
            $exportConcludeData = $this->fetchQuery($status, $category, $search, $regionName);
        }

        if ($exportConcludeData->get()->isEmpty()) {
            return back()->with('successMessage', 'no cases found to export in Excel');
        } else {
            $exportConclude = new ConcludeStatusExport($exportConcludeData);
            return Excel::download($exportConclude, 'ConcludeData.xls');
        }
    }



    /**
     *@param $request which contains region_id,category,search_value

     * it export data to excel in admin toclose listing
     */
    public function exportToClose(Request $request)
    {
        $status = 'toclose';
        $category = $request->filter_category;
        $search = $request->filter_search;
        $region = $request->filter_region;
        $regionId = $request->session()->get('regionId');

        if ($region == "All Regions") {
            $exportToCloseData = $this->buildQuery($status, $category, $search, $regionId);
        } else {
            $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();
            $exportToCloseData = $this->fetchQuery($status, $category, $search, $regionName);
        }

        if ($exportToCloseData->get()->isEmpty()) {
            return back()->with('successMessage', 'no cases found to export in Excel');
        } else {
            $exportToClose = new ToCloseStatusExport($exportToCloseData);
            return Excel::download($exportToClose, 'ToCloseData.xls');
        }
    }


    /**
     *@param $request which contains region_id,category,search_value

     * it export data to excel in admin unpaid listing
     */
    public function exportUnpaid(Request $request)
    {
        $status = 'unpaid';
        $category = $request->filter_category;
        $search = $request->filter_search;
        $region = $request->filter_region;
        $regionId = $request->session()->get('regionId');


        if ($region == "All Regions") {
            $exportUnpaidData = $this->buildQuery($status, $category, $search, $regionId);
        } else {
            $regionName = Regions::where('id', $regionId)->pluck('region_name')->first();
            $exportUnpaidData = $this->fetchQuery($status, $category, $search, $regionName);
        }

        if ($exportUnpaidData->get()->isEmpty()) {
            return back()->with('successMessage', 'no cases found to export in Excel');
        } else {
            $exportUnpaid = new UnPaidStatusExport($exportUnpaidData);
            return Excel::download($exportUnpaid, 'UnPaidData.xls');
        }
    }

    // REMOVED FROM SRS
    // Cancel History Page
    public function viewCancelHistory()
    {
        $cancelCases = RequestStatus::with('request')->where('status', 2)->get();
        return view('adminPage.records.cancelHistory', compact('cancelCases'));
    }
    // search cancel case in Cancel History Page
    public function searchCancelCase(Request $request)
    {
        $query = RequestStatus::where('status', 2);

        $query->whereHas('request', function ($query) use ($request) {
            $query->whereHas('requestClient', function ($clientQuery) use ($request) {
                $clientQuery->where(function ($subQuery) use ($request) {
                    $subQuery->where('first_name', 'LIKE', "%{$request->name}%")->orWhere('last_name', 'LIKE', "%{$request->name}%");
                })->when($request->email, function ($query) use ($request) {
                    return $query->where('email', 'LIKE', "%{$request->email}%");
                })->when($request->phone_number, function ($query) use ($request) {
                    return $query->where('phone_number', 'LIKE', "%{$request->phone_number}%");
                })->when($request->date, function ($query) use ($request) {
                    return $query->where('request.updated_at', $request->date);
                });
            });
        });

        $cancelCases = $query->get();

        return view('adminPage.records.cancelHistory', compact('cancelCases'));
    }
}
