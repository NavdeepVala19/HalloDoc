<?php

namespace App\Http\Controllers;

use App\Models\request_Client;
use App\Models\RequestTable;
use Illuminate\Http\Request;
// use App\Models\User;
class familyRequestController extends Controller
{

  


    public function create(Request $request){

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
            'family_first_name'=>'required|min:2|max:30',
            'family-last-name'=>'min:2|max:30',
            'family_email'=>'required|email|min:2|max:30',
            'family_phone_number'=>'required',
            'family_relation'=>'required',
        ]);

    
        // family request creating

        $familyRequest = new RequestTable();
        $familyRequest->request_type_id= $request->request_type;
        $familyRequest->first_name = $request->family_first_name;
        $familyRequest->last_name = $request->family_last_name;
        $familyRequest->email = $request->family_email;
        $familyRequest->phone_number = $request->family_phone_number;
        $familyRequest->relation_name = $request->family_relation;

        $familyRequest->save();

        $patientRequest = new request_Client();
        $patientRequest->request_id = $familyRequest->id;
        $patientRequest->first_name = $request->first_name;
        $patientRequest->last_name = $request->last_name;
        $patientRequest->date_of_birth= $request->date_of_birth;
        $patientRequest->email = $request->email;
        $patientRequest->phone_number = $request->phone_number;
        $patientRequest->street = $request->street;
        $patientRequest->city = $request->city;
        $patientRequest->state = $request->state;
        $patientRequest->zipcode = $request->zipcode;
        

        $patientRequest->save();
    

        return view('patientSite/submitScreen');

    }
}

