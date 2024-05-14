<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Users;
use App\Models\AllUsers;
use App\Models\EmailLog;
use App\Models\UserRoles;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\RequestClient;
use App\Mail\SendEmailAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\AdminCreateRequest;

class AdminDashboardController extends Controller
{

    /**
     * shows admin request page(form)
     *  from this page admin can create request on behalf of patient
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */

    public function createNewRequest()
    {
        return view('adminPage/adminRequest');
    }


    /**
     * it stores request in request_client and request table and if user is new it stores details in all_user,users, make role_id 3 in user_roles table
     * and send email to create account using same email
     * @param \Illuminate\Http\Request $request  (the input which is enter by user)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */

    public function createAdminPatientRequest(AdminCreateRequest $request)
    {

        $isEmailStored = Users::where('email', $request->email)->first();
        if ($isEmailStored == null) {
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
            $requestUsers->zipcode = $request->zip;
            $requestUsers->save();

            $userRolesEntry = new UserRoles();
            $userRolesEntry->role_id = 3;
            $userRolesEntry->user_id = $requestEmail->id;
            $userRolesEntry->save();

            $requestData = new RequestTable();
            $requestData->status = 1;
            $requestData->user_id = $requestEmail->id;
            $requestData->request_type_id = $request->request_type;
            $requestData->first_name = $request->first_name;
            $requestData->last_name = $request->last_name;
            $requestData->email = $request->email;
            $requestData->phone_number = $request->phone_number;
            $requestData->save();

            $adminPatientRequest = new RequestClient();
            $adminPatientRequest->request_id = $requestData->id;
            $adminPatientRequest->first_name = $request->first_name;
            $adminPatientRequest->last_name = $request->last_name;
            $adminPatientRequest->date_of_birth = $request->date_of_birth;
            $adminPatientRequest->email = $request->email;
            $adminPatientRequest->phone_number = $request->phone_number;
            $adminPatientRequest->street = $request->street;
            $adminPatientRequest->city = $request->city;
            $adminPatientRequest->state = $request->state;
            $adminPatientRequest->zipcode = $request->zip;
            $adminPatientRequest->room = $request->room;
            $adminPatientRequest->save();

            // store notes in request_notes table
            $request_notes = new RequestNotes();
            $request_notes->request_id = $requestData->id;
            $request_notes->admin_notes = $request->adminNote;
            $request_notes->created_by = 'admin';
            $request_notes->save();
        } else {
            $requestData = new RequestTable();
            $requestData->user_id = $isEmailStored->id;
            $requestData->request_type_id = 1;
            $requestData->first_name = $request->first_name;
            $requestData->last_name = $request->last_name;
            $requestData->email = $request->email;
            $requestData->phone_number = $request->phone_number;
            $requestData->status = 1;
            $requestData->save();

            $adminPatientRequest = new RequestClient();
            $adminPatientRequest->request_id = $requestData->id;
            $adminPatientRequest->first_name = $request->first_name;
            $adminPatientRequest->last_name = $request->last_name;
            $adminPatientRequest->date_of_birth = $request->date_of_birth;
            $adminPatientRequest->email = $request->email;
            $adminPatientRequest->phone_number = $request->phone_number;
            $adminPatientRequest->street = $request->street;
            $adminPatientRequest->city = $request->city;
            $adminPatientRequest->state = $request->state;
            $adminPatientRequest->zipcode = $request->zip;
            $adminPatientRequest->room = $request->room;
            $adminPatientRequest->save();

            // store notes in request_notes table
            $request_notes = new RequestNotes();
            $request_notes->request_id = $requestData->id;
            $request_notes->admin_notes = $request->adminNote;
            $request_notes->created_by = 'admin';
            $request_notes->save();
        }

        // confirmation number
        $currentTime = Carbon::now();
        $currentDate = $currentTime->format('Y');

        $todayDate = $currentTime->format('Y-m-d');
        $entriesCount = RequestTable::whereDate('created_at', $todayDate)->count();

        $uppercaseStateAbbr = strtoupper(substr($request->state, 0, 2));
        $uppercaseLastName = strtoupper(substr($request->last_name, 0, 2));
        $uppercaseFirstName = strtoupper(substr($request->first_name, 0, 2));

        $confirmationNumber = $uppercaseStateAbbr . $currentDate . $uppercaseLastName . $uppercaseFirstName  . '00' . $entriesCount;

        // if (!empty($requestData->id)) {
        if ($requestData->id) {
            $requestData->update(['confirmation_no' => $confirmationNumber]);
        }

        try {
            if ($isEmailStored === null) {
                // send email
                $emailAddress = $request->email;
                Mail::to($request->email)->send(new SendEmailAddress($emailAddress));

                EmailLog::create([
                    'role_id' => 3,
                    'request_id' =>  $requestData->id,
                    'confirmation_number' => $confirmationNumber,
                    'is_email_sent' => 1,
                    'recipient_name' => $request->first_name . ' ' . $request->last_name,
                    'sent_tries' => 1,
                    'create_date' => now(),
                    'sent_date' => now(),
                    'email_template' => $request->email,
                    'subject_name' => 'Create account by clicking on below link with below email address',
                    'email' => $request->email,
                    'action' => 5,
                ]);
                return redirect()->route('admin.status', 'new')->with('message', 'Email for create account is sent and Request is Submitted');
            } else {
                return redirect()->route('admin.status', 'new')->with('message', 'Request is Submitted');
            }
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }


    /**
     * this page will show when admin edit their profile through user access page
     * @param mixed $id ( $id is the id of users table)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminProfile($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $adminProfileData = Admin::select(
                'admin.first_name',
                'admin.last_name',
                'admin.email',
                'admin.mobile',
                'admin.address1',
                'admin.address2',
                'admin.city',
                'admin.zip',
                'admin.status',
                'admin.user_id',
                'alt_phone',
                'role.name',
                'regions.region_name',
                'regions.id'
            )
                ->leftJoin('role', 'role.id', 'admin.role_id')
                ->leftJoin('users', 'users.id', 'admin.user_id')
                ->leftJoin('regions', 'regions.id', 'admin.region_id')
                ->where('user_id', $id)
                ->first();

            return view('adminPage/adminProfile', compact('adminProfileData'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }


    /**
     * this page will show admin profile edit and admin can route to this page from any page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminProfilePage()
    {
        $adminData = Auth::user();

        $adminProfileData = Admin::select(
            'admin.first_name',
            'admin.last_name',
            'admin.email',
            'admin.mobile',
            'admin.address1',
            'admin.address2',
            'admin.city',
            'admin.zip',
            'admin.status',
            'admin.user_id',
            'alt_phone',
            'role.name',
            'regions.region_name',
            'regions.id'
        )
            ->leftJoin('role', 'role.id', 'admin.role_id')
            ->leftJoin('users', 'users.id', 'admin.user_id')
            ->leftJoin('regions', 'regions.id', 'admin.region_id')
            ->where('user_id', $adminData->id)
            ->first();

        return view('adminPage/adminProfile', compact('adminProfileData'));
    }


    /**
     * it will update password in users table
     * @param \Illuminate\Http\Request $request  (the input which is enter by user(admin))
     * @param mixed $id (id of users table)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminChangePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8|max:30'
        ]);

        // Update data in users table
        $updateUserData = [
            'password' => Hash::make($request->password),
        ];

        Users::where('id', $id)->first()->update($updateUserData);

        return back()->with('message', 'Your password is updated successfully');
    }


    /**
     * it will update firstname,lastname,email,mobile in allusers and admin table
     * @param \Illuminate\Http\Request $request  (the input which is enter by user)
     * @param mixed $id (id of users table)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminInfoUpdate(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'required|min:2|max:30',
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'confirm_email' => 'required|min:2|max:40|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required',
        ]);

        // Update in admin table

        $updateAdminInformation = Admin::with('users')->where('user_id', $id)->first();

        $updateAdminInformation->first_name = $request->first_name;
        $updateAdminInformation->last_name = $request->last_name;
        $updateAdminInformation->email = $request->email;
        $updateAdminInformation->mobile = $request->phone_number;

        $updateAdminInformation->save();

        // update Data in allusers table

        $updateAdminInfoAllUsers = AllUsers::where('user_id', $id)->first();

        $updateAdminInfoAllUsers->first_name = $request->first_name;
        $updateAdminInfoAllUsers->last_name = $request->last_name;
        $updateAdminInfoAllUsers->email = $request->email;
        $updateAdminInfoAllUsers->mobile = $request->phone_number;
        $updateAdminInfoAllUsers->save();

        // update email and phone number in users table

        $updateUserInfo = Users::where('id', $id)->first();
        $updateUserInfo->email = $request->email;
        $updateUserInfo->phone_number = $request->phone_number;
        $updateUserInfo->save();

        return back()->with('message', 'Your Administration Information is updated successfully');
    }


    /**
     * it will update address1,address2 ,city,zip ,state,alternate mobile in admin and allusers table
     * @param \Illuminate\Http\Request $request  (the input which is enter by user)
     * @param mixed $id (id of users table)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminMailInfoUpdate(Request $request, $id)
    {
        $request->validate([
            'address1' => 'required|min:2|max:50',
            'address2' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'zip' => 'digits:6',
            'alt_mobile' => 'required|min_digits:10|max_digits:10',
        ]);

        // Update in admin table
        $updateAdminInformation = Admin::with('users')->where('user_id', $id)->first();

        $updateAdminInformation->city = $request->city;
        $updateAdminInformation->address1 = $request->address1;
        $updateAdminInformation->address2 = $request->address2;
        $updateAdminInformation->zip = $request->zip;
        $updateAdminInformation->alt_phone = $request->alt_mobile;
        $updateAdminInformation->region_id = $request->select_state;
        $updateAdminInformation->save();

        // update Data in allusers table
        $updateAdminInfoAllUsers = AllUsers::where('user_id', $id)->first();
        $updateAdminInfoAllUsers->city = $request->city;
        $updateAdminInfoAllUsers->street = $request->address1;
        $updateAdminInfoAllUsers->zipcode = $request->zip;
        $updateAdminInfoAllUsers->save();

        return back()->with('message', 'Your Mailing and Billing Information is updated successfully');
    }
}
