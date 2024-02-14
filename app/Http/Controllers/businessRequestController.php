<?php

namespace App\Http\Controllers;

use App\Models\request_Client;
use App\Models\RequestTable;
use Illuminate\Http\Request;
// use App\Models\User;

class businessRequestController extends Controller
{
 
    public function create(Request $request){

        // dd($request->all());
        $request->validate([
            'first_name'=>'required|min:2|max:30',
            'last_name'=>'string|min:2|max:30',
            'email' => 'required|email|min:2|max:30',
            'phone_number'=>'required',
            'street'=>'min:2|max:30',
            'city' => 'string|min:2|max:30',
            'zipcode' => 'numeric', 
            'state' => 'string|min:2|max:30',
            'room' => 'numeric',
            'business_first_name'=>'required|min:2|max:30',
            'business_email' =>'required|email|min:2|max:30',
            'business_mobile'=>'required',
            'business_property_name'=>'required|min:2|max:30',

        ]);

          //business request creating
        
          $requestBusiness = new RequestTable();
          $requestBusiness->request_type_id= $request->request_type;
          $requestBusiness->first_name = $request->business_first_name;
          $requestBusiness->last_name = $request->business_last_name;
          $requestBusiness->email = $request->business_email;
          $requestBusiness->phone_number = $request->business_phone_number;
          $requestBusiness->relation_name = $request->business_hotel_name;
          $requestBusiness->case_number = $request->business_case_number;
  
          $requestBusiness->save();


        $patientRequest = new request_Client();
        $patientRequest->request_id = $requestBusiness->id;
        $patientRequest->first_name = $request->first_name;
        $patientRequest->last_name = $request->last_name;
        $patientRequest->notes= $request->symptoms; 
        $patientRequest->date_of_birth= $request->date_of_birth;
        $patientRequest->email = $request->email;
        $patientRequest->phone_number = $request->phone_number;
        $patientRequest->street = $request->street;
        $patientRequest->city = $request->city;
        $patientRequest->state = $request->state;
        $patientRequest->zipcode = $request->zipcode;
        $patientRequest->room = $request->room;
        // $patientRequest->docs = $request->file('docs')->store('public');

        // dd($patientRequest->all());


      
        $patientRequest->save();


return view('patientSite/submitScreen');

    }
}

