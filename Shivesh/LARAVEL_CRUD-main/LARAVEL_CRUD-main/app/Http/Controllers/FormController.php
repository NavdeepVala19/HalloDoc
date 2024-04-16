<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Item;
use App\Http\Controllers\Image;

class FormController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('CRUD/read')->with('users', $users);
    }

    /**
  * Show the form for creating a new user.
 
  */
    public function create()
    {
        return view('CRUD/create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {

        $request->validate([
            'firstname' => ['required', 'min:2', 'max:30'],
            'lastname' => ['required', 'min:2', 'max:30'],
            'email' => ['required', 'email', 'min:10', 'max:30'],
            'password' => ['required', 'min:5', 'max:30'],
            'gender' => ['required'],
            'image' => ['required'],
        ]);


        $user = new User;
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->gender = $request->input('gender');
        $user->image = $request->file('image')->store('public');

        // $imagePath = $request->file('image')->store('images', 'public');

        // Image::create([
        //     'image_path' => $imagePath,
        // ]);


        // $attributes['image'] = request()->file('image')->store('public');
        $user->save();

        return redirect('index')->withSuccess('Successfully registered');

    }

    public function read()
    {
        $users = User::all();
        return view('CRUD/read')->with('users', $users);
    }



    public function edit($id)
    {
        $users = User::find($id);
        return view('CRUD/update', compact('users'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        // Log::info("Request data", $request->all());
        

        $request->validate([
            'firstname' => ['required', 'min:2', 'max:30'],
            'lastname' => ['required', 'min:2', 'max:30'],
            'email' => ['required', 'email', 'min:10', 'max:30'],
            'password' => ['required', 'min:5', 'max:30'],
            'gender' => ['required'],
            'image' => ['required'],
        ]);

        $user = User::find($id);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->gender = $request->gender;
        // $user->image = $request->image;

        $user->image = $request->file('image')->store('public');

        // $user->image = decrypt($user->image);
        // dd($request->image);

        $user->save();

        return redirect('index');

    }

    public function destroy($id)
    {
        $users = User::where('id', $id)->delete();
        // dd($users);

        return redirect('index');
    }

}
