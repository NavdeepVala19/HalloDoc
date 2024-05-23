<?php

namespace App\Http\Controllers;

use App\Helpers\ConfirmationNumber;
use App\Helpers\Helper;
use App\Http\Requests\ProviderCreateRequest;
use App\Http\Requests\SendMailRequest;
use App\Mail\ProviderRequest;
use App\Mail\SendEmailAddress;
use App\Mail\SendMail;
use App\Models\Admin;
use App\Models\EmailLog;
use App\Models\PhysicianRegion;
use App\Models\Provider;
use App\Models\Regions;
use App\Models\requestTable;
use App\Models\SMSLogs;
use App\Models\User;
use App\Models\Users;
use App\Services\CreateNewUserService;
use App\Services\EmailLogService;
use App\Services\RequestClientService;
use App\Services\RequestNotesService;
use App\Services\RequestTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Twilio\Rest\Client;

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
            'newCase' => RequestTable::where('status', Helper::STATUS_NEW)->where('physician_id', $providerId)->count(),
            // pending state(Status = 3) -> Accepted by provider
            'pendingCase' => RequestTable::where('status', Helper::STATUS_PENDING)->where('physician_id', $providerId)->count(),
            // Active State(Status = 4,5) -> MDEnRoute(agreement sent and accepted by patient), MDOnSite(call type selected by provider[HouseCall])
            'activeCase' => RequestTable::whereIn('status', Helper::STATUS_ACTIVE)->where('physician_id', $providerId)->count(),
            // Conclude State(Status = 6) -> when consult selected during Encounter pop-up or HouseCall Completed
            'concludeCase' => RequestTable::where('status', Helper::STATUS_CONCLUDE)->where('physician_id', $providerId)->count(),
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
        if (is_array(Helper::getStatusId($status))) {
            $query = RequestTable::with('requestClient')->whereIn('status', Helper::getStatusId($status))->where('physician_id', $providerId);
        } else {
            $query = RequestTable::with('requestClient')->where('status', Helper::getStatusId($status))->where('physician_id', $providerId);
        }

        // Filter by Category if not 'all' (These will enter condition only if there is any filter selected)
        if ($category !== 'all') {
            $query->where('request_type_id', Helper::getCategoryId($category));
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
    public function cases(Request $request, $status = 'new')
    {
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
            return $this->cases($request, $status);
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
    public function search(Request $request, $status = 'new')
    {
        // store searchTerm in session
        $request->session()->put('searchTerm', $request->search);

        return $this->cases($request, $status);
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
     * Store request data, made by Provider.
     *
     * @param Request $request HTTP Request object
     *
     * @return \Illuminate\Http\RedirectResponse provider status page
     */
    public function createRequest(ProviderCreateRequest $request, CreateNewUserService $createNewUserService, RequestTableService $requestTableService, RequestClientService $requestClientService, RequestNotesService $requestNotesService, EmailLogService $emailLogService)
    {
        $user = Auth::user();
        $providerId = Provider::where('user_id', $user->id)->first()->id;

        // check if email already exists in users table
        $isEmailStored = Users::where('email', $request->email)->first();

        $userId = $isEmailStored ? $isEmailStored->id : $createNewUserService->storeNewUser($request);

        // Generate confirmation number
        $confirmationNumber = ConfirmationNumber::generate($request);

        $requestTable = $requestTableService->createEntry($request, $userId, $confirmationNumber, $providerId);
        // Store client details in RequestClient table
        $requestClientService->createEntry($request, $requestTable->id);
        // Store notes in RequestNotes table
        $requestNotesService->createEntry($request, $requestTable->id);

        if (! $isEmailStored) {
            // Send email to user
            $emailAddress = $request->email;

            try {
                Mail::to($request->email)->send(new SendEmailAddress($emailAddress));
            } catch (\Throwable $th) {
                return view('errors.500');
            }

            $emailLogService->createEntry($request, $requestTable->id, $confirmationNumber, $providerId);

            return redirect()->route('provider.status', 'pending')->with('successMessage', 'Email for create account is sent & request created successfully!');
        }
        // Redirect to provider status page with success message
        return redirect()->route('provider.status', 'pending')->with('successMessage', 'Request Created Successfully!');
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
            'password' => 'required|min:5',
        ]);

        $userId = Provider::where('id', $request->providerId)->first()->user_id;

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
            'recipient_name' => $admin->first_name . ' ' . $admin->last_name,
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
     * Send Mail to patient with link to create request page
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
    public function sendMail(SendMailRequest $request)
    {
        // Generate the link using route() helper (assuming route parameter is optional)
        $link = route('submit.request');

        try {
            // send SMS Logic
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

        $user = Auth::user();
        $providerId = Provider::where('user_id', $user->id)->first()->id;

        $name = $request->first_name . ' ' . $request->last_name;

        SMSLogs::create(
            [
                'sms_template' => 'Hii ,Click on the below link to create request',
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
        try {
            Mail::to($request->email)->send(new SendMail($request->all()));
        } catch (\Throwable $th) {
            return view('errors.500');
        }
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

        return redirect()->back()->with('successMessage', 'Link Sent Successfully!');
    }
}
