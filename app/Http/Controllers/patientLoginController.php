<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\UserRoles;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class PatientLoginController extends Controller
{
    /**
     *display patient login screen
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function patientLoginScreen()
    {
        return view("patientSite/patientLogin");
    }

    /**
     * it verfies user(patient) credentials are valid or not
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function patientLogin(Request $request)
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
            $patientRole = UserRoles::where('user_id', $patientCredentials->id)->first();
            if ($patientRole == null) {
                return redirect()->route('patient.login.view')->with('error', 'submit request with registered email');
            } elseif ($patientRole->role_id === 3) {
                return redirect()->route('patient.dashboard');
            } else {
                return back()->with('error', 'Invalid credentials');
            }
        } else {
            $user = Users::where("email", $request->email)->first();
            if ($user == null) {
                return back()->with('error', 'We could not find an account associated with that email address');
            } else {
                return back()->with('error', 'Incorrect Password , Please Enter Correct Password');
            }
        }
    }


    /**
     *  display reset password form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function resetpassword()
    {
        return view("patientSite/patientResetPassword");
    }

    /**
     * it checks email in users table and send reset password form to that email
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
        ]);

        $user = Users::where('email', $request->email)->first();
        
        // check user and userRoles is exist or not
        if ($user) {
            $patientRole = UserRoles::where('user_id', $user->id)->first();
        }
        if ($user == null || $patientRole->role_id == 1 || $patientRole->role_id == 2) {
            return back()->with('error', 'no such email is registered');
        }

        $token = Str::random(64);
        $user->token = $token;
        $user->save();

        $userToken = Users::where('email', $request->email)->value('token');

        Mail::send('email.forgetPassword', ['token' => $userToken], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return redirect()->route('patient.login.view')->with('success', 'E-mail is sent for password reset.');
    }


    /**
     * it shows password update form
     * if password is already update then it shows password update success page
     * @param mixed $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showResetPasswordForm($token)
    {
        try {
            $tokenValue = Crypt::decrypt($token);
            $userData = users::where('token', $tokenValue)->first();

            if ($userData) {
                return view('patientSite/patientPasswordReset', ['token' => $tokenValue]);
            } else {
                return view('patientSite/passwordUpdatedSuccess');
            }
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }


    /**
     * update password of patient and delete token
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:8|max:30',
            'confirm_password' => 'required|same:new_password',
        ]);

        $updatePassword = Users::where('token', $request->token)->first();

        if (!$updatePassword) {
            return back()->with('error', 'invalid token!');
        }
        Users::where([
            'token' => $request->token
        ])->update(['password' => Hash::make($request->new_password)]);

        Users::where(['token' => $request->token])->update(['token' => ""]);

        return redirect()->route('patient.login.view')->with('success', 'Your password has been changed!');
    }


    /**
     * logout user(patient)
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('patient.login.view');
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

    //         $user = Users::where("email", $request->email)->first();

    //         if ($userRolesData->role_id == 1 || $userRolesData->role_id == 2 || $user == null) {

    //             $errors = new MessageBag(['email' => ['Invalid email. Please login with correct email']]);

    //             return redirect()->back()
    //             ->withErrors($errors)
    //             ->withInput($request->except('email'));
    //         } else if ($userRolesData->role_id == 3) {
    //             return redirect()->route('patient.dashboard');
    //         } else {
    //             return back()->with('error', 'Invalid credentials');
    //         }
    //     } else {
    //         $user = Users::where("email", $request->email)->first();

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
