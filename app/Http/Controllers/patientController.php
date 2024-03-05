<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Cron\MonthField;
use App\Models\users;
use App\Models\Status;
use App\Models\allusers;
use App\Models\RequestWise;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use Illuminate\Http\Request;

use App\Models\RequestStatus;
use App\Models\request_Client;
use App\Models\RequestWiseFile;
use Illuminate\Support\Facades\DB;

// use App\Models\User;


class patientController extends Controller
{

    // this controller is responsible for creating/storing the patient


    public function create(Request $request)
    {
        

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


        // store email and phoneNumber in users table
        $requestEmail = new users();
        $requestEmail->email = $request->email;
        $requestEmail->phone_number = $request->phone_number;
        $requestEmail->save();



        $requestData = new RequestTable();
        $requestStatus = new RequestStatus();

        // $requestData->status = $requestStatus->id;
        $requestData->user_id = $requestEmail->id;
        $requestData->request_type_id = $request->request_type;
        $requestData->first_name = $request->first_name;
        $requestData->last_name = $request->last_name;
        $requestData->email = $request->email;
        $requestData->phone_number = $request->phone_number;
        $requestData->save();

        $requestStatus->request_id = $requestData->id;
        $requestStatus->status = 1;
        $requestStatus->save();

        
        if (!empty($requestStatus)) {
            $requestData->update(['status' => $requestStatus->id]);
        }


        $patientRequest = new request_Client();
        $patientRequest->request_id = $requestData->id;
        $patientRequest->first_name = $request->first_name;
        $patientRequest->last_name = $request->last_name;
        $patientRequest->date_of_birth = $request->date_of_birth;
        $patientRequest->email = $request->email;
        $patientRequest->phone_number = $request->phone_number;
        $patientRequest->street = $request->street;
        $patientRequest->city = $request->city;
        $patientRequest->state = $request->state;
        $patientRequest->zipcode = $request->zipcode;
        $patientRequest->room = $request->room;

        $patientRequest->notes = $request->symptoms;
        $patientRequest->save();





        // store documents in request_wise_file table

        if (isset($request->docs)) {
            $request_file = new RequestWiseFile();
            $request_file->request_id = $requestData->id;
            $request_file->file_name = $request->file('docs')->getClientOriginalName();
            $path = $request->file('docs')->storeAs('public', $request->file('docs')->getClientOriginalName());
            $request_file->save();
        }



        // store symptoms in request_notes table

        // $request_notes = new RequestNotes();
        // $request_notes->request_id = $requestData->id;
        // $request_notes->patient_notes = $request->symptoms;

        // $request_notes->save();




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




        $currentTime = Carbon::now();
        $currentDate = $currentTime->format('Y');

        $todayDate = $currentTime->format('Y-m-d');
        $entriesCount = RequestTable::whereDate('created_at', $todayDate)->count();


        $confirmationNumber = substr($request->state, 0, 2) . $currentDate . substr($request->last_name, 0, 2) . substr($request->first_name, 0, 2) . '00' . $entriesCount;
        // dd($confirmationNumber);

        if (!empty($requestData->id)) {
            $requestData->update(['confirmation_no' => $confirmationNumber]);
        }


        return redirect()->route('submitRequest');
    }
}
