<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AllUsers;
use App\Models\Users;
use App\Models\UserRoles;
use App\Models\AdminRegion;
use Illuminate\Support\Facades\Hash;

class UserAccessService
{

    /**
     * list of user access
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function userAccessList()
    {
        return AllUsers::select('roles.name', 'allusers.first_name', 'allusers.mobile', 'allusers.status', 'allusers.user_id')
            ->leftJoin('user_roles', 'user_roles.user_id', '=', 'allusers.user_id')
            ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id')
            ->whereIn('user_roles.role_id', [1, 2])
            ->paginate(10);
    }

    /**
     * filter user according to account type (admin/provider)
     * @param mixed $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function filterAccountWise($request)
    {
        $accountType = $request->selectedAccount === "all" ? '' : $request->selectedAccount;
        $userAccessDataFiltering = AllUsers::select('roles.name', 'allusers.first_name', 'allusers.mobile', 'allusers.status', 'allusers.user_id')
            ->leftJoin('user_roles', 'user_roles.user_id', '=', 'allusers.user_id')
            ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id')
            ->whereIn('user_roles.role_id', [1, 2]);

        if ($accountType) {
            $userAccessDataFiltering = $userAccessDataFiltering->where('roles.name', '=', $accountType);
        }
        $userAccessDataFiltering = $userAccessDataFiltering->paginate(10);

        return $userAccessDataFiltering;
    }

    /**
     * create new admin and store data of admin in users,admin,user_roles,all_users
     * @param mixed $request
     * @return bool
     */
    public function createAdminAccount($request)
    {
        // Store Data in users table
        $adminCredentialsData = new Users();
        $adminCredentialsData->username = $request->user_name;
        $adminCredentialsData->password = Hash::make($request->password);
        $adminCredentialsData->email = $request->email;
        $adminCredentialsData->phone_number = $request->phone_number;
        $adminCredentialsData->save();

        // Store Data in Admin Table 
        $storeAdminData = new Admin();
        $storeAdminData->user_id = $adminCredentialsData->id;
        $storeAdminData->first_name = $request->first_name;
        $storeAdminData->last_name = $request->last_name;
        $storeAdminData->email = $request->email;
        $storeAdminData->mobile = $request->phone_number;
        $storeAdminData->address1 = $request->address1;
        $storeAdminData->address2 = $request->address2;
        $storeAdminData->city = $request->city;
        $storeAdminData->zip = $request->zip;
        $storeAdminData->alt_phone = $request->alt_mobile;
        $storeAdminData->status = 'pending';
        $storeAdminData->role_id = $request->role;
        $storeAdminData->region_id = $request->state;
        $storeAdminData->save();

        foreach ($request->region_id as $region) {
            AdminRegion::create([
                'admin_id' => $storeAdminData->id,
                'region_id' => $region
            ]);
        }

        // make entry in user_roles table to identify the user(whether it is admin or physician)
        $user_roles = new UserRoles();
        $user_roles->user_id = $adminCredentialsData->id;
        $user_roles->role_id = 1;
        $user_roles->save();

        // store data in allusers table
        $adminAllUserData = new AllUsers();
        $adminAllUserData->user_id = $adminCredentialsData->id;
        $adminAllUserData->first_name = $request->first_name;
        $adminAllUserData->last_name = $request->last_name;
        $adminAllUserData->email = $request->email;
        $adminAllUserData->street = $request->address1;
        $adminAllUserData->city = $request->city;
        $adminAllUserData->zipcode = $request->zip;
        $adminAllUserData->mobile = $request->phone_number;
        $adminAllUserData->status = 'pending';
        $adminAllUserData->save();

        return true;
    }
}
