<?php

namespace App\Http\Controllers;

use App\Models\UserRoles;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminLoginController extends Controller
{
    /**
     * show Login page for admin and provider
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminLogin()
    {
        return view('admin/adminLogin');
    }

    /**
     * verify that user is admin or provider and if user entered credentials are valid it redirects to dashboard according to role
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function userLogin(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials)) {
            return $this->handleUserRoleRedirect();
        }

        return $this->handleLoginFailure($request);
    }

    /**
     * show password reset form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminResetPassword()
    {
        return view('admin/adminResetPassword');
    }

    /**
     *  send email to entered email if email not exist it shows error message
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitForgetPasswordForm(Request $request)
    {
        $this->validateEmail($request);

        $user = $this->getUserByEmail($request->email);

        if ($this->isInvalidUser($user)) {
            return back()->with('error', 'no such email is registered');
        }

        $token = $this->generateTokenForUser($user);

        $this->sendPasswordResetEmail($request->email, $token);

        return redirect()->route('login')->with('message', 'E-mail is sent for password reset');
    }

    /**
     * shows password update form and if password is already updated it shows password update success form
     *
     * @param mixed $token
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showUpdatePasswordForm($token)
    {
        try {
            $tokenValue = Crypt::decrypt($token);
            $userData = $this->getUserByToken($tokenValue);
            if ($userData) {
                return view('admin/adminPasswordUpdate', ['token' => $tokenValue]);
            }
            return view('admin/adminPasswordUpdateSuccess');
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * update password of user(admin/provider) and after successfully update password token gets deleted
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitUpdatePasswordForm(Request $request)
    {
        $this->validatePasswordUpdate($request);

        $updatePassword = $this->getUserByToken($request->token);
        if (! $updatePassword) {
            return back()->with('error', 'Invalid token!');
        }

        $this->updateUserPassword($request->token, $request->new_password);

        return redirect()->route('login')->with('message', 'Your password has been changed!');
    }

    /**
     * logout user(admin/provider)
     *
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // Helper methods

    private function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'password' => 'required|min:8|max:30',
        ]);
    }

    private function getCredentials(Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password,
        ];
    }

    private function handleUserRoleRedirect()
    {
        $userRoleId = UserRoles::where('user_id', Auth::user()->id)->value('role_id');
        if ($userRoleId === 1) {
            return redirect()->route('admin.dashboard');
        }
        if ($userRoleId === 2) {
            return redirect()->route('provider.dashboard');
        }
        return back()->with('error', 'Invalid Credentials');
    }

    private function handleLoginFailure(Request $request)
    {
        $isUserExist = Users::where('email', $request->email)->first();
        if ($isUserExist === null) {
            return back()->with('error', 'We could not find an account associated with that email address.');
        }
        return back()->with('error', 'Incorrect Password, Please Enter Correct Password.');
    }

    private function validateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
        ]);
    }

    private function getUserByEmail($email)
    {
        return Users::where('email', $email)->first();
    }

    private function isInvalidUser($user)
    {
        return $user === null || UserRoles::where('user_id', $user->id)->value('role_id') === 3;
    }

    private function generateTokenForUser($user)
    {
        $token = Str::random(64);
        $user->token = $token;
        $user->save();
        return $token;
    }

    private function sendPasswordResetEmail($email, $token)
    {
        Mail::send('email.adminforgetPassword', ['token' => $token], function ($message) use ($email) {
            $message->to($email);
            $message->subject('Reset Password');
        });
    }

    private function getUserByToken($token)
    {
        return Users::where('token', $token)->first();
    }

    private function validatePasswordUpdate(Request $request)
    {
        $request->validate([
            'confirm_password' => 'required|min:8|max:20',
            'new_password' => 'required|same:confirm_password|min:8|max:20',
        ]);
    }

    private function updateUserPassword($token, $newPassword)
    {
        Users::where(['token' => $token])->update(['password' => Hash::make($newPassword), 'token' => '']);
    }
}
