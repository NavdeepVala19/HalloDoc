<?php

namespace App\Http\Controllers;

use App\Models\Concierge;
use App\Models\users;
use App\Models\allusers;
use App\Models\RequestNotes;
use App\Models\request_Client;
use App\Models\RequestTable;
use Illuminate\Http\Request;

use PhpParser\Node\Stmt\TryCatch;

// use App\Models\User;

class conciergeRequestController extends Controller
{
  

    public function create(Request $request){

        
        // $request->validate([
        //     'first_name'=>'required|min:2|max:30',
        //     'last_name'=>'string|min:2|max:30',
        //     'email' => 'required|email|min:2|max:30',
        //     'phone_number'=>'required|numeric|digits:10',
        //     'street'=>'min:2|max:30',
        //     'city' => 'min:2|max:30',
        //     'zipcode' => 'numeric', 
        //     'state' => 'min:2|max:30',
        //     'room' => 'numeric',
        //     'concierge_first_name'=>'required|min:2|max:30',
        //     'concierge_last_name'=>'min:2|max:30',
        //     'concierge_email' =>'required|email|min:2|max:30',
        //     'concierge_mobile'=>'required',
        //     'concierge_hotel_name'=>'required|min:2|max:30',
        //     'concierge_street'=>'min:2|max:30',
        //     'concierge_state'=>'min:2|max:30',
        //     'concierge_city' =>'min:2|max:30',
        //     'concierge_zip_code' =>'numeric',
        // ]);

        // concierge request into request table

        $concierge = new Concierge();
        $concierge->name = $request->concierge_first_name;
        $concierge->address = $request->concierge_hotel_name;
        $concierge->street = $request->concierge_street;
        $concierge->city = $request->concierge_city;
        $concierge->state = $request->concierge_state;
        $concierge->zipcode = $request->concierge_zip_code;
        
        $concierge->save();
        
        // concierge request into request table

        $requestConcierge = new RequestTable();
        $requestConcierge->status = 1;   
        $requestConcierge->request_type_id= $request->request_type;
        $requestConcierge->first_name = $request->concierge_first_name;
        $requestConcierge->last_name = $request->concierge_last_name;
        $requestConcierge->email = $request->concierge_email;
        $requestConcierge->phone_number = $request->concierge_mobile;
        $requestConcierge->relation_name = $request->concierge_hotel_name;

        $requestConcierge->save();

        
        $patientRequest = new request_Client();
        $patientRequest->request_id = $requestConcierge->id;
        $patientRequest->first_name = $request->first_name;
        $patientRequest->last_name = $request->last_name;
        $patientRequest->date_of_birth= $request->date_of_birth;
        $patientRequest->email = $request->email;
        $patientRequest->phone_number = $request->phone_number;
        $patientRequest->street = $request->street;
        $patientRequest->city = $request->city;
        $patientRequest->state = $request->state;
        $patientRequest->zipcode = $request->zipcode;
        $patientRequest->room = $request->room;
        $patientRequest->save();


        // store symptoms in request_notes table

        $request_notes = new RequestNotes();
        $request_notes->request_id = $request_notes->id;
        $request_notes->patient_notes = $request->symptoms;

        $request_notes->save();



        // store email and phoneNumber in users table
        $requestEmail = new users();
        $requestEmail->email = $request->email;
        $requestEmail->phone_number = $request->phone_number;

        $requestEmail->save();


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


        return view('patientSite/submitScreen');

    }
}

