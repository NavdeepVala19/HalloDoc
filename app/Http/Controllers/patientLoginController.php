<?php

namespace App\Http\Controllers;

use App\Models\request_Client;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\users;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;


class patientLoginController extends Controller
{

    public function loginScreen()
    {
        return view("patientSite/patientLogin");
    }

    public function userLogin(Request $request)
    {

        // $encryptedEmail = Crypt::encryptString($request->email);

        // Session::put('email', $encryptedEmail);


        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];



        if (Auth::attempt($credentials)) {
            $userData = Auth::user();
            return redirect()->route('patientDashboardData');
        }
    }


    public function resetpassword()
    {
        return view("patientSite/patientResetPassword");
    }


    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        $user = users::where('email', $request->email)->first();
        $user->token = $token;
        $user->save();

        Mail::send('email.forgetPassword', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return back()->with('message', 'We have e-mailed your password reset link!');
    }



    public function showResetPasswordForm($token)
    {
        return view('patientSite/patientPasswordReset', ['token' => $token]);
    }


    public function submitResetPasswordForm(Request $request)
    {

        $request->validate([
            'confirm_password' => 'required',
            'new_password' => 'required|same:confirm_password',
        ]);

        $updatePassword = users::where('token', $request->token)->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        users::where([
            'token' => $request->token
        ])->update(['password_hash' => Hash::make($request->new_password)]);


        // users::where(['email' => $request->email])->delete();

        users::where(['email' => $request->email])->update(['token' => null]);


        return redirect('/patient_login')->with('message', 'Your password has been changed!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect("loginScreen");
    }
}

