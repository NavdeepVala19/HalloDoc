<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\PhysicianLocation;
use App\Models\UserRoles;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class AdminLoginController extends Controller
{

    /**
     * show Login page for admin and provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminLogin()
    {
        return view("admin/adminLogin");
    }


    /**
     * verify that user is admin or provider and if user entered credentials are valid it redirects to dashboard according to role
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\RedirectResponse
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
            if ($userRolesData->role_id == 1) {
                return redirect()->route('admin.dashboard');
            }
            if ($userRolesData->role_id == 2) {
                return redirect()->route('provider.dashboard');
            }
            if ($userRolesData->role_id == 3) {
                return back()->with('error', 'invalid credentials');
            }
        } else {
            $user = Users::where("email", $request->email)->first();

            if ($user == null) {
                return back()->with('error', 'We could not find an account associated with that email address , Please enter correct email');
            } else {
                return back()->with('error', 'Incorrect Password , Please Enter Correct Password');
            }
        }
    }

    /**
     * show password reset form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminResetPassword()
    {
        return view("admin/adminResetPassword");
    }


    /**
     *  send email to entered email if email not exist it shows error message
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = Users::where('email', $request->email)->first();

        if ($user) {
            $userRolesData = UserRoles::where('user_id', $user->id)->first();
        }

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
     * shows password update form and if password is already updated it shows password update success form
     * @param mixed $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showUpdatePasswordForm($token)
    {
        try {
            $tokenValue = Crypt::decrypt($token);
            $userData = users::where('token', $tokenValue)->first();
            if ($userData) {
                return view('admin/adminPasswordUpdate', ['token' => $tokenValue]);
            } else {
                return view('admin/adminPasswordUpdateSuccess');
            }
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }


    /**
     * password updated of users(admin/provider) and after successfully update password token gets deleted
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitUpdatePasswordForm(Request $request)
    {
        $request->validate([
            'confirm_password' => 'required|min:8|max:20',
            'new_password' => 'required|same:confirm_password|min:8|max:20',
        ]);

        $updatePassword = Users::where('token', $request->token)->first();

        if (!$updatePassword) {
            return back()->with('error', 'Invalid token!');
        }

        Users::where([
            'token' => $request->token,
        ])->update(['password' => Hash::make($request->new_password)]);

        Users::where(['token' => $request->token])->update(['token' => ""]);

        return redirect()->route('login')->with('message', 'Your password has been changed!');
    }


    /**
     * logout user(admin/provider)
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
 public function logout()
    {
        Auth::logout();
        return redirect()->route('login');

        // $userData = Auth::user();
        // $userRolesData = UserRoles::where('user_id', $userData->id)->first();

        // if ($userRolesData->role_id == 2) {
        //     $providersData = Provider::where('email', $userData->email)->first();
        //     PhysicianLocation::where('provider_id', $providersData->id)->forceDelete();
        // }

       
    }
}
