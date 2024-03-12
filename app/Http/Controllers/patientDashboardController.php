<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\view;
use App\Models\request_Client;
use App\Models\RequestTable;
use App\Models\RequestWiseFile;
use App\Models\RequestNotes;
use App\Models\Status;
use App\Models\users;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;


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

    public function viewAgreement()
    {
        return view("patientSite/patientAgreement");
    }

    public function createNewPatient(Request $request)
    {

     
        $request->validate([
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'min:2|max:30',
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
        $fileName = isset($request->docs) ? $request->file('docs')->store('public') : '';
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



    public function read()
    {

        $timestamp = RequestTable::select('created_at')->get();
        // return view('patientSite/patientDashboard')->with('date', $timestamp);


        // $newDate = $timestamp->implode('', $timestamp);

        // $carbonDate = Carbon::parse($newDate);

        // $dateOnly = $carbonDate->toDateString();


        // $date = RequestTable::select('created_at')->get();

        // dd($date);

        // $items = array();
        // foreach ($timestamp as $key) {
        //     $items[] = $key;
        // }
        // print_r($items);


        // $jsonString = '{"created_at":"2024-02-16T05:23:20.000000Z"}';

        // Decode the JSON string to an object
        // $jsonObject = json_decode($jsonString);

        // Extract the 'created_at' value
        // $createdAtString = $jsonObject->created_at;

        // Now, parse the 'created_at' string with Carbon
        // $createdAt = Carbon::parse($createdAtString);

        // If you need it in a specific format or just the date part
        // Outputs: 2024-02-16

        // $dateString = $createdAt->toDateString()

        $currentTime = Carbon::now();
        $currentDate = $currentTime->format('Y-m-d');


        $userData = Auth::user();
        // $email = $userData["email"];


        $data = DB::table('request')
            ->join('status', 'request.status', '=', 'status.id')
            ->select('request.created_at', 'status.status_type')
            ->paginate(10);

        return view('patientSite/patientDashboard', compact('data'));
    }
}
