<?php

namespace App\Http\Controllers;

use App\Models\request_Client;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Str;


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

        // Assuming $inputPassword is the password the user submitted via a form
        $inputPassword = $request->password;
        // And assuming you've retrieved the user's hashed password from the database

        $user = users::where('email', $request->email)->first();
        $hashedPassword = $user->password_hash; // or $user->password, depending on your column name


        
        // Now, you can check if the input password matches the hash
        if (Hash::check($inputPassword, $hashedPassword)) {
            // The passwords match...
            // Log the user in or perform the next steps

            $timestamp = RequestTable::select('created_at')->get();
            $data = DB::table('request')
                ->join('status', 'request.status', '=', 'status.id')
                ->select('request.created_at', 'status.status_type')
                ->get();

            return view('patientSite/patientDashboard', compact('data'));
        } else {
            // The passwords don't match...
            // Handle the failed login attempt

            return redirect()->back()->withErrors(['email'=> 'enter appropriate login credentials']);
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


        users::where(['email' => $request->email])->delete();

        return redirect('/patient_login')->with('message', 'Your password has been changed!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect("loginScreen");
    }
}

