<?php

namespace App\Http\Controllers;

use App\Exports\ActiveStatusExport;
use App\Exports\ConcludeStatusExport;
use App\Exports\NewStatusExport;
use App\Exports\PendingStatusExport;
use App\Exports\SearchRecordExport;
use App\Exports\ToCloseStatusExport;
use App\Exports\UnPaidStatusExport;
use App\Helpers\Helper;
use App\Http\Requests\AdminProfileForm;
use App\Http\Requests\BusinessRequest;
use App\Mail\RequestSupportMessage;
use App\Mail\SendLink;
use App\Models\BlockRequest;
use App\Models\EmailLog;
use App\Models\HealthProfessional;
use App\Models\HealthProfessionalType;
use App\Models\Menu;
use App\Models\Provider;
use App\Models\Regions;
use App\Models\RequestBusiness;
use App\Models\RequestClient;
use App\Models\RequestConcierge;
use App\Models\RequestStatus;
use App\Models\RequestTable;
use App\Models\RequestWiseFile;
use App\Models\Role;
use App\Models\RoleMenu;
use App\Models\Roles;
use App\Models\ShiftDetail;
use App\Models\SMSLogs;
use App\Services\RecordsService;
use App\Services\UserAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Twilio\Rest\Client;

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
            'newCase' => RequestTable::where('status', Helper::STATUS_NEW)->count(),
            // pending state(Status = 3) -> Accepted by provider
            'pendingCase' => RequestTable::where('status', Helper::STATUS_PENDING)->count(),
            // Active State(Status = 4,5) -> MDEnRoute(agreement sent and accepted by patient), MDOnSite(call type selected by provider[HouseCall])
            'activeCase' => RequestTable::whereIn('status', Helper::STATUS_ACTIVE)->count(),
            // Conclude State(Status = 6) -> when consult selected during Encounter pop-up or HouseCall Completed
            'concludeCase' => RequestTable::where('status', Helper::STATUS_CONCLUDE)->count(),
            // toClose State(Status = 2,7,11) -> when provider conclude care or when admin cancel case or agreement cancelled by patient, it moves to ToClose state
            'tocloseCase' => RequestTable::whereIn('status', Helper::STATUS_TOCLOSE)->count(),
            // toClose State(Status = 9) -> when Admin close case, it will move to unpaid state
            'unpaidCase' => RequestTable::where('status', Helper::STATUS_UNPAID)->count(),
        ];
    }

    /**
     *  Build Query as per filters, search query or normal cases
     *
     * @param string $status status of the cases [new, active, pending, conclude].
     * @param string $category category of the cases [all, patient, family, business, concierge].
     * @param string $searchTerm search term to filter the cases.
     *
     * @return object $query formed as per the status, category selected, any search term entered
     */
    public function buildQuery($status, $category, $searchTerm, $regionId)
    {
        // Check for Status(whether it's single status or multiple)
        if (is_array(Helper::getStatusId($status))) {
            $query = RequestTable::with('requestClient')->whereIn('status', Helper::getStatusId($status));
        } else {
            $query = RequestTable::with('requestClient')->where('status', Helper::getStatusId($status));
        }

        // Filter by Category if not 'all'
        if ($category !== 'all') {
            $query->where('request_type_id', Helper::getCategoryId($category));
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
     *
     * @return \illuminate\View\View
     */
    public function cases(Request $request, $status = 'new')
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

        return view($viewName, compact('cases', 'count', 'userData'));
    }

    /**
     * Display Provider Listing/Dashboard page as per the Tab Selected (By default it's "new")
     *
     * @param \illuminate\HTTP\Request $request
     * @param string $status different status names.
     *
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
     *
     * @return \illuminate\View\View
     */
    public function adminFilter(Request $request, $status = 'new', $category = 'all')
    {
        // Store category in the session
        $request->session()->put('category', $category);

        if ($status === 'new' || $status === 'pending' || $status === 'active' || $status === 'conclude' || $status === 'toclose' || $status === 'unpaid' && $category === 'all' || $category === 'patient' || $category === 'family' || $category === 'business' || $category === 'concierge') {
            return $this->cases($request, $status);
        }
        return view('errors.404');
    }

    /**
     * Search for searchTerm request in first_name & last_name of requestclient or RequestTable
     *
     * @param \illuminate\Http\Request $request
     * @param string $status different status names.
     * @param string $category different category names.
     *
     * @return \illuminate\View\View
     */
    public function search(Request $request, $status = 'new')
    {
        // store searchTerm in session
        $request->session()->put('searchTerm', $request->search);

        return $this->cases($request, $status);
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
     * Send Mail and SMS to patient with an link to create request page
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
    public function sendMail(Request $request)
    {
        // Generate the link using route() helper (assuming route parameter is optional)
        $link = route('submit.request');

        $request->validate([
            'first_name' => 'required|alpha|min:5|max:30',
            'last_name' => 'required|alpha|min:5|max:30',
            'phone_number' => 'required',
            'email' => 'required|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
        ]);

        try {
            Mail::to($request->email)->send(new SendLink($request->all()));
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
            'action' => 1,
        ]);

        try {
            // send SMS
            $sid = config('api.twilio_sid');
            $token = config('api.twilio_auth_token');
            $senderNumber = config('api.sender_number');

            $twilio = new Client($sid, $token);

            $twilio->messages
                ->create(
                    '+91 99780 71802', // to
                    [
                        'body' => "Hii {$request->first_name} {$request->last_name}, Click on the this link to create request:{$link}",
                        'from' => $senderNumber,
                    ]
                );
        } catch (\Throwable $th) {
            return view('errors.500');
        }

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
                'sms_template' => 'Hii, Click on the below link to create request',
            ]
        );

        return redirect()->back()->with('successMessage', 'Link Sent Successfully!');
    }
    // -------------------- 2. Create Request -------------------------
    // Admin Create Request -> code written in Admin DashBoard Controller

    // -------------------- 3. Export ---------------------------------
    /**
     * it export data to excel in admin listing for all status
     *
     * @param \Illuminate\Http\Request $request (region_id,category,search_value)
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportData(Request $request)
    {
        $status = $request->status;
        $category = $request->filter_category;
        $search = $request->filter_search;
        $regionId = session('regionId');

        $data = $this->buildQuery($status, $category, $search, $regionId);

        if ($data->get()->isEmpty()) {
            return back()->with('successMessage', 'no cases found to export in Excel');
        }

        $exportDataClasses = [
            'new' => NewStatusExport::class,
            'pending' => PendingStatusExport::class,
            'active' => ActiveStatusExport::class,
            'conclude' => ConcludeStatusExport::class,
            'toclose' => ToCloseStatusExport::class,
            'unpaid' => UnPaidStatusExport::class,
        ];

        $exportDataClass = $exportDataClasses[$status] ?? null;

        $file = $status . 'Data.xls';
        return Excel::download(new $exportDataClass($data), $file);
    }

    // -------------------- 4. Export All -----------------------------
    // Export All -> code written in ExcelController

    // -------------------- 5. Request DTY Support --------------------
    /**
     * send email to all unscheduled physician
     *
     * @param \Illuminate\Http\Request $request (message)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
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

        try {
            if ($offDutyPhysicians) {
                foreach ($offDutyPhysicians as $offDutyPhysician) {
                    try {
                        Mail::to($offDutyPhysician)->send(new RequestSupportMessage($request->contact_msg));
                    } catch (\Throwable $th) {
                        return view('errors.500');
                    }
                }
                return redirect()->back()->with('message', 'message is sent');
            }
            return redirect()->back()->with('message', 'No unschedule physician available!');
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }

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
    // It's code is written in AdminProviderController

    // -------------------- 2. My Profile ---------------------------
    // Admin profile -> it's code is written in AdminDashboardController

    // -------------------- 3. Providers ----------------------------
    // --------- 3.1 : Provider ----------
    // It's code is written in AdminProviderController

    // --------- 3.2 : Scheduling --------
    // It's code is written in Scheduling Controller

    // -------------------- 4. Partners -----------------------------
    /**
     * Display Partners/Vendors page
     *
     * @param int $id healthProfessionalType id to show selection on dropdown
     *
     * @return \Illuminate\View\View partners page
     */
    public function viewPartners($id = null)
    {
        if ($id === null || $id === 0) {
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
     *
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

        if ($id !== '0') {
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
     *
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
    public function addBusiness(BusinessRequest $request)
    {
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
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function updateBusinessView($id)
    {
        try {
            $caseId = Crypt::decrypt($id);
            // HealthProfessional Id whose value need to be updated
            $vendor = HealthProfessional::where('id', $caseId)->first();
            $professions = HealthProfessionalType::get();
            return view('adminPage.partners.updateBusiness', compact('vendor', 'professions'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * Update business data based on the provided request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBusiness(BusinessRequest $request)
    {
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
            'zip' => $request->zip,
        ]);

        return redirect()->back()->with('changesSaved', 'Changes Saved Successfully!');
    }

    /**
     * Delete a business from the vendors page.
     *
     * @param int|null $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteBusiness($id = null)
    {
        HealthProfessional::where('id', $id)->delete();
        return redirect()->back();
    }
    // -------------------- 5. Access -------------------------------
    // --------- 5.1 : User Access --------
    /**
     * listing of user access page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function userAccess(UserAccessService $userAccessService)
    {
        $userAccessData = $userAccessService->userAccessList();
        return view('adminPage.access.userAccess', compact('userAccessData'));
    }

    /**
     *  route admin to edit account page as per accountType(admin/provider)
     *
     * @param mixed $id (id of user table)
     *
     * @return mixed|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function userAccessEdit($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $userAccessRoleName = Roles::select('name')
                ->leftJoin('user_roles', 'user_roles.role_id', 'roles.id')
                ->where('user_roles.user_id', $id)
                ->get();

            if ($userAccessRoleName->value('name') === 'admin') {
                return redirect()->route('edit.admin.profile', ['id' => Crypt::encrypt($id)]);
            }
            if ($userAccessRoleName->value('name') === 'physician') {
                $getProviderId = Provider::where('user_id', $id)->first()->id;
                return redirect()->route('admin.edit.providers', ['id' => Crypt::encrypt($getProviderId)]);
            }
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * filtering listing in user access page through ajax
     *
     * @param \Illuminate\Http\Request $request (account type(all/admin/provider))
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function filterUserAccessAccountTypeWise(Request $request, UserAccessService $userAccessService)
    {
        $userAccessDataFiltering = $userAccessService->filterAccountWise($request);
        $data = view('adminPage.access.userAccessFiltering')->with('userAccessDataFiltering', $userAccessDataFiltering)->render();

        return response()->json(['html' => $data]);
    }

    /**
     * same as above in mobile view
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function filterUserAccessAccountTypeWiseMobileView(Request $request, UserAccessService $userAccessService)
    {
        $userAccessDataFiltering = $userAccessService->filterAccountWise($request);
        $data = view('adminPage.access.userAccessFilterMobileView')->with('userAccessDataFiltering', $userAccessDataFiltering)->render();

        return response()->json(['html' => $data]);
    }

    /**
     * displaying create admin account page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminAccount()
    {
        $regions = Regions::get();
        return view('adminPage.createAdminAccount', compact('regions'));
    }

    /**
     * it stores data in admin ,users,allusers and make role_id '1' in user_roles
     *
     * @param \Illuminate\Http\Request
     *
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function createAdminAccount(AdminProfileForm $request, UserAccessService $userAccessService)
    {
        $userAccessService->createAdminAccount($request);
        return redirect()->route('admin.user.access');
    }

    /**
     * fetch roles for admin account create through ajax
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function fetchRolesForAdminAccountCreate()
    {
        $fetchedRoles = Role::select('id', 'name')->where('account_type', 'admin')->get();
        return response()->json($fetchedRoles);
    }

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
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchRoles($id = null)
    {
        if ($id === 0) {
            $menus = Menu::get();
            return response()->json($menus);
        }
        if ($id === '1') {
            $menus = Menu::where('account_type', 'Admin')->get();
            return response()->json($menus);
        }
        if ($id === '2') {
            $menus = Menu::where('account_type', 'Physician')->get();
            return response()->json($menus);
        }
    }

    /**
     * Creating different Access for different roles
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing role information and menu checkboxes.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAccess(Request $request)
    {
        $request->validate([
            'role_name' => 'required',
            'role' => 'required',
            'menu_checkbox' => 'required',
        ]);
        if ($request->role_name === '1') {
            $roleId = Role::insertGetId(['name' => $request->role, 'account_type' => 'admin']);
        } elseif ($request->role_name === '2') {
            $roleId = Role::insertGetId(['name' => $request->role, 'account_type' => 'physician']);
        }

        foreach ($request->input('menu_checkbox') as $value) {
            RoleMenu::create([
                'role_id' => $roleId,
                'menu_id' => $value,
            ]);
        }
        return redirect()->route('admin.access.view')->with('accessOperation', 'New access created successfully!');
    }

    /**
     * Deletes a complete role.
     *
     * @param int|null $id The ID of the role to be deleted.
     *
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
     *
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
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editAccessData(Request $request)
    {
        $request->validate([
            'role_name' => 'required',
            'role' => 'required',
            'menu_checkbox' => 'required',
        ]);

        Role::where('id', $request->roleId)->update([
            'name' => $request->role,
            // 'account_type' => $request->role,
        ]);

        RoleMenu::where('role_id', $request->roleId)->delete();

        foreach ($request->input('menu_checkbox') as $value) {
            RoleMenu::create([
                'role_id' => $request->roleId,
                'menu_id' => $value,
            ]);
        }
        return redirect()->route('admin.access.view')->with('accessOperation', 'Your Changes Are successfully Saved!');
    }
    // -------------------- 6. Records -------------------------------
    // --------- 6.1 : Search Records -----
    /**
     * list of  search records
     * it list patient name,email,mobile,address,zip,date of service ,close case date,request type,request status,provider name,
     * physician note,admin note,patient note
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function searchRecordsView(RecordsService $recordsService)
    {
        $records = $recordsService->searchRecordsListing();
        Session::forget('request_status');
        Session::forget('request_type');

        return view('adminPage.records.searchRecords', compact('records'));
    }

    /**
     * filter records as per input
     *
     * @param \Illuminate\Http\Request $request (the input which is use to filter data in search records)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function searchRecordSearching(Request $request, RecordsService $recordsService)
    {
        $records = $recordsService->searchRecords($request);
        session([
            'patient_name' => $request->patient_name,
            'from_date_of_service' => $request->from_date_of_service,
            'to_date_of_service' => $request->to_date_of_service,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'provider_name' => $request->provider_name,
        ]);

        if ($request->has('request_status')) {
            Session::put('request_status', $request->request_status);
        } else {
            Session::forget('request_status');
        }

        if ($request->has('request_type')) {
            Session::put('request_type', $request->request_type);
        } else {
            Session::forget('request_type');
        }

        return view('adminPage.records.searchRecords', compact('records'));
    }

    /**
     * common function for filtering and exporting to excel
     * it filter as per request
     *
     * @param mixed $request (the input which is use to filter data in search records)
     *
     * @return RequestClient
     */
    public function exportFilteredSearchRecord($request, RecordsService $recordsService)
    {
        return $recordsService->filterSearchRecords($request);
    }

    /**
     * export filtered data to excel
     *
     * @param \Illuminate\Http\Request $request ( the input which is use to filter data in search records)
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFilteredData(Request $request, RecordsService $recordsService)
    {
        $data = $recordsService->exportFilteredDataToExcel($request);

        if ($data->get()->isEmpty()) {
            return back()->with('message', 'no records to export to Excel');
        }
        $export = new SearchRecordExport($data);
        return Excel::download($export, 'search_record_filtered_data.xls');
    }

    /**
     * delete record permanently from request client ,request,block_request,request_concierge,request_business,request_status,request_wise_file
     *
     * @param mixed $id  (id of request table)
     *
     * @return \Illuminate\Http\RedirectResponse
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
     * @param  \Illuminate\Http\Request $request
     *
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
    /**
     * list receipient name ,action,role_name,mobile,create_date,sent_date,confirmation_number,is_sent_sent_tries
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function smsRecordsView()
    {
        $smsLogs = SMSLogs::latest('id')->paginate(10);
        Session::forget('role_type');
        return view('adminPage.records.smsLogs', compact('smsLogs'));
    }

    /**
     * filter sms logs
     *
     * @param \Illuminate\Http\Request $request  (the input which is enter by admin to filter data)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */

    public function searchSMSLogs(Request $request, RecordsService $recordsService)
    {
        $smsLogs = $recordsService->filterSMSLogs($request);
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

        return view('adminPage.records.smsLogs', compact('smsLogs'));
    }

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
     *
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
     * @param  string|null $id
     *
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
     * List of block request
     * list patient name,mobile,email,created date and notes
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function blockHistoryView(RecordsService $recordsService)
    {
        $blockData = $recordsService->blockHistory();
        return view('adminPage.records.blockHistory', compact('blockData'));
    }

    /**
     * filter data according to request
     *
     * @param \Illuminate\Http\Request $request (input which is enter by admin to filter data)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function blockHistroySearchData(Request $request, RecordsService $recordsService)
    {
        $blockData = $recordsService->filterBlockHistoryData($request);
        session([
            'patient_name' => $request->patient_name,
            'date' => $request->date,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return view('adminPage.records.blockHistory', compact('blockData'));
    }

    /**
     * it check and uncheck checkbox in is_Active columns of listing through ajax
     *
     * @param \Illuminate\Http\Request $request (input which is check or uncheck by admin)
     *
     * @return void
     */
    public function updateBlockHistoryIsActive(Request $request)
    {
        $block = BlockRequest::find($request->blockId);
        $block->update(['is_active' => $request->is_active]);
    }

    /**
     * unblock patient and set status 1 in request_Status and request table
     *
     * @param mixed $id (id of request table)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unBlockPatientInBlockHistoryPage($id)
    {
        RequestTable::where('id', $id)->update(['status' => 1]);
        RequestStatus::where('request_id', $id)->update(['status' => 1]);
        BlockRequest::where('request_id', $id)->delete();
        return redirect()->back()->with('message', 'patient is unblock');
    }

    // ------------------------------------------------------------------
    /**
     * fetch region from region table and show in all region drop down button
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function fetchRegions()
    {
        $fetchedRegions = Regions::get();
        return response()->json($fetchedRegions);
    }

    /**
     * filter data in admin new listing through regions
     *
     * @param \Illuminate\Http\Request $request (region_id,status,category,search_value)
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function filterPatient(Request $request)
    {
        $request->session()->put('regionId', $request->regionId);
        $status = $request->status;
        $category = $request->category_value;
        $search = $request->session()->get('searchTerm', null);
        $regionId = session('regionId');

        $cases = $this->buildQuery($status, $category, $search, $regionId)->orderByDesc('id')->paginate(10);

        $bladeFileName = 'filter-' . $request->status;
        $bladeFilePath = 'adminPage.adminTabs.' . $bladeFileName;

        $data = view($bladeFilePath)->with('cases', $cases)->render();
        return response()->json(['html' => $data]);
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
