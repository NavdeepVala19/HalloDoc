<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientAccountController extends Controller
{
    /**
     * display patient register page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function patientRegister()
    {
        return view('patientSite/patientRegister');
    }

    /**
     *stores email and password in users table
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAccount(Request $request)
    {
        $request->validate([
            'email' => "required|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/",
            'password' => "required|min:2|max:30|regex:/^\S(.*\S)?$/",
            'confirm_password' => 'required|same:password',
        ]);
        $user = Users::where('email', $request->email)->first();
        if ($user !== null) {
            if ($user->password !== null && $user->email !== null) {
                return redirect()->route('patient.login.view')->with('message', 'account with this email already exist');
            }
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('patient.login.view')->with('message', 'login with your registered credentials');
        }
        return back()->with('message', 'no single request was created from this email To create account first submit request');
    }
}
