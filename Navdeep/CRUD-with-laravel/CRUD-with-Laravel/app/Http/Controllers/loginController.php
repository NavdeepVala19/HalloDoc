<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function loginPage()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credintials = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required']
            ]
        );

        if (Auth::attempt($credintials)) {
            $request->session()->regenerate();
            return redirect()->intended('/read')->withSuccess('Signed In!');
        }

        return back()->with('error', 'Check Email & Password Again!!!')->onlyInput('email');
        // return redirect()->intended('/login')->withSuccess("Login Details are not valid");
    }

    public function loggedIn()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return view("read", compact('user'));
        } else {
            return redirect()->route('login.page');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
