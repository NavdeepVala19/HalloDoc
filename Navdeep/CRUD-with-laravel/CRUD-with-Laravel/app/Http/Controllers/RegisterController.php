<?php

namespace App\Http\Controllers;

use App\Models\registerModel;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    public function show()
    {
        return view('index');
    }
    public function register()
    {
        return view("create");
    }

    public function create()
    {
        // create the user and store the data
        // var_dump(request()->all());
        $attributes =   request()->validate([
            'firstname' => ['required', 'max:255', 'min:3'],
            'lastname' =>  ['required', 'max:255', 'min:3'],
            'email' =>  ['required', 'email', 'max:255'],
            'password' => ['required', 'max:255'],
            'gender' => ['required'],
            'image' => ['required', 'image']
        ]);


        $attributes['password'] = bcrypt($attributes['password']);

        $attributes['image'] = request()->file('image')->store('public');

        registerModel::insert(
            [
                'firstname' =>  $attributes['firstname'],
                'lastname' =>  $attributes['lastname'],
                'email' => $attributes['email'],
                'password' => $attributes['password'],
                'gender' => $attributes['gender'],
                'image' =>  $attributes['image']
            ]
        );
        return redirect()->route('home')->with('message', 'Account Successfully created! Login To View the Data');
    }


    public function read()
    {
        // $users = registerModel::select('select * from crud_users ORDER BY id');
        $users = registerModel::orderby('id', 'asc')->get();
        return view("read", compact('users'));
        // return redirect()->route("landing.page", compact("users"));
    }
    public function updateView($id)
    {
        $user = registerModel::where("id", $id)->first();
        // dd($user);
        return view("update", compact("user"));
    }

    public function update($id)
    {

        $values = request()->validate([
            'firstname' => ['required', 'max:255', 'min:3'],
            'lastname' =>  ['required', 'max:255', 'min:3'],
            'email' =>  ['required', 'email', 'max:255'],
            'password' => ['required', 'max:255'],
            'gender' => ['required'],
            'image' => ['required', 'image']
        ]);
        $values['password'] = bcrypt($values['password']);
        $values['image'] = request()->file('image')->store('public');
        registerModel::where('id', $id)->update($values);
        return redirect()->route('list');
    }

    public function delete($id)
    {
        registerModel::where('id', $id)->delete();
        return redirect()->route('list');
    }
}
