<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class patientDashboardController extends Controller
{
    public function patientDashboard(){
        return view("patientSite/patientDashboard");
   }

   public function patientViewDocument(){
    return view("patientSite/patientViewDocument");
   }
}
