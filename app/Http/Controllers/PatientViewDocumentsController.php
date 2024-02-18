<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestTable;
use App\Models\RequestWiseFile;

class PatientViewDocumentsController extends Controller
{

    public function patientViewDocument()
    {
        return view("patientSite/patientViewDocument");
    }
     
    public function uploadDocs(Request $request){

        $familyRequest = new RequestTable();
        $familyRequest->request_type_id= $request->request_type;
        $familyRequest->save();

         // store documents in request_wise_file table

         $request_file = new RequestWiseFile();
         $request_file->request_id = $familyRequest->id;
         $request_file->file_name = $request->file('docs')->store('public');
         $request_file->save();
 
         // this code is for getting original name of document
 
         // $filename = $request->file('docs')->getClientOriginalName(); 
         // dd($filename);
 
    }

    public function docsRead(){

        $users = RequestTable::all();
        return view('patientSite/patientDashboard')->with('users', $users);
     
    }


    public function download($filename)
    {
        $filePath = storage_path('app/public/' . $filename);
        return response()->download($filePath);
    }
}

