<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // *display home page of patientSite
    public function patientSite()
    {
        return view('patientSite/patientSite');
    }


    // * display submit screen of patient site
    public function submitScreen()
    {
        return view('patientSite.submitScreen');
    }


}