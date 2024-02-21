<?php

namespace App\Http\Controllers;

use App\Mail\sendResetPasswordMail;
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


class AdminLoginController extends Controller
{


    // **************
// this code is for login input credentials

    public function adminLogin()
    {
        return view("admin/adminLogin");
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

            return redirect('/adminResetPassword');
        } else {
            // The passwords don't match...
            // Handle the failed login attempt

            return redirect('/adminLogin')->with('message', 'Your password is not appropriate!');

        }

    }

    // ***********





    // *********
    // this code is for entering email for reset password

    public function adminResetPassword()
    {
        return view("admin/adminResetPassword");
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



        Mail::send('email.adminforgetPassword', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return back()->with('message', 'We have e-mailed your password reset link!');
    }

    // **********



    // ************
// this code is to update/reset password

    public function showUpdatePasswordForm($token)
    {
        return view('admin/adminPasswordUpdate', ['token' => $token]);
    }


    public function submitUpdatePasswordForm(Request $request)
    {
        try {
            // dd($request);
            $request->validate([
                'confirm_password' => 'required',
                'new_password' => 'required|same:confirm_password',
                
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }

        $updatePassword = users::where('token', $request->token)->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        users::where([
            'token' => $request->token
        ])->update(['password_hash' => Hash::make($request->new_password)]);


        users::where(['email' => $request->email])->delete();

        return redirect('/adminLogin')->with('message', 'Your password has been changed!');
    }


}
