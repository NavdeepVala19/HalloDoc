<?php

namespace App\Http\Controllers;

use App\Models\request_Client;
use App\Models\RequestTable;
use App\Models\RequestWiseFile;
use App\Models\RequestNotes;

use Illuminate\Http\Request;

class patientDashboardController extends Controller
{
    public function patientDashboard()
    {
        return view("patientSite/patientDashboard");
    }


    public function createNewRequest()
    {
        return view("patientSite/patientNewRequest");
    }

    public function createSomeoneRequest()
    {
        return view("patientSite/patientSomeoneRequest");
    }


    public function createNewPatient(Request $request)
    {

        // dd($request->all()); 

        $request->validate([
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'string|min:2|max:30',
            'email' => 'required|email|min:2|max:30',
            'phone_number' => 'required',
            'street' => 'min:2|max:30',
            'city' => 'min:2|max:30',
            'zipcode' => 'numeric',
            'state' => 'min:2|max:30',
            'room' => 'numeric',
            'docs' => 'nullable'
        ]);

        $newPatient = new RequestTable();
        $newPatient->request_type_id = $request->request_type;
        $newPatient->first_name = $request->first_name;
        $newPatient->last_name = $request->last_name;
        $newPatient->email = $request->email;
        $newPatient->phone_number = $request->phone_number;
        $newPatient->relation_name = $request->relation;
        $newPatient->save();

        $newPatientRequest = new request_Client();
        $newPatientRequest->request_id = $newPatient->id;
        $newPatientRequest->first_name = $request->first_name;
        $newPatientRequest->last_name = $request->last_name;
        $newPatientRequest->date_of_birth = $request->date_of_birth;
        $newPatientRequest->email = $request->email;
        $newPatientRequest->phone_number = $request->phone_number;
        $newPatientRequest->street = $request->street;
        $newPatientRequest->city = $request->city;
        $newPatientRequest->state = $request->state;
        $newPatientRequest->zipcode = $request->zipcode;
        $newPatientRequest->room = $request->room;

        $newPatientRequest->save();


        // store documents in request_wise_file table

        $request_file = new RequestWiseFile();

        $request_file->request_id = $newPatient->id;
        $fileName = isset($request->docs)?$request->file('docs')->store('public'):'';
        $request_file->file_name = $fileName;
        $request_file->save();

        // this code is for getting original name of document

        // $filename = $request->file('docs')->getClientOriginalName(); 
        // dd($filename);



        // store symptoms in request_notes table

        $request_notes = new RequestNotes();
        $request_notes->request_id = $newPatient->id;
        $request_notes->patient_notes = $request->symptoms;

        $request_notes->save();

        return view("patientSite/patientDashboard");
    }

}
