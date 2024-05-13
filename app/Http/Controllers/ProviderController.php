<?php

namespace App\Http\Controllers;

// Different Models used in these Controller
use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Users;
use App\Mail\SendMail;
use App\Models\Regions;
use App\Models\SMSLogs;
use Twilio\Rest\Client;
use App\Models\AllUsers;
use App\Models\EmailLog;
use App\Models\Provider;
use App\Models\UserRoles;

// For sending Mails
use App\Models\RequestNotes;
use App\Models\requestTable;
use Illuminate\Http\Request;

// For Sending SMS
use App\Mail\ProviderRequest;
use App\Models\RequestClient;

use App\Mail\SendEmailAddress;
// Common facades used for different functionalities
use App\Models\PhysicianRegion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// For Date Formatting
use Illuminate\Support\Facades\Mail;

use App\Http\Requests\SendMailRequest;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\ProviderCreateRequest;

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
        ];
        return $statusMapping[$status];
    }

    // For Provider redirect to new State(By Default)
    public function providerDashboard()
    {
        return redirect('/provider/new');
    }

    /**
     *  Counts Total Number of cases for particular Physician and having particular state
     *
     * @param int $providerId Id of the LoggedIn provider.
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
     * @return \illuminate\View\View
     */
    public function cases(Request $request, $status = 'new', $category = "all")
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
     * Store request data, made by Provider.
     *
     * @param Request $request HTTP Request object
     * @return \Illuminate\Http\RedirectResponse provider status page
     */
    public function createRequest(ProviderCreateRequest $request)
    {
        // check if email already exists in users table
        $isEmailStored = Users::where('email', $request->email)->first();

        $user = Auth::user();
        $providerId = Provider::where('user_id', $user->id)->first()->id;

        // If email doesn't exist, store email, username, phone_number in users table
        if ($isEmailStored === null) {
            // store email and phoneNumber in users table
            $requestEmail = new Users();
            $requestEmail->username = $request->first_name . " " . $request->last_name;
            $requestEmail->email = $request->email;
            $requestEmail->phone_number = $request->phone_number;
            $requestEmail->save();

            // store all details of patient in allUsers table
            $requestUsers = new AllUsers();
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

            $userRolesEntry = new UserRoles();
            $userRolesEntry->role_id = 3;
            $userRolesEntry->user_id = $requestEmail->id;
            $userRolesEntry->save();

            // Store request details in requestTable table
            $requestTable = new requestTable();
            $requestTable->status = 3;
            $requestTable->physician_id = $providerId;
            $requestTable->user_id = $requestEmail->id;
            $requestTable->request_type_id = $request->request_type_id;
            $requestTable->first_name = $request->first_name;
            $requestTable->last_name = $request->last_name;
            $requestTable->email = $request->email;
            $requestTable->phone_number = $request->phone_number;
            $requestTable->save();
        } else {
            // Store request details in requestTable table
            $requestTable = new requestTable();
            $requestTable->status = 3;
            $requestTable->physician_id = $providerId;
            $requestTable->user_id = $isEmailStored->id;
            $requestTable->request_type_id = $request->request_type_id;
            $requestTable->first_name = $request->first_name;
            $requestTable->last_name = $request->last_name;
            $requestTable->email = $request->email;
            $requestTable->phone_number = $request->phone_number;
            $requestTable->save();
        }

        // Store client details in RequestClient table
        $requestClient = new RequestClient();
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

        // Store notes in RequestNotes table
        $requestNotes = new RequestNotes();
        $requestNotes->request_id = $requestTable->id;
        $requestNotes->physician_notes = $request->note;
        $requestNotes->created_by = 'physician';
        $requestNotes->save();

        // Generate confirmation number
        $currentTime = Carbon::now();
        $currentDate = $currentTime->format('Y');
        $todayDate = $currentTime->format('Y-m-d');
        $entriesCount = RequestTable::whereDate('created_at', $todayDate)->count();
        $uppercaseStateAbbr = strtoupper(substr($request->state, 0, 2));
        $uppercaseLastName = strtoupper(substr($request->last_name, 0, 2));
        $uppercaseFirstName = strtoupper(substr($request->first_name, 0, 2));
        $confirmationNumber = $uppercaseStateAbbr . $currentDate . $uppercaseLastName . $uppercaseFirstName  . '00' . $entriesCount;

        // Update RequestTable with confirmation number
        // if (!empty($requestTable->id)) {
        if ($requestTable->id) {
            $requestTable->update(['confirmation_no' => $confirmationNumber]);
        }

        if ($isEmailStored === null) {
            // Send email to user
            $emailAddress = $request->email;

            try {
                Mail::to($request->email)->send(new SendEmailAddress($emailAddress));
            } catch (\Throwable $th) {
                return view('errors.500');
            }

            // Log email in EmailLog table
            $user = Auth::user();
            $providerId = Provider::where('user_id', $user->id)->first()->id;

            EmailLog::create([
                'role_id' => 3,
                'request_id' =>  $requestTable->id,
                'recipient_name' => $request->first_name . " " . $request->last_name,
                'confirmation_number' => $confirmationNumber,
                'provider_id' => $providerId,
                'is_email_sent' => 1,
                'sent_tries' => 1,
                'action' => 5,
                'create_date' => now(),
                'sent_date' => now(),
                'email_template' => 'Create Account With Provided Email',
                'subject_name' => 'Create account by clicking on below link with below email address',
                'email' => $request->email,
            ]);
            return redirect()->route('provider.status', 'pending')->with('successMessage', 'Email for create account is sent & request created successfully!');
        }
        // Redirect to provider status page with success message
        return redirect()->route("provider.status", 'pending')->with('successMessage', "Request Created Successfully!");
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
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
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

    /**
     * Provider send an email to Admin with the request of changes needed in the profile
     *
     * @param Request $request
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
            'recipient_name' => $admin->first_name . " " . $admin->last_name,
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
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
    public function sendMail(SendMailRequest $request)
    {
        // Generate the link using route() helper (assuming route parameter is optional)
        $link = route('submit.request');

        try {
            // send SMS Logic
            $sid = getenv("TWILIO_SID");
            $token = getenv("TWILIO_AUTH_TOKEN");
            $senderNumber = getenv("TWILIO_PHONE_NUMBER");

            $twilio = new Client($sid, $token);

            $twilio->messages
                ->create(
                    "+91 99780 71802", // to
                    [
                        "body" => "Hii {$request->first_name} {$request->last_name}, Click on the this link to create request:{$link}",
                        "from" =>  $senderNumber
                    ]
                );
        } catch (\Throwable $th) {
            return view('errors.500');
        }

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

        return redirect()->back()->with('successMessage', "Link Sent Successfully!");
    }
}
