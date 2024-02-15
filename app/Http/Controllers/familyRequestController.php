<?php

namespace App\Http\Controllers;

use App\Models\request_Client;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\RequestNotes;
use App\Models\RequestWise;
// use App\Models\User;
class familyRequestController extends Controller
{

  


    public function create(Request $request){

        $request->validate([
            'first_name'=>'required|min:2|max:30',
            'last_name'=>'string|min:2|max:30',
            'email' => 'required|email|min:2|max:30',
            'phone_number'=>'required|numeric|digits:10',
            'street'=>'min:2|max:30',
            'city' => 'regex:/^[\pL\s\-]+$/u|min:2|max:30',
            'zipcode' => 'numeric', 
            'state' => 'regex:/^[\pL\s\-]+$/u|min:2|max:30',
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
    

        // store documents in request_wise_file table

        $request_file = new RequestWise();
        $request_file->request_id = $familyRequest->id;
        $request_file->file_name = $request->file('docs')->store('public');
        $request_file->save();

        // this code is for getting original name of document

        // $filename = $request->file('docs')->getClientOriginalName(); 
        // dd($filename);



        // store symptoms in request_notes table

        $request_notes = new RequestNotes();
        $request_notes->request_id = $familyRequest->id;    
        $request_notes->patient_notes = $request->symptoms;
        
        $request_notes->save();

        return view('patientSite/submitScreen');

    }
}

