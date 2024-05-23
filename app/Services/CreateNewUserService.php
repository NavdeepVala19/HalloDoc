<?php

namespace App\Services;

use App\Models\AllUsers;
use App\Models\UserRoles;
use App\Models\Users;

class CreateNewUserService
{
    public function storeNewUser($request)
    {
        // store email and phoneNumber in users table
        $newUser = Users::create([
            'username' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        // store all details of patient in allUsers table
        AllUsers::create([
            'user_id' => $newUser->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->phone_number,
            'street' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'zipcode' => $request->zipcode,
        ]);

        UserRoles::create([
            'role_id' => 3,
            'user_id' => $newUser->id,
        ]);

        return $newUser->id;
    }
}
