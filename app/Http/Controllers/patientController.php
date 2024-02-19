<?php

namespace App\Http\Controllers;

use App\Models\request_Client;
use App\Models\RequestTable;
use App\Models\RequestWise;
use App\Models\RequestNotes;
use App\Models\RequestWiseFile;
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

        // $request->validate([
        //     'first_name'=>'required|min:2|max:30',
        //     'last_name'=>'string|min:2|max:30',
        //     'email' => 'required|email|min:2|max:30',
        //     'phone_number'=>'required|numeric|digits:10',
        //     'street'=>'min:2|max:30',
        //     'city' => 'alpha|min:2|max:30',
        //     'zipcode' => 'numeric', 
        //     'state' => 'alpha|min:2|max:30',
        //     'room' => 'numeric',
        // ]);
        
        
   

        // $myDate = ($request->date_of_birth);

        // $date = Carbon::createFromDate($myDate);
        
        // $monthName = $date->format('F');

        // dd($monthName);


        $requestData = new RequestTable();
            $requestData->request_type_id = $request->request_type;
            $requestData->first_name = $request->first_name;
            $requestData->last_name = $request->last_name;
            $requestData->email = $request->email;
            $requestData->phone_number = $request->phone_number;
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
        $patientRequest->save();





        // store documents in request_wise_file table

        $request_file = new RequestWiseFile();
        $request_file->request_id = $requestData->id;
        $request_file->file_name = $request->file('docs')->getClientOriginalName();
        $path = $request->file('docs')->storeAs('public', $request->file('docs')->getClientOriginalName());
        $request_file->save();




        // store symptoms in request_notes table

        $request_notes = new RequestNotes();
        $request_notes->request_id = $requestData->id;    
        $request_notes->patient_notes = $request->symptoms;
        
        $request_notes->save();
    


        return view('patientSite/submitScreen');

    }
}
