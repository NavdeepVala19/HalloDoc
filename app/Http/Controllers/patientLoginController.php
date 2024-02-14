<?php

namespace App\Http\Controllers;

use App\Models\request_Client;
use App\Models\RequestTable;
use Illuminate\Http\Request;

class patientLoginController extends Controller
{
    public function loginScreen(){
        return view("patientSite/patientDashboard");
    }

    public function read(){
        $patientData = RequestTable::all();
        $patientData = RequestTable::paginate(10);
        return view ("patientSite/patientDashboard")->with('patientData', $patientData);

    }
}


