<?php

namespace App\Services;

use App\Models\AllUsers;
use App\Models\Users;
use App\Models\UserRoles;

class CreateNewUserService{

    private function storeInUsersTable($request){
        $storePatientInUsers = new Users();
        $storePatientInUsers->username = $request->first_name . " " . $request->last_name;
        $storePatientInUsers->email = $request->email;
        $storePatientInUsers->phone_number = $request->phone_number;
        $storePatientInUsers->save();

        return $storePatientInUsers->id;
    }

    private function storeInAllUsers($request, $userId){
        $storePatientInAllUsers = new AllUsers();
        $storePatientInAllUsers->user_id = $userId;
        $storePatientInAllUsers->fill($request->only([
            'first_name',
            'last_name',
            'email',
            'phone_number',
            'street',
            'city',
            'state',
            'zipcode',
        ]));
        $storePatientInAllUsers->save();
    }

    private function storeInUserRoles($userId){
        $userRole = new UserRoles();
        $userRole->role_id = 3;
        $userRole->user_id = $userId;
        $userRole->save();
    }

    public function storeNewUsers($request){
        $userId = $this->storeInUsersTable($request);
        $this->storeInAllUsers($request, $userId);
        $this->storeInUserRoles($userId);

        return $userId;
    }
}