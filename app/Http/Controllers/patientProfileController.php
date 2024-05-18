<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePatientRequest;
use App\Models\Users;
use App\Models\AllUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class patientProfileController extends Controller
{
    /**
     * display patient profile edit page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function patientEdit()
    {
        $userData = Auth::user();
        $email = $userData["email"];

        $getEmailData = AllUsers::where('email', '=', $email)->first();
        return view("patientSite/patientProfile", compact('getEmailData'));
    }



    /**
     * the page through which patient can edit their profile
     * @param mixed $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function patientprofileEdit($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $getPatientData = AllUsers::where('id', '=', $id)->first();
            if ($getPatientData) {
                return view("patientSite/patientProfileEdit", compact('getPatientData'));
            }
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }


    /**
     * patient update their data in users, allusers table
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function patientUpdate(CreatePatientRequest $request)
    {
        $userData = Auth::user();

        // Update data in users table
        $updateUserData = [
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'username' => $request->input('first_name') . $request->input('last_name'),
        ];

        // update Data in allusers table
        $updateAllUser = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('phone_number'),
            'date_of_birth' => $request->input('date_of_birth'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'street' => $request->input('street'),
            'zipcode' => $request->input('zipcode')
        ];

        Users::where('email', $userData['email'])->update($updateUserData);

        AllUsers::where('email', $userData['email'])->update($updateAllUser);

        return redirect()->route('patient.dashboard')->with('message', 'profile is updated successfully');
    }


    /**
     * patient can see their location on google map
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function patientMapLocation()
    {
        $userData = Auth::user();
        $email = $userData["email"];
        $getEmailData = AllUsers::where('email', '=', $email)->first();
        $address = $getEmailData->street . $getEmailData->city . $getEmailData->state;

        return view('patientSite.patientMapLocation', compact('address'));
    }
}
