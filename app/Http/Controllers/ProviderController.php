<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProviderCreateRequest;
use App\Mail\ProviderRequest;
use App\Mail\SendEmailAddress;
use App\Models\Admin;
use App\Models\EmailLog;
use App\Models\PhysicianRegion;
use App\Models\Provider;
use App\Models\Regions;
use App\Models\RequestTable;
use App\Models\User;
use App\Models\Users;
use App\Services\CreateEmailLogService;
use App\Services\CreateNewUserService;
use App\Services\ProviderCreateRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ProviderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Provider Listing (Cases with different status, category and searchTerm)
    |--------------------------------------------------------------------------
    |
    | Provider Listing pages functionality -> with particullar provider LoggedIn
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

    // For Provider redirect to new State(By Default)
    public function providerDashboard()
    {
        return redirect('/provider/new');
    }

    /**
     *  Counts Total Number of cases for particular Physician and having particular state
     *
     * @param int $providerId Id of the LoggedIn provider.
     *
     * @return int total number of cases, as per the status.
     */
    public function totalCasesCount($providerId)
    {
        // Total count of cases as per the status (displayed in all listing pages)
        return [
            // unassigned case(Status = 1) -> assigned to provider but not accepted
            'newCase' => RequestTable::where('status', self::STATUS_NEW)->where('physician_id', $providerId)->count(),
            // pending state(Status = 3) -> Accepted by provider
            'pendingCase' => RequestTable::where('status', self::STATUS_PENDING)->where('physician_id', $providerId)->count(),
            // Active State(Status = 4,5) -> MDEnRoute(agreement sent and accepted by patient), MDOnSite(call type selected by provider[HouseCall])
            'activeCase' => RequestTable::whereIn('status', self::STATUS_ACTIVE)->where('physician_id', $providerId)->count(),
            // Conclude State(Status = 6) -> when consult selected during Encounter pop-up or HouseCall Completed
            'concludeCase' => RequestTable::where('status', self::STATUS_CONCLUDE)->where('physician_id', $providerId)->count(),
        ];
    }

    /**
     *  Build Query as per filters, search query or normal cases
     *
     * @param string $status status of the cases [new, active, pending, conclude].
     * @param string $category category of the cases [all, patient, family, business, concierge].
     * @param string $searchTerm search term to filter the cases.
     * @param string $providerId id of the LoggedIn provider.
     *
     * @return object $query formed as per the status, category selected, any search term entered and the logged in provider
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
        // if (isset($searchTerm) && !empty($searchTerm)) {
        if (isset($searchTerm) && $searchTerm) {
            $query->whereHas('requestClient', function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")->orWhere('last_name', 'like', "%{$searchTerm}%");
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
        // Use Session to filter by category and searchTerm
        $searchTerm = $request->session()->get('searchTerm', null);
        $category = $request->session()->get('category', 'all');

        $userData = Auth::user();
        $providerId = Provider::where('user_id', $userData->id)->value('id');
        $count = $this->totalCasesCount($providerId);
        $query = $this->buildQuery($status, $category, $searchTerm, $providerId);

        $cases = $query->orderByDesc('id')->paginate(10);

        $viewName = 'providerPage.providerTabs.' . $status . 'Listing';
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
        Session::forget(['searchTerm', 'category']);

        if ($status === 'new' || $status === 'pending' || $status === 'active' || $status === 'conclude') {
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
    public function filter(Request $request, $status = 'new', $category = 'all')
    {
        // Store category in the session
        $request->session()->put('category', $category);

        if ($status === 'new' || $status === 'pending' || $status === 'active' || $status === 'conclude' && $category === 'all' || $category === 'patient' || $category === 'family' || $category === 'business' || $category === 'concierge') {
            return $this->cases($request, $status, $category);
        }
        return view('errors.404');
    }

    /**
     * Search for searchTerm request in first_name & last_name of requestclient
     *
     * @param \illuminate\Http\Request $request
     * @param string $status different status names.
     * @param string $category different category names.
     *
     * @return \illuminate\View\View
     */
    public function search(Request $request, $status = 'new', $category = 'all')
    {
        // store searchTerm in session
        $request->session()->put('searchTerm', $request->search);

        return $this->cases($request, $status, $category);
    }

    /**
     * Display the provider request page.
     *
     * This function returns the view for the provider request page.
     * It is used to show the form for creating a new provider request.
     *
     * @return \Illuminate\View\View The provider request page view
     */
    public function viewCreateRequest()
    {
        return view('providerPage/providerRequest');
    }

    /**
     * Summary of createRequest
     *
     * @param \App\Http\Requests\ProviderCreateRequest $request HTTP Request object
     * @param \App\Services\ProviderCreateRequestService $providerCreateRequestService
     *
     * @return \Illuminate\Http\RedirectResponse provider status page
     */
    public function createRequest(ProviderCreateRequest $request, ProviderCreateRequestService $providerCreateRequestService, CreateNewUserService $createNewUserService, CreateEmailLogService $createEmailLogService)
    {
        $providerId = Provider::where('user_id', Auth::user()->id)->value('id');
        $isEmailStored = Users::where('email', $request->email)->first();
        $requestId = $providerCreateRequestService->storeRequest($request, $providerId);
        if ($isEmailStored === null) {
            $createNewUserService->storeNewUsers($request);
            try {
                Mail::to($request->email)->send(new SendEmailAddress($request->email));
                $createEmailLogService->storeEmailLogs($request, $requestId, $providerId);
            } catch (\Throwable $th) {
                return view('errors.500');
            }
        }
        $redirectMsg = $isEmailStored ? 'Request is Submitted' : 'Email for Create Account is Sent and Request is Submitted';
        return redirect()->route('provider.status', 'pending')->with('successMessage', $redirectMsg);
    }

    /**
     * Display MyProfile Provider Page with data of LoggedIn provider
     *
     * @return \Illuminate\View\View provider MyProfile Page
     */
    public function providerProfile()
    {
        $userData = Auth::user();
        $provider = Provider::where('user_id', $userData->id)->first();
        $regions = Regions::get();
        $physicianRegions = PhysicianRegion::where('provider_id', $provider->id)->get();
        return view('providerPage.providerProfile', compact('provider', 'userData', 'regions', 'physicianRegions'));
    }

    /**
     * Reset password of provider
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|max:100',
        ]);

        $userId = Provider::where('id', $request->providerId)->value('user_id');

        User::where('id', $userId)->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password Reset Successfully');
    }

    /**
     * Provider send an email to Admin with the request of changes needed in the profile
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
    public function editProfileMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|min:5|max:200|regex:/^[a-zA-Z0-9 ,_.-]+?$/'
        ]);
        
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
            'recipient_name' => $admin->first_name.' ' .$admin->last_name,
            'email_template' => 'email.providerRequest',
            'email' => $admin->email,
            'sent_tries' => 1,
        ]);

        try {
            Mail::to($admin->email)->send(new ProviderRequest($admin, $provider, $request));
        } catch (\Throwable $th) {
            return view('errors.500');
        }

        return redirect()->back()->with('mailSentToAdmin', 'Email Sent to Admin - to make requested changes!');
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
        ];
        return $statusMapping[$status];
    }
}
