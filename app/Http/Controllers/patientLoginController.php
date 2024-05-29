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

class PatientLoginController extends Controller
{
    /**
     * Display patient login screen
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function patientLoginScreen()
    {
        return view('patientSite/patientLogin');
    }

    /**
     * Verify user(patient) credentials are valid or not
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function patientLogin(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials)) {
            return $this->handlePatientLogin();
        }

        return $this->handleLoginFailure($request);
    }

    /**
     * Display reset password form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function resetPassword()
    {
        return view('patientSite/patientResetPassword');
    }

    /**
     * Check email in users table and send reset password form to that email
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitForgetPasswordForm(Request $request)
    {
        $this->validateEmail($request);

        $user = $this->getUserByEmail($request->email);

        if ($this->isInvalidPatient($user)) {
            return back()->with('error', 'No such email is registered');
        }

        $token = $this->generateTokenForUser($user);
        $this->sendPasswordResetEmail($request->email, $token);

        return redirect()->route('patient.login.view')->with('success', 'E-mail is sent for password reset.');
    }

    /**
     * Show password update form, or show password update success page if already updated
     *
     * @param mixed $token
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function showResetPasswordForm($token)
    {
        try {
            $tokenValue = Crypt::decrypt($token);
            $userData = $this->getUserByToken($tokenValue);

            if ($userData) {
                return view('patientSite/patientPasswordReset', ['token' => $tokenValue]);
            }
            return view('patientSite/passwordUpdatedSuccess');
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * Update password of patient and delete token
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitResetPasswordForm(Request $request)
    {
        $this->validatePasswordUpdate($request);

        $updatePassword = $this->getUserByToken($request->token);

        if (! $updatePassword) {
            return back()->with('error', 'Invalid token!');
        }

        $this->updateUserPassword($request->token, $request->new_password);

        return redirect()->route('patient.login.view')->with('success', 'Your password has been changed!');
    }

    /**
     * Logout user(patient)
     *
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('patient.login.view');
    }

    // Helper methods

    private function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'password' => 'required|min:8|max:30|regex:/^\S(.*\S)?$/',
        ]);
    }

    private function getCredentials(Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password,
        ];
    }

    private function handlePatientLogin()
    {
        $userRole = UserRoles::where('user_id', Auth::user()->id)->first();
        if ($userRole->role_id === 3) {
            return redirect()->route('patient.dashboard');
        }
        return back()->with('error', 'Invalid credentials');
    }

    private function handleLoginFailure(Request $request)
    {
        $isUserExist = Users::where('email', $request->email)->first();
        if ($isUserExist === null) {
            return back()->with('error', 'We could not find an account associated with that email address');
        }
        return back()->with('error', 'Incorrect Password, Please Enter Correct Password');
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

    private function isInvalidPatient($user)
    {
        return $user === null || in_array(UserRoles::where('user_id', $user->id)->value('role_id'), [1, 2]);
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
        Mail::send('email.forgetPassword', ['token' => $token], function ($message) use ($email) {
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
            'new_password' => 'required|min:8|max:30',
            'confirm_password' => 'required|same:new_password',
        ]);
    }

    private function updateUserPassword($token, $newPassword)
    {
        Users::where(['token' => $token])->update([
            'password' => Hash::make($newPassword),
            'token' => '',
        ]);
    }
}
