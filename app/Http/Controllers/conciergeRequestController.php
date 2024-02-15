<?php

namespace App\Http\Controllers;

use App\Models\Concierge;
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
        //     'city' => 'regex:/^[\pL\s\-]+$/u|min:2|max:30',
        //     'zipcode' => 'numeric', 
        //     'state' => 'regex:/^[\pL\s\-]+$/u|min:2|max:30',
        //     'room' => 'numeric',
        //     'concierge_first_name'=>'required|min:2|max:30',
        //     'concierge_last_name'=>'min:2|max:30',
        //     'concierge_email' =>'required|email|min:2|max:30',
        //     'concierge_mobile'=>'required',
        //     'concierge_hotel_name'=>'required|min:2|max:30',
        //     'concierge_street'=>'regex:/^[\pL\s\-]+$/u|min:2|max:30',
        //     'concierge_state'=>'regex:/^[\pL\s\-]+$/u|min:2|max:30',
        //     'concierge_city' =>'regex:/^[\pL\s\-]+$/u|min:2|max:30',
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
        // $patientRequest->notes= $request->symptoms; 
        $patientRequest->date_of_birth= $request->date_of_birth;
        $patientRequest->email = $request->email;
        $patientRequest->phone_number = $request->phone_number;
        $patientRequest->street = $request->street;
        $patientRequest->city = $request->city;
        $patientRequest->state = $request->state;
        $patientRequest->zipcode = $request->zipcode;
        $patientRequest->room = $request->room;
        // $patientRequest->docs = $request->file('docs')->store('public');



        $patientRequest->save();



        return view('patientSite/submitScreen');

    }
}

