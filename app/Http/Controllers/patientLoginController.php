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

    /**
     *display patient login screen
     */

    public function loginScreen()
    {
        return view("patientSite/patientLogin");
    }

    /**
     *@param $request the input which is enter by user

     * it verfies user(patient) credentials are valid or not
     */

    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'password' => "required|min:8|max:30|regex:/^\S(.*\S)?$/",
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $patientCredentials = Auth::user();
            $userRolesData = UserRoles::where('user_id', $patientCredentials->id)->first();
            if ($userRolesData == null) {
                return redirect()->route('loginScreen')->with('error', 'submit request with registered email');
            } else if ($userRolesData->role_id == 3) {
                return redirect()->route('patientDashboardData');
            } else {
                return back()->with('error', 'Invalid credentials');
            }
        } else {
            return back()->with('error', 'Invalid credentials');
        }
    }


    /**
     * it will show reset password form
     */

    public function resetpassword()
    {
        return view("patientSite/patientResetPassword");
    }


    /**
     *@param $request the input which is enter by user

     * it checks email in users table and send reset password form to that email
     */

    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
        ]);

        $user = users::where('email', $request->email)->first();
        $userRolesData = UserRoles::where('user_id', $user->id)->first();

        if ($user == null || $userRolesData->role_id == 1 || $userRolesData->role_id == 2) {
            return back()->with('error', 'no such email is registered');
        }

        $token = Str::random(64);
        $user->token = $token;
        $user->save();


        $userToken = users::where('email', $request->email)->first()->token;

        Mail::send('email.forgetPassword', ['token' => $userToken], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return redirect()->route('loginScreen')->with('success', 'E-mail is sent for password reset.');
    }



    // * patient update password

    /**
     *@param $token which was generated when user enter email in password reset form and stores in users table where user enter email 

     * it shows password update form
     * if password is already update then it shows password update success page
     */
    public function showResetPasswordForm($token)
    {
        $userData = users::where('token', $token)->first();
        if ($userData) {
            return view('patientSite/patientPasswordReset', ['token' => $token]);
        } else {
            return view('patientSite/passwordUpdatedSuccess');
        }
    }


    /**
     *@param $request the password which is enter by user and 

     * it update password of patient and delete token 
     */
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:8|max:30',
            'confirm_password' => 'required|same:new_password',
        ]);

        $updatePassword = users::where('token', $request->token)->first();

        if (!$updatePassword) {
            return back()->with('error', 'invalid token!');
        }
        users::where([
            'token' => $request->token
        ])->update(['password' => Hash::make($request->new_password)]);

        users::where(['token' => $request->token])->update(['token' => ""]);

        return redirect('/patient_login')->with('success', 'Your password has been changed!');
    }


    /**
     * it logout user(patient) 
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('loginScreen');
    }


    // Learning Purpose 

    // public function patientLogin(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
    //         'password' => "required|min:8|max:30|regex:/^\S(.*\S)?$/",
    //     ]);

    //     $credentials = [
    //         'email' => $request->email,
    //         'password' => $request->password,
    //     ];
    //     $errors = new MessageBag; // initiate MessageBag

    //     if (Auth::attempt($credentials)) {

    //         $patientCredentials = Auth::user();

    //         $userRolesData = UserRoles::where('user_id', $patientCredentials->id)->first();

    //         $user = users::where("email", $request->email)->first();

    //         if ($userRolesData->role_id == 1 || $userRolesData->role_id == 2 || $user == null) {

    //             $errors = new MessageBag(['email' => ['Invalid email. Please login with correct email']]);

    //             return redirect()->back()
    //             ->withErrors($errors)
    //             ->withInput($request->except('email'));
    //         } else if ($userRolesData->role_id == 3) {
    //             return redirect()->route('patientDashboardData');
    //         } else {
    //             return back()->with('error', 'Invalid credentials');
    //         }
    //     } else {
    //         $user = users::where("email", $request->email)->first();

    //         if ($user == null) {
    //             $errors = new MessageBag(['email' => ['We could not find an account associated with that email address , Please enter correct email ']]);

    //             return redirect()->back()
    //             ->withErrors($errors)
    //             ->withInput($request->except('email'));
    //         } else {
    //             $errors = new MessageBag(['password' => ['Incorrect Password , Please Enter Correct Password']]);

    //             return redirect()->back()
    //             ->withErrors($errors)
    //             ->withInput($request->except('password'));
    //         }
    //     }
    // }



}
