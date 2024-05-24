<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\AdminRegion;
use App\Models\AllUsers;
use App\Models\UserRoles;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;

class UserAccessService
{
    /**
     * list of user access
     *
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
     *
     * @param mixed $request
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function filterAccountWise($request)
    {
        $accountType = $request->selectedAccount === 'all' ? '' : $request->selectedAccount;
        $userAccessDataFiltering = AllUsers::select('roles.name', 'allusers.first_name', 'allusers.mobile', 'allusers.status', 'allusers.user_id')
            ->leftJoin('user_roles', 'user_roles.user_id', '=', 'allusers.user_id')
            ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id')
            ->whereIn('user_roles.role_id', [1, 2]);

        if ($accountType) {
            $userAccessDataFiltering = $userAccessDataFiltering->where('roles.name', '=', $accountType);
        }
        return $userAccessDataFiltering->paginate(10);
    }

    /**
     * create new admin and store data of admin in users,admin,user_roles,all_users
     *
     * @param mixed $request
     *
     * @return bool
     */
    public function createAdminAccount($request)
    {
        // Store Data in users table
        $adminCredentialsData = Users::create([
            'username' => $request->user_name,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        // Store Data in Admin Table
        $storeAdminData = Admin::create([
            'user_id' => $adminCredentialsData->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->phone_number,
            'address1' => $request->address1,
            'address2' => $request->address2,
            'city' => $request->city,
            'zip' => $request->zip,
            'alt_phone' => $request->alt_mobile,
            'status' => 'pending',
            'role_id' => $request->role,
            'region_id' => $request->state,
        ]);

        foreach ($request->region_id as $region) {
            AdminRegion::create([
                'admin_id' => $storeAdminData->id,
                'region_id' => $region,
            ]);
        }

        // make entry in user_roles table to identify the user(whether it is admin or physician)
        UserRoles::create([
            'user_id' => $adminCredentialsData->id,
            'role_id' => 1,
        ]);

        // store data in allusers table
        AllUsers::create([
            'user_id' => $adminCredentialsData->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'street' => $request->address1,
            'city' => $request->city,
            'zipcode' => $request->zip,
            'mobile' => $request->phone_number,
            'status' => 'pending',
        ]);

        return true;
    }
}
