<?php

namespace App\Http\Controllers;

use App\Models\users;
use App\Models\Provider;
use App\Models\RequestWiseFile;
use Illuminate\Http\Request;

class AdminProviderController extends Controller
{


    public function readProvidersInfo()
    {
        $providersData = Provider::paginate(10);
        return view('/adminPage/provider/adminProvider', compact('providersData'));

    }


    // ****************** This code is for creating a new provider ************************
    public function newProvider()
    {
        return view('/adminPage/provider/adminNewProvider');
    }

    public function adminCreateNewProvider(Request $request)
    {



        $request->validate([
            'user_name' => 'required',
            'password' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'medical_license' => 'required',
            'npi_number' => 'required',
            'email_alt' => 'required|email',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'phone_number_alt' => 'required',
            'business_name' => 'required',
            'business_website' => 'required',
            'admin_notes' => 'required',
        ]);

        // store data of providers in users table

        $userProvider = new users();
        $userProvider->username = $request->user_name;
        $userProvider->password = $request->password;
        $userProvider->email = $request->email;
        $userProvider->phone_number = $request->phone_number;
        // $userProvider->save();


        // store data of providers in providers table

        $providerData = new Provider();
        $providerData->user_id = $userProvider->id;
        $providerData->first_name = $request->first_name;
        $providerData->last_name = $request->last_name;
        $providerData->email = $request->email;
        $providerData->mobile = $request->phone_number;
        $providerData->alt_phone = $request->phone_number_alt;
        $providerData->medical_license = $request->medical_license;
        $providerData->npi_number = $request->npi_number;
        $providerData->syncEmailAddress = $request->email_alt;
        $providerData->address1 = $request->address1;
        $providerData->address2 = $request->address2;
        $providerData->city = $request->city;
        $providerData->zip = $request->zip;
        $providerData->business_name = $request->business_name;
        $providerData->business_website = $request->business_website;
        $providerData->admin_notes = $request->admin_notes;

        // $providerData->save();





        // store documents in request_wise_file
        $request_file = new RequestWiseFile();

        if (isset($request->provider_photo)) {
            $request_file = new RequestWiseFile();
            $request_file->physician_id = $providerData->id;

            $request_file->file_name = $request->file('provider_photo')->getClientOriginalName();

            $providerData->photo = $request_file->file_name;

            $path = $request->file('provider_photo')->storeAs('public', $request->file('provider_photo')->getClientOriginalName());
            $request_file->save();
            $providerData->save();

        }


        if (isset($request->provider_signature)) {
            $request_file = new RequestWiseFile();
            $request_file->physician_id = $providerData->id;

            $request_file->file_name = $request->file('provider_signature')->getClientOriginalName();

            $providerData->signature = $request_file->file_name;

            $path = $request->file('provider_signature')->storeAs('public', $request->file('provider_signature')->getClientOriginalName());
            $request_file->save();
            $providerData->save();

        }

        if (isset($request->independent_contractor)) {
            $request_file = new RequestWiseFile();
            $request_file->physician_id = $providerData->id;

            $request_file->file_name = $request->file('independent_contractor')->getClientOriginalName();

            $providerData->IsAgreementDoc = 1;

            $path = $request->file('independent_contractor')->storeAs('public', $request->file('independent_contractor')->getClientOriginalName());
            $request_file->save();
            $providerData->save();
        }


        if (isset($request->background_doc)) {
            $request_file = new RequestWiseFile();
            $request_file->physician_id = $providerData->id;

            $request_file->file_name = $request->file('background_doc')->getClientOriginalName();

            $providerData->IsBackgroundDoc = 1;

            $path = $request->file('background_doc')->storeAs('public', $request->file('background_doc')->getClientOriginalName());
            $request_file->save();
            $providerData->save();
        }


        if (isset($request->hipaa_docs)) {
            $request_file = new RequestWiseFile();
            $request_file->physician_id = $providerData->id;

            $request_file->file_name = $request->file('hipaa_docs')->getClientOriginalName();

            $providerData->IsTrainingDoc = 1;

            $path = $request->file('hipaa_docs')->storeAs('public', $request->file('hipaa_docs')->getClientOriginalName());
            $request_file->save();
            $providerData->save();
        }


        if (isset($request->non_disclosure_doc)) {
            $request_file = new RequestWiseFile();
            $request_file->physician_id = $providerData->id;

            $request_file->file_name = $request->file('non_disclosure_doc')->getClientOriginalName();

            $providerData->IsNonDisclosureDoc = 1;

            $path = $request->file('non_disclosure_doc')->storeAs('public', $request->file('non_disclosure_doc')->getClientOriginalName());
            $request_file->save();
            $providerData->save();
        }


        if (isset($request->license_doc)) {
            $request_file = new RequestWiseFile();
            $request_file->physician_id = $providerData->id;

            $request_file->file_name = $request->file('license_doc')->getClientOriginalName();


            $providerData->IsLicenseDoc = 1;

            $path = $request->file('license_doc')->storeAs('public', $request->file('license_doc')->getClientOriginalName());
            $request_file->save();
            $providerData->save();
        }



        return redirect()->route('adminProvidersInfo');

    }


    // **************** This code is for edit provider profile *********************

    public function editProvider()
    {
        return view('/adminPage/provider/adminEditProvider');
    }

    public function updateAdminProviderProfile(Request $request, $id)
    {

        $getProviderData = Provider::with('users')->where('id', $id)->first();
        // dd($getProviderData);



        $updateProviderData = [
            'username' => $request->input('user_name'),
            'password' => $request->input('password'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'syncEmailAddress' => $request->input('alt_email'),
            'mobile' => $request->input('phone_number'),
            'medical_license' => $request->input('medical_license'),
            'npi_number' => $request->input('npi_number'),
            'city' => $request->input('city'),
            'address1' => $request->input('address1'),
            'address2' => $request->input('address2'),
            'zip' => $request->input('zip'),
            'alt_phone' => $request->input('alt_phone_number'),
            'business_name' => $request->input('business_name'),
            'business_website' => $request->input('business_website'),
        ];



        return view('/adminPage/provider/adminEditProvider', compact('getProviderData'));
    }
}
