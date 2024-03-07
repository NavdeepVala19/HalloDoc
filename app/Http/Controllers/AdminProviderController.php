<?php

namespace App\Http\Controllers;

use App\Models\users;
use App\Models\Regions;
use App\Models\Provider;
use App\Models\UserRoles;
use Illuminate\Http\Request;
use App\Mail\ContactProvider;
use App\Models\PhysicianRegion;
use App\Models\RequestWiseFile;
use App\Models\PhysicianLocation;
use Geocoder\Exception\Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Geocoder\Laravel\Facades\Geocoder;
use Illuminate\Contracts\Mail\Mailable;
use Geocoder\Exception\GeocodingException;
use Geocoder\Provider\GoogleMaps\GoogleMaps;


class AdminProviderController extends Controller
{

    // ****************** This code is for listing Providers Details ************************

    public function readProvidersInfo()
    {
        $providersData = Provider::paginate(10);
        return view('/adminPage/provider/adminProvider', compact('providersData'));
    }


    // ****************** This code is for Sending Mail ************************

    public function sendMailToContactProvider(Request $request, $id)
    {
        $providerData = Provider::get()->where('id', $request->provider_id);

        Mail::send('email.contactYourProvider', ['id' => $request->provider_id], function ($message) use ($providerData) {
            $message->to($providerData->first()->email);
        });

        return redirect()->route('adminProvidersInfo')->with('message', 'Your mail has been sent successfully.');
    }



    // ****************** This code is for creating a new provider ************************

    public function newProvider()
    {
        $regions = Regions::get();

        return view('/adminPage/provider/adminNewProvider', compact('regions'));
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
        $userProvider->password = Hash::make($request->password);
        $userProvider->email = $request->email;
        $userProvider->phone_number = $request->phone_number;
        $userProvider->save();


        // store data in physician region

        $providerData = new Provider();
        $physicianRegion = new PhysicianRegion();





        // store data of providers in providers table

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

        $providerData->save();



        foreach ($request->region_id as $region) {
            PhysicianRegion::insert([
                [
                    'provider_id' => $providerData->id,
                    'region_id' => $region
                ]
            ]);
        }
        $data = PhysicianRegion::where('provider_id', $providerData->id)->pluck('id')->toArray();
        $ids = implode(',', $data);

        Provider::where('id', $providerData->id)->update(['regions_id' => $ids]);



        // make entry in user_roles table to identify the user(whether it is admin or physician)

        $user_roles = new UserRoles();
        $user_roles->user_id = $userProvider->id;
        $user_roles->role_id = 2;
        $user_roles->save();




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

    public function editProvider($id)
    {
        $getProviderData = Provider::with('users')->where('id', $id)->first();
        return view('/adminPage/provider/adminEditProvider', compact('getProviderData'));
    }


    public function updateAdminProviderProfile(Request $request, $id)
    {


        // $request->validate([
        //     'user_name' => 'required',
        //     'password' => 'required',
        //     'first_name' => 'required',
        //     'last_name' => 'required',
        //     'email' => 'required|email',
        //     'phone_number' => 'required',
        //     'medical_license' => 'required',
        //     'npi_number' => 'required',
        //     'email_alt' => 'required|email',
        //     'address1' => 'required',
        //     'address2' => 'required',
        //     'city' => 'required',
        //     'zip' => 'required',
        //     'phone_number_alt' => 'required',
        //     'business_name' => 'required',
        //     'business_website' => 'required',
        //     'admin_notes' => 'required',
        // ]);


        $getProviderInformation = Provider::with('users')->where('id', $id)->first();


        $getProviderInformation->users->username = $request->user_name;
        $getProviderInformation->users->password = $request->password;
        $getProviderInformation->first_name = $request->first_name;
        $getProviderInformation->last_name = $request->last_name;
        $getProviderInformation->email = $request->email;
        $getProviderInformation->syncEmailAddress = $request->alt_email;
        $getProviderInformation->mobile = $request->phone_number;
        $getProviderInformation->medical_license = $request->medical_license;
        $getProviderInformation->npi_number = $request->npi_number;
        $getProviderInformation->city = $request->city;
        $getProviderInformation->address1 = $request->address1;
        $getProviderInformation->address2 = $request->address2;
        $getProviderInformation->zip = $request->zip;
        $getProviderInformation->alt_phone = $request->alt_phone_number;
        $getProviderInformation->business_name = $request->business_name;
        $getProviderInformation->business_website = $request->business_website;
        $getProviderInformation->admin_notes = $request->admin_notes;



        $getProviderInformation->save();

        return redirect()->route('adminProvidersInfo')->with('message', 'account is updated');
    }


    public function deleteProviderAccount($id)
    {
        $ProviderInfo = Provider::with('users')->where('id', $id)->first();
        $ProviderInfo->delete();

        return redirect()->route('adminProvidersInfo')->with('message', 'account is deleted');
    }




    // **************** Show Provider Location *************

    public function providerLocation()
    {

        $providers = Provider::where('id', '>', '38')->get();


        return view('adminPage/provider/providerLocation', compact('providers'));
    }
}
