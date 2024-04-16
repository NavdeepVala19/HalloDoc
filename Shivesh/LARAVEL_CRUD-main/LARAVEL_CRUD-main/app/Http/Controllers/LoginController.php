<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function login()
    {
        return view("CRUD/login");
    }


    public function userLogin(Request $request)
    {

        // dd($request->all());

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);


        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('read')->withSuccess('Successfully logged in');
        } else {
            return redirect("login")->with('error',"Invalid crendentials");
        }
    }


    public function logout()
    {
        Auth::logout();
        return redirect("login");
    }

}
