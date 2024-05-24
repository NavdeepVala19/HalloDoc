<?php

namespace App\Services;

use App\Models\AllUsers;
use App\Models\Users;

class PatientDashboardService
{
    /**
     * it update patient profile data in allusers and users table
     *
     * @param mixed $request (input enter by user)
     * @param mixed $userData (loged in patient data)
     *
     * @return bool
     */
    public function patientProfileUpdate($request, $userData)
    {
        $updateInUserTable = [
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'username' => $request->input('first_name') . $request->input('last_name'),
        ];

        $updateInAllUserTable = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('phone_number'),
            'date_of_birth' => $request->input('date_of_birth'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'street' => $request->input('street'),
            'zipcode' => $request->input('zipcode'),
        ];

        Users::where('email', $userData['email'])->update($updateInUserTable);
        AllUsers::where('email', $userData['email'])->update($updateInAllUserTable);

        return true;
    }
}
