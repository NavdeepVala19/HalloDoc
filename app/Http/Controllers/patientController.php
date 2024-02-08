<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class patientController extends Controller
{
    public function create(){
        return view('patientSite/patientRequest');
    }

    public function store(Request $request){

        $request->validate([
            'firstname' => ['required', 'min:2', 'max:30'],
            'lastname' => ['required', 'min:2', 'max:30'],
            'email' => ['required', 'email', 'min:10', 'max:30'],
            'password' => ['required', 'min:5', 'max:30'],
            'image' => ['required'],
        ]);
        
    }
}
