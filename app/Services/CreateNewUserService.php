<?php

namespace App\Services;

use App\Models\AllUsers;
use App\Models\UserRoles;
use App\Models\Users;

class CreateNewUserService
{
    /**
     * store data in users,all_users,user_roles table
     *
     * @param mixed $request
     *
     * @return int id (id of user table)
     */
    public function storeNewUsers($request)
    {
        $userId = $this->storeInUsersTable($request);
        $this->storeInAllUsers($request, $userId);
        $this->storeInUserRoles($userId);

        return $userId;
    }

    /**
     * store data in users
     *
     * @param mixed $request
     *
     * @return int id (id of user table)
     */
    private function storeInUsersTable($request)
    {
        $storePatientInUsers = new Users();
        $storePatientInUsers->username = $request->first_name . ' ' . $request->last_name;
        $storePatientInUsers->email = $request->email;
        $storePatientInUsers->phone_number = $request->phone_number;
        $storePatientInUsers->save();

        return $storePatientInUsers->id;
    }

    /**
     * store data in all_users
     *
     * @param mixed $request
     *
     * @return void
     */
    private function storeInAllUsers($request, $userId)
    {
        $storePatientInAllUsers = new AllUsers();
        $storePatientInAllUsers->user_id = $userId;
        $storePatientInAllUsers->fill($request->only([
            'first_name',
            'last_name',
            'email',
            'mobile',
            'street',
            'city',
            'state',
            'zipcode',
        ]));
        $storePatientInAllUsers->save();
    }

    /**
     * store data in user_roles
     *
     * @param mixed $userId
     *
     * @return void
     */
    private function storeInUserRoles($userId)
    {
        $userRole = new UserRoles();
        $userRole->role_id = 3;
        $userRole->user_id = $userId;
        $userRole->save();
    }
}
