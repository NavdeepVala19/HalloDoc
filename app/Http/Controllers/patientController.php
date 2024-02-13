<?php

namespace App\Http\Controllers;

use App\Models\request_Client;
use App\Models\RequestTable;
use Cron\MonthField;
use Illuminate\Http\Request;
use Carbon\Carbon;
// use App\Models\User;


class patientController extends Controller
{

    // this controller is responsible for creating/storing the patient


    public function create(Request $request){

      

        // $request->validate([
        //     'first_name'=>['required','min:2','max:30'],
        //     'last_name'=>['string','min:2','max:30'],
        //     'email' => ['required','email','min:2','max:30'],
        //     'phone_number'=>['required','numeric',],
        //     'street'=>['min:2','max:30'],
        //     'city' => ['string','min:2','max:30'],
        //     'zipcode' => ['numeric'], 
        //     'state' => ['string','min:2','max:30'],
        //     'room' =>['numeric']
        // ]);

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
        ]);
        dd("Ss");
        dd($request->all());
        

        // $myDate = ($request->date_of_birth);

        // $date = Carbon::createFromDate($myDate);
        
        // $monthName = $date->format('F');

        // dd($monthName);


        $requestData = new RequestTable();
        $requestData->

        $requestData->save();
                
        $patientRequest = new request_Client();
        $patientRequest->request_id = $requestData->id;
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
        // $patientRequest->docs = $request->file('docs')->store('public');
        
    
        $patientRequest->save();

        return view('patientSite/submitScreen');

    }
}
