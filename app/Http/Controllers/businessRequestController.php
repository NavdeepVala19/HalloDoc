<?php

namespace App\Http\Controllers;

use App\Models\request_Client;
use App\Models\RequestTable;
use Illuminate\Http\Request;
// use App\Models\User;

class businessRequestController extends Controller
{
    // public function landing(){
    //     return view ('patientSite/submitScreen');
    // }


    public function create(Request $request){

        // dd($request->all());
        $request->validate([
            'first_name'=>'required',
            'email' => 'required|email',
            // 'password' => 'required|min:5|max:30',
            'phone_number'=>'required',
            'street'=>'required',
            'city' => 'required',
            'zipcode' => 'required', 
            'state' => 'required',
            // 'room' => 'required',
        ]);

        $requestData = new RequestTable();
        

        $patientRequest = new request_Client();
        $patientRequest->request_id = $requestData->id;
        $patientRequest->first_name = $request->first_name;
        $patientRequest->last_name = $request->last_name;
        // $patientRequest->notes= $request->symptoms; 
        // $patientRequest->date_of_birth= $request->date_of_birth;
        $patientRequest->email = $request->email;
        $patientRequest->phone_number = $request->phone_number;
        $patientRequest->street = $request->street;
        $patientRequest->city = $request->city;
        $patientRequest->state = $request->state;
        $patientRequest->zipcode = $request->zipcode;
        // $patientRequest->room = $request->room;
        // $patientRequest->docs = $request->file('docs')->store('public');

        // dd($patientRequest->all());


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
        $patientRequest->save();


return view('patientSite/submitScreen');

    }
}

