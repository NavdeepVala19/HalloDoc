<?php

namespace App\Http\Controllers;

use App\Mail\SendEmailAddress;
use App\Models\AllUsers;
use App\Models\Business;
use App\Models\EmailLog;
use App\Models\RequestTable;
use App\Models\RequestClient;
use App\Models\RequestBusiness;
use App\Models\Users;
use App\Models\UserRoles;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


// this controller is responsible for creating/storing the business request
class businessRequestController extends Controller
{
  public function businessRequests()
  {
    return view('patientSite/businessRequest');
  }


  /**
   *@param $request the input which is enter by user

   * it stores request in request_client and request table and if user(patient) is new it stores details in all_user,users, make role_id 3 in user_roles table
   * and send email to create account using same email
   */

  public function create(Request $request)
  {
    $request->validate([
      'first_name' => 'required|min:3|max:15|alpha',
      'last_name' => 'required|min:3|max:15|alpha',
      'date_of_birth' => 'required|before:today',
      'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
      'phone_number' => 'required|min_digits:10|max_digits:10',
      'street' => 'required|min:2|max:50|regex:/^[a-zA-Z0-9\s,_-]+?$/',
      'city' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
      'state' => 'required|min:2|max:30|regex:/^[a-zA-Z ]+?$/',
      'zipcode' => 'digits:6|gte:1',
      'business_first_name' => 'required|min:3|max:15|alpha',
      'business_last_name' => 'required|min:3|max:15|alpha',
      'business_email' => 'required|email|min:2|max:30|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
      'business_mobile' => 'required',
      'business_property_name' => 'required|min:2|max:30',
      'symptoms' => 'nullable|min:5|max:200|regex:/^[a-zA-Z0-9 \-_,()]+$/',
      'case_number' => 'nullable|min:0|max:1000',
      'room' => 'nullable|min:0|max:1000'
    ]);

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
      $requestUsers->zipcode = $request->zipcode;
      $requestUsers->save();

      $userRolesEntry = new UserRoles();
      $userRolesEntry->role_id = 3;
      $userRolesEntry->user_id = $requestEmail->id;
      $userRolesEntry->save();

      $requestBusiness = new RequestTable();
      $requestBusiness->status = 1;
      $requestBusiness->user_id = $requestEmail->id;
      $requestBusiness->request_type_id = 4;
      $requestBusiness->first_name = $request->business_first_name;
      $requestBusiness->last_name = $request->business_last_name;
      $requestBusiness->email = $request->business_email;
      $requestBusiness->phone_number = $request->business_mobile;
      $requestBusiness->relation_name = $request->business_property_name;
      $requestBusiness->case_number = $request->case_number;
      $requestBusiness->save();

      // business data store in business field
      $business = new Business();
      $business->phone_number = $request->business_mobile;
      $business->address1 = $request->street;
      $business->address2 = $request->city;
      $business->zipcode = $request->zipcode;
      $business->business_name = $request->business_property_name;
      $business->save();

      //business request store in request table
      $patientRequest = new RequestClient();
      $patientRequest->request_id = $requestBusiness->id;
      $patientRequest->first_name = $request->first_name;
      $patientRequest->last_name = $request->last_name;
      $patientRequest->date_of_birth = $request->date_of_birth;
      $patientRequest->email = $request->email;
      $patientRequest->phone_number = $request->phone_number;
      $patientRequest->street = $request->street;
      $patientRequest->city = $request->city;
      $patientRequest->state = $request->state;
      $patientRequest->zipcode = $request->zipcode;
      $patientRequest->room = $request->room;
      $patientRequest->notes = $request->symptoms;
      $patientRequest->save();

      // store data in request business table
      $businessRequest = new RequestBusiness();
      $businessRequest->request_id = $requestBusiness->id;
      $businessRequest->business_id = $business->id;
      $businessRequest->save();
    } else {
      $requestBusiness = new RequestTable();
      $requestBusiness->status = 1;
      $requestBusiness->user_id = $isEmailStored->id;
      $requestBusiness->request_type_id = 4;
      $requestBusiness->first_name = $request->business_first_name;
      $requestBusiness->last_name = $request->business_last_name;
      $requestBusiness->email = $request->business_email;
      $requestBusiness->phone_number = $request->business_mobile;
      $requestBusiness->relation_name = $request->business_property_name;
      $requestBusiness->case_number = $request->case_number;
      $requestBusiness->save();

      // business data store in business field
      $business = new Business();
      $business->phone_number = $request->business_mobile;
      $business->address1 = $request->street;
      $business->address2 = $request->city;
      $business->zipcode = $request->zipcode;
      $business->business_name = $request->business_property_name;
      $business->save();

      //business request store in request table
      $patientRequest = new RequestClient();
      $patientRequest->request_id = $requestBusiness->id;
      $patientRequest->first_name = $request->first_name;
      $patientRequest->last_name = $request->last_name;
      $patientRequest->date_of_birth = $request->date_of_birth;
      $patientRequest->email = $request->email;
      $patientRequest->phone_number = $request->phone_number;
      $patientRequest->street = $request->street;
      $patientRequest->city = $request->city;
      $patientRequest->state = $request->state;
      $patientRequest->zipcode = $request->zipcode;
      $patientRequest->room = $request->room;
      $patientRequest->notes = $request->symptoms;
      $patientRequest->save();

      // store data in request business table
      $businessRequest = new RequestBusiness();
      $businessRequest->request_id = $requestBusiness->id;
      $businessRequest->business_id = $business->id;
      $businessRequest->save();
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

    // if (!empty($requestBusiness->id)) {
    if ($requestBusiness->id) {
      $requestBusiness->update(['confirmation_no' => $confirmationNumber]);
    }

    try {
      if ($isEmailStored == null) {
        // send email
        $emailAddress = $request->email;
        Mail::to($request->email)->send(new SendEmailAddress($emailAddress));

        EmailLog::create([
          'request_id' => $requestBusiness->id,
          'confirmation_number' => $confirmationNumber,
          'role_id' => 3,
          'recipient_name' => $request->first_name . ' ' . $request->last_name,
          'is_email_sent' => 1,
          'sent_tries' => 1,
          'create_date' => now(),
          'sent_date' => now(),
          'email_template' => $request->email,
          'subject_name' => 'Create account by clicking on below link with below email address',
          'email' => $request->email,
          'action' => 5,
        ]);

        return redirect()->route('submit.request')->with('message', 'Email for Create Account is Sent and Request is Submitted');
      } else {
        return redirect()->route('submit.request')->with('message', 'Request is Submitted');
      }
    } catch (\Throwable $th) {
      return view('errors.500');
    }
  }
}
