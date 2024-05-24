<?php

namespace App\Http\Controllers;

use App\Exports\ActiveStatusExport;
use App\Exports\ConcludeStatusExport;
use App\Exports\NewStatusExport;
use App\Exports\PendingStatusExport;
use App\Exports\ToCloseStatusExport;
use App\Exports\UnPaidStatusExport;
use App\Exports\UsersExport;
use App\Helpers\ConfirmationNumber;
use App\Helpers\Helper;
use App\Http\Requests\AdminCreateRequest;
use App\Mail\RequestSupportMessage;
use App\Mail\SendEmailAddress;
use App\Models\Regions;
use App\Models\RequestClient;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use App\Models\Users;
use App\Services\CreateNewUserService;
use App\Services\EmailLogService;
use App\Services\RequestClientService;
use App\Services\RequestTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    // It's code is written in CommonOperationController

    // -------------------- 2. Create Request -------------------------
    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard -> Admin create new request
    |--------------------------------------------------------------------------
    */

    /**
     * shows admin request page(form)
     * from this page admin can create request on behalf of patient
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
    public function createAdminPatientRequest(AdminCreateRequest $request, CreateNewUserService $createNewUserService, RequestTableService $requestTableService, RequestClientService $requestClientService, EmailLogService $emailLogService)
    {
        $isEmailStored = Users::where('email', $request->email)->first();

        $userId = $isEmailStored ? $isEmailStored->id : $createNewUserService->storeNewUser($request);

        // Generate confirmation number
        $confirmationNumber = ConfirmationNumber::generate($request);

        $requestTable = $requestTableService->createEntry($request, $userId, $confirmationNumber);
        // Store client details in RequestClient table
        $requestClientService->createEntry($request, $requestTable->id);
        // Store notes in RequestNotes table
        RequestNotes::create([
            'admin_notes' => $request->adminNote,
            'request_id' => $requestTable->id,
        ]);

        if (! $isEmailStored) {
            // Send email to user
            $emailAddress = $request->email;

            try {
                Mail::to($request->email)->send(new SendEmailAddress($emailAddress));
            } catch (\Throwable $th) {
                return view('errors.500');
            }

            $emailLogService->createEntry($request, $requestTable->id, $confirmationNumber);

            return redirect()->route('admin.status', 'new')->with(
                'successMessage',
                'Email for create account is sent & request created successfully!'
            );
        }
        // Redirect to provider status page with success message
        return redirect()->route('admin.status', 'new')->with('successMessage', 'Request Created Successfully!');
    }

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
    // * export All in admin listing pages
    public function exportAll()
    {
        $data = RequestClient::select(
            'request_client.first_name',
            'request_client.last_name',
            'request_client.date_of_birth',
            'request.first_name as request_first_name',
            'request.last_name as request_last_name',
            'request_client.created_at',
            'request_client.phone_number',
            DB::raw("CONCAT(request_client.street,',',request_client.city,',',request_client.state) AS address"),
            'request_client.notes'
        )->leftJoin('request', 'request.id', '=', 'request_client.request_id')->get();

        $exportAll = new UsersExport($data);
        return Excel::download($exportAll, 'AllData.xls');
    }

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

        $offDutyPhysicians = Helper::getPhysicianDutyStatus()['offDutyPhysicians'];

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
}
