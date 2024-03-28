<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\users;
use App\Models\Orders;
use App\Models\allusers;
use App\Models\Business;
use App\Models\EmailLog;
use App\Models\UserRoles;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\RequestStatus;
use App\Mail\sendEmailAddress;
use App\Models\request_Client;
use App\Models\RequestBusiness;
use Illuminate\Support\Facades\Mail;

// use App\Models\User;

class businessRequestController extends Controller
{

  public function businessRequests()
  {
    return view('patientSite/businessRequest');
  }

  public function create(Request $request)
  {

    $request->validate([
      'first_name' => 'required|min:2|max:30',
      'last_name' => 'required|min:2|max:30',
      'date_of_birth' => 'required',
      'email' => 'required|email|min:2|max:30',
      'phone_number' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
      'street' => 'required|min:2|max:30',
      'city' => 'required|min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
      'state' => 'required|min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
      'zipcode' => 'digits:6',
      'business_first_name' => 'required|min:2|max:30',
      'business_last_name' => 'required|min:2|max:30',
      'business_email' => 'required|email|min:2|max:30',
      'business_mobile' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
      'business_property_name' => 'required|min:2|max:30',
    ]);


    $isEmailStored = users::where('email', $request->email)->first();

    if ($isEmailStored == null) {
      // store email and phoneNumber in users table
      $requestEmail = new users();
      $requestEmail->username = $request->first_name . " " . $request->last_name;
      $requestEmail->email = $request->email;
      $requestEmail->phone_number = $request->phone_number;
      $requestEmail->save();

      // store all details of patient in allUsers table
      $requestUsers = new allusers();
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
      
    }
    $requestEmail = new users();
    // business data store in business field

    $business = new Business();
    $business->phone_number = $request->business_mobile;
    $business->business_name = $request->business_property_name;
    $business->save();

    //business request store in request table

    $requestBusiness = new RequestTable();
    $requestBusiness->status = 1;
    $requestBusiness->user_id = $requestEmail->id;
    $requestBusiness->request_type_id = $request->request_type;
    $requestBusiness->first_name = $request->business_first_name;
    $requestBusiness->last_name = $request->business_last_name;
    $requestBusiness->email = $request->business_email;
    $requestBusiness->phone_number = $request->business_mobile;
    $requestBusiness->relation_name = $request->business_property_name;
    $requestBusiness->case_number = $request->case_number;
    $requestBusiness->save();

    $patientRequest = new request_Client();
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


    $currentTime = Carbon::now();
    $currentDate = $currentTime->format('Y');

    $todayDate = $currentTime->format('Y-m-d');
    $entriesCount = RequestTable::whereDate('created_at', $todayDate)->count();


    $uppercaseStateAbbr = strtoupper(substr($request->state, 0, 2));
    $uppercaseLastName = strtoupper(substr($request->last_name, 0, 2));
    $uppercaseFirstName = strtoupper(substr($request->first_name, 0, 2));

    $confirmationNumber = $uppercaseStateAbbr . $currentDate . $uppercaseLastName . $uppercaseFirstName  . '00' . $entriesCount;

    if (!empty($requestBusiness->id)) {
      $requestBusiness->update(['confirmation_no' => $confirmationNumber]);
    }


    if ($isEmailStored == null) {
      // send email
      $emailAddress = $request->email;
      Mail::to($request->email)->send(new sendEmailAddress($emailAddress));

      EmailLog::create([
        'request_id' => $requestBusiness->id,
        'confirmation_number' => $confirmationNumber,
        'role_id' => 3,
        'is_email_sent' => 1,
        'sent_tries' => 1,
        'create_date' => now(),
        'sent_date' => now(),
        'email_template' => $request->email,
        'subject_name' => 'Create account by clicking on below link with below email address',
        'email' => $request->email,
      ]);
    }

    if ($isEmailStored == null) {
      return redirect()->route('submitRequest')->with('message', 'Email for Create Account is Sent');
    } else {
      return redirect()->route('submitRequest');
    }
  }
}
