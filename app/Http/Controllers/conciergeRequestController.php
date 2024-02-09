<?php

namespace App\Http\Controllers;

use App\Models\request_Client;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

// use App\Models\User;

class conciergeRequestController extends Controller
{
    // public function landing(){
    //     return view ('patientSite/submitScreen');
    // }


    public function create(Request $request){

        
        // $request->validate([
        //     'first_name'=>'required',
        //     'email' => 'required|email',
        //     // 'password' => 'required|min:5|max:30',
        //     'phone_number'=>'required',
        //     'street'=>'required',
        //     'city' => 'required',
        //     'zipcode' => 'required', 
        //     'state' => 'required',
        //     // 'room' => 'required',
        // ]);



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


        // concierge request creating

        $requestConcierge = new RequestTable();

        $requestConcierge->request_type_id= $request->request_type;
        $requestConcierge->first_name = $request->concierge_first_name;
        $requestConcierge->last_name = $request->concierge_last_name;
        $requestConcierge->email = $request->concierge_email;
        $requestConcierge->phone_number = $request->concierge_mobile;
        $requestConcierge->relation_name = $request->concierge_hotel_name;


        $requestConcierge->save();
        $patientRequest->save();



        return view('patientSite/submitScreen');

    }
}

