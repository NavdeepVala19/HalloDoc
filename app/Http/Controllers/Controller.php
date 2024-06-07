<?php

namespace App\Http\Controllers;

use App\Models\RequestTable;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // display home page of patientSite
    public function patientSite()
    {
        return view('patientSite/patientSite');
    }

    // display submit screen of patient site
    public function submitScreen()
    {
        return view('patientSite.submitScreen');
    }


    public function getRequestData(){
        $requestData = RequestTable::get();
    }
}
