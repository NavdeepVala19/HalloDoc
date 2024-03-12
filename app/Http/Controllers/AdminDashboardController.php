<?php

namespace App\Http\Controllers;

use App\Models\users;
use App\Models\allusers;
use App\Models\RequestNotes;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\RequestStatus;
use App\Models\request_Client;
use App\Models\RequestWiseFile;
use App\Services\TwilioService;
use App\Exports\PendingStatusExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminDashboardController extends Controller
{
    public function createNewRequest()
    {
        return view('adminPage/adminRequest');
    }

    public function createAdminPatientRequest(Request $request)
    {

        try {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'phone_number' => 'required',
                'email' => 'required|email',
                // 'dob' => 'required',
                'street' => 'required',
                'city' => 'required',
                'state' => 'required'
            ]);
        } catch (\Throwable $th) {
            dd($th);
        }

        // store email and phoneNumber in users table
        $requestEmail = new users();
        $requestEmail->email = $request->email;
        $requestEmail->phone_number = $request->phone_number;
        // $requestEmail->save();

        $requestData = new RequestTable();
        $requestStatus = new RequestStatus();

        $requestData->status = $requestStatus->id;
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


        $adminPatientRequest = new request_Client();
        $adminPatientRequest->request_id = $requestData->id;
        $adminPatientRequest->first_name = $request->first_name;
        $adminPatientRequest->last_name = $request->last_name;
        $adminPatientRequest->date_of_birth = $request->date_of_birth;
        $adminPatientRequest->email = $request->email;
        $adminPatientRequest->phone_number = $request->phone_number;
        $adminPatientRequest->street = $request->street;
        $adminPatientRequest->city = $request->city;
        $adminPatientRequest->state = $request->state;
        $adminPatientRequest->zipcode = $request->zipcode;
        $adminPatientRequest->room = $request->room;

        $adminPatientRequest->save();




        // store notes in request_notes table

        $request_notes = new RequestNotes();
        $request_notes->request_id = $requestData->id;
        $request_notes->admin_notes = $request->adminNote;
        $request_notes->created_by = 'admin';

        $request_notes->save();


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


        return redirect()->route('admin.dashboard');
    }
}
