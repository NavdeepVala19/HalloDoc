<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function patientSite()
    {
        return view('patientSite/patientSite');
        // return view('patientSite.patientSite');
    }

    public function submitScreen()
    {
        return view('patientSite.submitScreen');
    }

    public function passReset(){
        return view ('email.createNewRequest');
    }
}
