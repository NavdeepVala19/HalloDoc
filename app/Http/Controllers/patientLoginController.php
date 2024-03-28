<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\users;
use App\Models\UserRoles;
use Illuminate\Support\Str;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\request_Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;


class patientLoginController extends Controller
{

    public function loginScreen()
    {
        return view("patientSite/patientLogin");
    }

    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $patientCredentials = Auth::user();

            dd(Auth::user());

            // isCredentialStored = users::where('')

            $userRolesData = UserRoles::where('user_id', $patientCredentials->id)->first();
            dd("here");

            if ($userRolesData->role_id == 3) {
                return redirect()->route('patientDashboardData');
            } else {
                return back()->with('error', 'Invalid credentials');
            }
        }
    }


    public function resetpassword()
    {
        return view("patientSite/patientResetPassword");
    }


    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = users::where('email', $request->email)->first();

        if ($user == null) {
            return back()->with('error', 'no such email is registered');
        }

        $token = Str::random(64);
        $user->token = $token;
        $user->save();

        Mail::send('email.forgetPassword', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return redirect()->route('loginScreen')->with('success', 'We have e-mailed your password reset link!');
    }



    public function showResetPasswordForm($token)
    {
        return view('patientSite/patientPasswordReset', ['token' => $token]);
    }


    public function submitResetPasswordForm(Request $request)
    {

        $request->validate([
            'new_password' => 'required|min:8|max:20',
            'confirm_password' => 'required|same:new_password',
        ]);

        $updatePassword = users::where('token', $request->token)->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        users::where([
            'token' => $request->token
        ])->update(['password' => Hash::make($request->new_password)]);


        users::where(['email' => $request->email])->update(['token' => null]);


        return redirect('/patient_login')->with('success', 'Your password has been changed!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect("loginScreen");
    }
}
