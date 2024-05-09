<?php

namespace App\Http\Controllers;

use App\Models\users;
use App\Models\Provider;
use App\Models\UserRoles;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PhysicianLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class AdminLoginController extends Controller
{

    /**
     * show adminLogin page
     */

    public function adminLogin()
    {
        return view("admin/adminLogin");
    }


    /**
     *@param $request user enter credentials

     * verify that user is admin or provider and if user entered credentials are valid it redirects to dashboard according to role
     */
    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|min:2|max:40|',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $userData = Auth::user();
            $userRolesData = UserRoles::where('user_id', $userData->id)->first();

            if ($userRolesData->role_id == 2) {
                $providersData = Provider::where('email', $userData->email)->first();
                PhysicianLocation::create([
                    'provider_id' => $providersData->id,
                    'physician_name' => $providersData->first_name,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            }

            if ($userRolesData->role_id == 2) {
                return redirect()->route('provider.dashboard');
            } else if ($userRolesData->role_id == 1) {
                return redirect()->route('admin.dashboard');
            } else if ($userRolesData->role_id == 3) {
                return back()->with('error', 'invalid credentials');
            }
        } else {
            return back()->with('error', 'invalid credentials');
        }
    }

 
    /**
     * show password reset form
     */
    public function adminResetPassword()
    {
        return view("admin/adminResetPassword");
    }


    /**
     *@param $request user input email

     * send email to entered email if email not exist it shows error message 
     */

    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = users::where('email', $request->email)->first();

        $userRolesData = UserRoles::where('user_id', $user->id)->first();

        if ($user == null || $userRolesData->role_id == 3) {
            return back()->with('error', 'no such email is registered');
        }

        $token = Str::random(64);

        $user->token = $token;
        $user->save();

        Mail::send('email.adminforgetPassword', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return redirect()->route('login')->with('message', 'E-mail is sent for password reset');
    }


    /**
     *@param $token which was generate when email is sent and store in users table at enter entered email

     * shows password update form and if password is already updated it shows password update success form
     */

    public function showUpdatePasswordForm($token)
    {        
        try{
            $tokenValue = Crypt::decrypt($token);
            $userData = users::where('token', $tokenValue)->first();
            if ($userData) {
                return view('admin/adminPasswordUpdate', ['token' => $tokenValue]);
            } else {
                return view('admin/adminPasswordUpdateSuccess');
            }
        }catch (\Throwable $th) {
            return view('errors.404');
        }


    }


    /**
     *@param $request   $request->token which was generated when email was sent 

     * password updated of users(admin/provider) and after successfully update password token gets deleted 
     */


    public function submitUpdatePasswordForm(Request $request)
    {

        $request->validate([
            'confirm_password' => 'required|min:8|max:20',
            'new_password' => 'required|same:confirm_password|min:8|max:20',

        ]);

        $updatePassword = users::where('token', $request->token)->first();

        if (!$updatePassword) {
            return back()->with('error', 'Invalid token!');
        }

        users::where([
            'token' => $request->token,
        ])->update(['password' => Hash::make($request->new_password)]);

        users::where(['token' => $request->token])->update(['token' => ""]);

        return redirect()->route('login')->with('message', 'Your password has been changed!');
    }

    public function logout()
    {
        $userData = Auth::user();
        $userRolesData = UserRoles::where('user_id', $userData->id)->first();

        if ($userRolesData->role_id == 2) {
            $providersData = Provider::where('email', $userData->email)->first();
            PhysicianLocation::where('provider_id', $providersData->id)->forceDelete();
        }

        Auth::logout();
        return redirect()->route('login');
    }
}