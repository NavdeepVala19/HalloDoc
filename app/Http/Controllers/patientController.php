<?php

namespace App\Http\Controllers;

use App\Models\request_Client;
use App\Models\RequestTable;
use Illuminate\Http\Request;
// use App\Models\User;


class patientController extends Controller
{

    // this controller is responsible for creating/storing the patient

    public function landing(){
        return view ('patientSite/submitScreen');
    }


    public function create(Request $request){

        dd($request->all());
        $request->validate([
            'first_name'=>'required',
            'email' => 'required|email',
            // 'password' => 'required|min:5|max:30',
            'phone_number'=>'required',
            'street'=>'required',
            'city' => 'required',
            'zipcode' => 'required', 
            'state' => 'required',
            'room' => 'required',
        ]);
        

        $requestData = new RequestTable();
        

        $patientRequest = new request_Client();
        $patientRequest->request_id = $requestData->id;
        $patientRequest->first_name = $request->first_name;
        $patientRequest->last_name = $request->last_name;
        $patientRequest->notes= $request->symptoms; 
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




        
        $patientRequest->save();


dd($patientRequest);
        return redirect('landing');

    }
}
