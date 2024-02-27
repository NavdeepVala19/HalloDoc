<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Orders;
use App\Models\RequestBusiness;
use App\Models\RequestStatus;
use App\Models\request_Client;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\allusers;
use App\Models\RequestNotes;
use App\Models\users;

// use App\Models\User;

class businessRequestController extends Controller
{

  public function create(Request $request)
  {


    // $request->validate([
    //     'first_name'=>'required|min:2|max:30',
    //     'last_name'=>'string|min:2|max:30',
    //     'email' => 'required|email|min:2|max:30',
    //     'phone_number'=>'required|numeric|digits:10',
    //     'street'=>'min:2|max:30',
    //     'city' => 'regex:/^[\pL\s\-]+$/u|min:2|max:30',
    //     'zipcode' => 'numeric', 
    //     'state' => 'regex:/^[\pL\s\-]+$/u|min:2|max:30',
    //     'room' => 'numeric',
    //     'business_first_name'=>'required|min:2|max:30',
    //     'business_email' =>'required|email|min:2|max:30',
    //     'business_mobile'=>'required',
    //     'business_property_name'=>'required|min:2|max:30',
    // ]);

    // store email and phoneNumber in users table
    $requestEmail = new users();
    $requestEmail->email = $request->email;
    $requestEmail->phone_number = $request->phone_number;

    $requestEmail->save();



    // business data store in business field

    $business = new Business();
    $business->phone_number = $request->business_mobile;
    $business->business_name = $request->business_property_name;

    $business->save();

    //business request store in request table

    $requestBusiness = new RequestTable();

    $requestStatus = new RequestStatus();


    $requestBusiness->status = $requestStatus->id;
    $requestBusiness->user_id = $requestEmail->id;
    $requestBusiness->request_type_id = $request->request_type;
    $requestBusiness->first_name = $request->business_first_name;
    $requestBusiness->last_name = $request->business_last_name;
    $requestBusiness->email = $request->business_email;
    $requestBusiness->phone_number = $request->business_mobile;
    $requestBusiness->relation_name = $request->business_property_name;
    $requestBusiness->case_number = $request->case_number;
    $requestBusiness->save();


    $requestStatus->request_id = $requestBusiness->id;
    $requestStatus->status = 1;
    $requestStatus->save();

    if (!empty($requestStatus)) {
      $requestBusiness->update(["status" => $requestStatus->id]);
    }





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


    // store symptoms in request_notes table

    // $request_notes = new RequestNotes();
    // $request_notes->request_id = $requestBusiness->id;
    // $request_notes->patient_notes = $request->symptoms;

    // $request_notes->save();





    // store all details of patient in allUsers table

    $requestUsers = new allusers();
    $requestUsers->first_name = $request->first_name;
    $requestUsers->last_name = $request->last_name;
    $requestUsers->email = $request->email;
    $requestUsers->mobile = $request->phone_number;
    $requestUsers->street = $request->street;
    $requestUsers->city = $request->city;
    $requestUsers->state = $request->state;
    $requestUsers->zipcode = $request->zipcode;
    $requestUsers->save();


    // store data in request business table 

    $businessRequest = new RequestBusiness();
    $businessRequest->request_id = $requestBusiness->id;
    $businessRequest->business_id = $business->id;
    $businessRequest->save();
    

    // store data in orders table

    $orders = new Orders();
    $orders->vendor_id = $business->id;
    $orders->request_id = $requestBusiness->id;
    $orders->save();

    

    return view('patientSite/submitScreen');

  }
}

