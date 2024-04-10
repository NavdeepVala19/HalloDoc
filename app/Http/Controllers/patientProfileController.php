<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\users;
use App\Models\allusers;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\request_Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class patientProfileController extends Controller
{
    public function patientEdit(Request $request)
    {
        $userData = Auth::user();
        $email = $userData["email"];

        $getEmailData = allusers::where('email', '=', $email)->first();
        return view("patientSite/patientProfile", compact('getEmailData'));
    }

    public function patientprofileEdit($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $getPatientData = allusers::where('id', '=', $id)->first();
            if (!empty($getPatientData)) {
                return view("patientSite/patientProfileEdit", compact('getPatientData'));
            }
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }

    public function patientUpdate(Request $request)
    {

        $request->validate([
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'required|min:2|max:30',
            'date_of_birth' => 'required',
            'email' => 'required|email|min:2|max:30',
            'phone_number' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'street' => 'required|min:2',
            'city' => 'required|min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'state' => 'required|min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'zipcode' => 'digits:6',
        ]);


        $userData = Auth::user();

        // update Data in requestClientTable
        $updatedData = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'street' => $request->input('street'),
            'zipcode' => $request->input('zipcode')
        ];

        // update Data in RequestTable

        $updatedRequestTableData = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
        ];

        // Update data in users table
        $updateUserData = [
            'email' => $request->input('email'),
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

        $updateData = request_Client::where('email', $userData['email'])->update($updatedData);

        $updateUser = users::where('email', $userData['email'])->update($updateUserData);

        $updateAllUserData = allusers::where('email', $userData['email'])->update($updateAllUser);

        $updateRequestTableData = RequestTable::where('email', $userData['email'])->update($updatedRequestTableData);


        return redirect()->route('patientDashboardData');
    }

    public function patientMapLocation()
    {
        $userData = Auth::user();
        $email = $userData["email"];
        $getEmailData = allusers::where('email', '=', $email)->first();
        $address = $getEmailData->street . $getEmailData->city . $getEmailData->state;

        return view('patientSite.patientMapLocation', compact('address'));
    }
}
