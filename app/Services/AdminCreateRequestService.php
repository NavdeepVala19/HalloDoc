<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AllUsers;
use App\Models\Users;

class AdminCreateRequestService
{
    /**
     * it will return data of admin when route from one page to their profile edit page
     *
     * @param mixed $id (id of user table)
     *
     * @return Admin|object|\Illuminate\Database\Eloquent\Model|null
     */
    public function adminProfile($id)
    {
        return Admin::select(
            'admin.first_name',
            'admin.last_name',
            'admin.email',
            'admin.mobile',
            'admin.address1',
            'admin.address2',
            'admin.city',
            'admin.zip',
            'admin.status',
            'admin.user_id',
            'alt_phone',
            'role.name',
            'regions.region_name',
            'regions.id'
        )
            ->leftJoin('role', 'role.id', 'admin.role_id')
            ->leftJoin('users', 'users.id', 'admin.user_id')
            ->leftJoin('regions', 'regions.id', 'admin.region_id')
            ->where('user_id', $id)
            ->first();
    }

    /**
     * it update admin profile administration information data in admin,allusers and users table
     *
     * @param mixed $request (input enter by user(admin))
     * @param mixed $id (id of user table)
     *
     * @return bool
     */
    public function updateAdminInformation($request, $id)
    {
        // Update in admin table
        $updateAdminInformation = Admin::where('user_id', $id)->first();
        $updateAdminInformation->first_name = $request->first_name;
        $updateAdminInformation->last_name = $request->last_name;
        $updateAdminInformation->email = $request->email;
        $updateAdminInformation->mobile = $request->phone_number;
        $updateAdminInformation->save();

        // update Data in allusers table
        $updateAdminInfoAllUsers = AllUsers::where('user_id', $id)->first();
        $updateAdminInfoAllUsers->first_name = $request->first_name;
        $updateAdminInfoAllUsers->last_name = $request->last_name;
        $updateAdminInfoAllUsers->email = $request->email;
        $updateAdminInfoAllUsers->mobile = $request->phone_number;
        $updateAdminInfoAllUsers->save();

        // update email and phone number in users table
        $updateUserInfo = Users::where('id', $id)->first();
        $updateUserInfo->email = $request->email;
        $updateUserInfo->phone_number = $request->phone_number;
        $updateUserInfo->save();

        return true;
    }

    /**
     * it update admin profile Mailing & Billing Information data in admin and allusers table
     *
     * @param mixed $request (input enter by user(admin))
     * @param mixed $id (id of user table)
     *
     * @return bool
     */
    public function updateAdminMailInformation($request, $id)
    {
        // Update in admin table
        $updateAdminInformation = Admin::where('user_id', $id)->first();
        $updateAdminInformation->city = $request->city;
        $updateAdminInformation->address1 = $request->address1;
        $updateAdminInformation->address2 = $request->address2;
        $updateAdminInformation->zip = $request->zip;
        $updateAdminInformation->alt_phone = $request->alt_mobile;
        $updateAdminInformation->region_id = $request->select_state;
        $updateAdminInformation->save();

        // update Data in allusers table
        $updateAdminInfoAllUsers = AllUsers::where('user_id', $id)->first();
        $updateAdminInfoAllUsers->city = $request->city;
        $updateAdminInfoAllUsers->street = $request->address1;
        $updateAdminInfoAllUsers->zipcode = $request->zip;
        $updateAdminInfoAllUsers->save();

        return true;
    }
}
