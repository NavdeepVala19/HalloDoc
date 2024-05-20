<?php

namespace App\Http\Controllers;

use App\Models\AllUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Services\PatientDashboardService;
use App\Http\Requests\CreatePatientRequest;

class PatientProfileController extends Controller
{
    /**
     * display patient profile edit page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function patientEdit()
    {
        $userData = Auth::user();
        $email = $userData['email'];

        $getEmailData = AllUsers::where('email', $email)->first();
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
            $getPatientData = AllUsers::where('id', $id)->first();
            if ($getPatientData) {
                return view('patientSite/patientProfileEdit', compact('getPatientData'));
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
    public function patientUpdate(CreatePatientRequest $request, PatientDashboardService $patientDashboardService)
    {
        $userData = Auth::user();
        // Update data in users table

        $patientDashboardService->patientProfileUpdate($request,$userData);
        return redirect()->route('patient.dashboard')->with('message', 'profile is updated successfully');
    }


    /**
     * patient can see their location on google map
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function patientMapLocation()
    {
        $userData = Auth::user();
        $email = $userData['email'];
        $getEmailData = AllUsers::where('email', '=', $email)->first();
        $address = $getEmailData->street . $getEmailData->city . $getEmailData->state;

        return view('patientSite.patientMapLocation', compact('address'));
    }
}
