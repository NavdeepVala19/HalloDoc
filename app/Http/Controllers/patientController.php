<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;


class patientController extends Controller
{

    // this controller is responsible for creating/storing the patient

    public function landing(){
        return view ('patientSite/submitScreen');
    }
    public function create(){
        return view('patientSite/patientRequest');
    }

    public function store(Request $request){

        $request->validate([
            'first_name' => ['required', 'min:2', 'max:30'],,
            'email' => ['required', 'email', 'min:10', 'max:30'],
            'password' => ['required', 'min:5', 'max:30'],
            'phone_number'=>['required','min:10', 'max:10'],
            'image' => ['required'],
        ]);

        $patientRequest = new User;
        $patientRequest->first_name = $request->input('first_name');
        $patientRequest->last_name = $request->input('last_name');
        $patientRequest->symptoms= $request->input('symptoms');
        $patientRequest->date_of_birth = $request->input('date_of_birth');
        $patientRequest->email = $request->input('email');
        $patientRequest->phone_number = $request->input('phone_number');
        $patientRequest->street = $request->input('street');
        $patientRequest->city = $request->input('city');
        $patientRequest->state = $request->input('state');
        $patientRequest->zip_code = $request->input('zip_code');
        $patientRequest->room = $request->input('room');
        $patientRequest->docs = $request->file('docs')->store('public');

        $patientRequest->save();

        return redirect('landing');

    }
}
