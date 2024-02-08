<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function newUserCase()
    {
        $users = User::get();
        return view('providerPage/provider', compact('users'));
    }
}
