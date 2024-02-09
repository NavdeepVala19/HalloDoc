<?php

namespace App\Http\Controllers;

use App\Models\request_Client;
use App\Models\RequestTable;
use Illuminate\Http\Request;
// use App\Models\User;
class familyRequestController extends Controller
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


        // family request creating
        $familyRequest = new RequestTable();
        $familyRequest->request_type_id= $request->request_type;
        $familyRequest->first_name = $request->family_first_name;
        $familyRequest->last_name = $request->family_last_name;
        $familyRequest->email = $request->family_email;
        $familyRequest->phone_number = $request->family_phone_number;
        $familyRequest->relation_name = $request->family_relation;

        // dd($familyRequest);

        $familyRequest->save();
        $patientRequest->save();


        return view('patientSite/submitScreen');

    }
}

