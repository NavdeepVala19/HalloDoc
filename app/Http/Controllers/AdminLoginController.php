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

class AdminLoginController extends Controller
{
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

        $user = users::where('email', $request->email)->first();
        if ($user == null) {
            return back()->with('error', 'no such email is registered');
        }

        $token = Str::random(64);

        $user->token = $token;
        $user->save();

        Mail::send('email.adminforgetPassword', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return redirect()->route('adminLogin')->with('message', 'We have e-mailed your password reset link!');
    }

    // this code is to update/reset password

    public function showUpdatePasswordForm($token)
    {
        return view('admin/adminPasswordUpdate', ['token' => $token]);
    }

    public function submitUpdatePasswordForm(Request $request)
    {

        $request->validate([
            'confirm_password' => 'required|min:8|max:20',
            'new_password' => 'required|same:confirm_password|min:8|max:20',

        ]);

        $updatePassword = users::where('token', $request->token)->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        users::where([
            'token' => $request->token,
        ])->update(['password' => Hash::make($request->new_password)]);

        users::where(['token' => $request->token])->update(['token' => ""]);

        return redirect('/adminLogin')->with('message', 'Your password has been changed!');
    }

    public function logout()
    {
        $userData = Auth::user();
        $userRolesData = UserRoles::where('user_id', $userData->id)->first();

        if ($userRolesData->role_id == 2) {
            $providersData = Provider::where('email', $userData->email)->first();
            PhysicianLocation::where('provider_id',$providersData->id)->forceDelete();
        }

        Auth::logout();
        return redirect()->route('adminLogin');
    }
}
