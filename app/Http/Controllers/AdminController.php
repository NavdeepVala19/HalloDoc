<?php

namespace App\Http\Controllers;

use App\Exports\ActiveStatusExport;
use App\Exports\ConcludeStatusExport;
use App\Exports\NewStatusExport;
use App\Exports\PendingStatusExport;
use App\Exports\ToCloseStatusExport;
use App\Exports\UnPaidStatusExport;
use App\Http\Requests\AdminCreateRequest;
use App\Mail\RequestSupportMessage;
use App\Mail\SendEmailAddress;
use App\Models\Admin;
use App\Models\Provider;
use App\Models\Regions;
use App\Models\RequestTable;
use App\Models\ShiftDetail;
use App\Models\Users;
use App\Services\AdminCreateRequestService;
use App\Services\CreateEmailLogService;
use App\Services\CreateNewUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

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

    // For Admin redirect to new State(By Default)
    public function adminDashboard()
    {
        return redirect('/admin/new');
    }

    /**
     *  Counts Total Number of cases for different status
     *
     * @return array<int> array[] total number of cases, as per the status.
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
     *
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
     *
     * @return \illuminate\View\View
     */
    public function cases(Request $request, $status = 'new', $category = 'all')
    {
        // $searchTerm = $request->search;
        // Use Session to filter by category and searchTerm
        $category = $request->session()->get('category', 'all');
        $searchTerm = $request->session()->get('searchTerm', null);
        $regionId = $request->session()->get('regionId', 'all_regions');

        $userData = Auth::user();
        $count = $this->totalCasesCount();
        $query = $this->buildQuery($status, $category, $searchTerm, $regionId);

        $cases = $query->latest()->paginate(10);
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
     *
     * @return \illuminate\View\View
     *
     * @param string $status different status names.
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
     *
     * @return \illuminate\View\View
     *
     * @param string $status different status names.
     * @param string $category different category names.
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
     *
     * @return \illuminate\View\View
     *
     * @param string $status different status names.
     * @param string $category different category names.
     */
    public function search(Request $request, $status = 'new', $category = 'all')
    {
        // store searchTerm in session
        $request->session()->put('searchTerm', $request->search);

        return $this->cases($request, $status, $category);
    }

    public function fetchRegions()
    {
        $fetchedRegions = Regions::get();
        return response()->json($fetchedRegions);
    }

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
                return back()->with('message', 'message is sent');
            }
            return back()->with('message', 'No unschedule physician available!');
        } catch (\Throwable $th) {
            return view('errors.500');
        }
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

        $cases = $this->buildQuery($status, $category, $search, $regionId)->latest()->paginate(10);

        $bladeFileName = 'filter-' . $request->status;
        $bladeFilePath = 'adminPage.adminTabs.' . $bladeFileName;

        // $data = view('adminPage.adminTabs.filter-new')->with('cases', $cases)->render();
        $data = view($bladeFilePath)->with('cases', $cases)->render();
        return response()->json(['html' => $data]);
    }

    /**
     * it export data to excel in admin new listing
     *
     * @param \Illuminate\Http\Request $request (region_id,category,search_value)
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */

    public function exportDataToExcel(Request $request)
    {
        $status = $request->status;
        $category = $request->filter_category;
        $search = $request->filter_search;
        $regionId = session('regionId');

        $exportData = $this->buildQuery($status, $category, $search, $regionId);

        if ($exportData->get()->isEmpty()) {
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

        $file = $status . '-data.xls';
        return Excel::download(new $exportDataClass($exportData), $file);
    }

    /**
     * shows admin request page(form) from this page admin can create request on behalf of patient
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */

    public function createNewRequest()
    {
        return view('adminPage/adminRequest');
    }

    /**
     * it stores request in request_client and request table and if user is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     *
     * @param \Illuminate\Http\Request $request  (the input which is enter by user)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */

    public function createAdminPatientRequest(AdminCreateRequest $request, AdminCreateRequestService $adminCreateRequestService, CreateNewUserService $createNewUserService, CreateEmailLogService $createEmailLogService)
    {
        $adminId = Admin::where('user_id', Auth::user()->id)->value('id');
        $isEmailStored = Users::where('email', $request->email)->first();
        $requestId = $adminCreateRequestService->storeRequest($request);
        if ($isEmailStored === null) {
            $createNewUserService->storeNewUsers($request);
            try {
                Mail::to($request->email)->send(new SendEmailAddress($request->email));
                $createEmailLogService->storeEmailLogs($request, $requestId, null, $adminId);
            } catch (\Throwable $th) {
                return view('errors.500');
            }
        }
        $redirectMsg = $isEmailStored ? 'Request is Submitted' : 'Email for Create Account is Sent and Request is Submitted';

        return redirect()->route('admin.status', 'new')->with('successMessage', $redirectMsg);
    }
    /**
     * Get category id from the name of category
     *
     * @param string $category different category names.
     *
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
     *
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
}
