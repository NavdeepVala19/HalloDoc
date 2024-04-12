<?php

namespace App\Http\Controllers;

use App\Mail\ContactProvider;
use App\Models\allusers;
use App\Models\EmailLog;
use App\Models\PhysicianRegion;
use App\Models\Provider;
use App\Models\Regions;
use App\Models\RequestWiseFile;
use App\Models\Role;
use App\Models\SMSLogs;
use App\Models\UserRoles;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

class AdminProviderController extends Controller
{

    // ****************** This code is for listing Providers Details ************************

    public function readProvidersInfo()
    {
        $providersData = Provider::orderBy('created_at', 'asc')->paginate(10);
        return view('/adminPage/provider/adminProvider', compact('providersData'));
    }

    // ****************** This code is for Filtering Physician through regions ************************

    public function filterPhysicianThroughRegions(Request $request)
    {
        if ($request->selectedId == "all") {
            $providersData = Provider::paginate(10);
        } else {
            $physicianRegions = PhysicianRegion::where('region_id', $request->selectedId)->pluck('provider_id');
            $providersData = Provider::whereIn('id', $physicianRegions)->paginate(10);
        }

        $data = view('/adminPage/provider/adminProviderFilterData')->with('providersData', $providersData)->render();
        return response()->json(['html' => $data]);
    }

    public function filterPhysicianThroughRegionsMobileView(Request $request)
    {

        if ($request->selectedId == "all") {
            $providersData = Provider::paginate(10);
        } else {
            $physicianRegions = PhysicianRegion::where('region_id', $request->selectedId)->pluck('provider_id');
            $providersData = Provider::whereIn('id', $physicianRegions)->paginate(10);
        }

        $data = view('/adminPage/provider/adminProviderFilterMobileData')->with('providersData', $providersData)->render();
        return response()->json(['html' => $data]);
    }

    // ****************** This code is for Sending Mail ************************

    public function sendMailToContactProvider(Request $request, $id)
    {
        $request->validate([
            'contact_msg' => 'required|min:2|max:100',
        ]);

        $receipientData = Provider::where('id', $id)->get();
        $receipientId = $id;
        $receipientName = $receipientData->first()->first_name;
        $receipientEmail = $receipientData->first()->email;
        $receipientMobile = $receipientData->first()->mobile;

        $enteredText = $request->contact_msg;

        if ($request->contact == "email") {
            // send email
            $providerData = Provider::get()->where('id', $request->provider_id);
            Mail::to($providerData->first()->email)->send(new ContactProvider($enteredText));

            EmailLog::create([
                'role_id' => 1,
                // 'provider_id' => specify provider id
                // 'email_template' =>,
                // 'subject_name' =>,
                'is_email_sent' => true,
                'sent_tries' => 1,
                'sent_date' => now(),
                'email_template' => $enteredText,
                'subject_name' => 'notification to provider',
                'email' => $receipientEmail,
                'provider_id' => $receipientId,
            ]);
        } else if ($request->contact == "sms") {
            // send SMS
            $sid = getenv("TWILIO_SID");
            $token = getenv("TWILIO_AUTH_TOKEN");
            $senderNumber = getenv("TWILIO_PHONE_NUMBER");

            $twilio = new Client($sid, $token);

            $message = $twilio->messages
                ->create(
                    "+91 99780 71802", // to
                    [
                        "body" => "$enteredText",
                        "from" => $senderNumber,
                    ]
                );

            SMSLogs::create(
                [
                    'provider_id' => $receipientId,
                    'mobile_number' => $receipientMobile,
                    'created_date' => now(),
                    'sent_date' => now(),
                    'role_id' => 1,
                    'recipient_name' => $receipientName,
                    'sent_tries' => 1,
                    'is_sms_sent' => 1,
                    'action' => 1,
                    'sms_template' => $enteredText,
                ]
            );
        } else if ($request->contact == "both") {
            // send email
            $providerData = Provider::get()->where('id', $request->provider_id);
            Mail::to($providerData->first()->email)->send(new ContactProvider($enteredText));

            // send SMS
            $sid = getenv("TWILIO_SID");
            $token = getenv("TWILIO_AUTH_TOKEN");
            $senderNumber = getenv("TWILIO_PHONE_NUMBER");

            $twilio = new Client(
                $sid,
                $token
            );

            $message = $twilio->messages
                ->create(
                    "+91 99780 71802", // to
                    [
                        "body" => "$enteredText",
                        "from" => $senderNumber,
                    ]
                );

            EmailLog::create([
                'role_id' => 1,
                // 'provider_id' => specify provider id
                // 'email_template' =>,
                // 'subject_name' =>,
                'is_email_sent' => true,
                'sent_tries' => 1,
                'sent_date' => now(),
                'email_template' => $enteredText,
                'subject_name' => 'notification to provider',
                'email' => $receipientEmail,
                'provider_id' => $receipientId,
            ]);

            SMSLogs::create(
                [
                    'provider_id' => $receipientId,
                    'mobile_number' => $receipientMobile,
                    'created_date' => now(),
                    'sent_date' => now(),
                    'role_id' => 1,
                    'recipient_name' => $receipientName,
                    'sent_tries' => 1,
                    'is_sms_sent' => 1,
                    'action' => 1,
                    'sms_template' => $enteredText,
                ]
            );
        }

        return redirect()->route('adminProvidersInfo')->with('message', 'Your message has been sent successfully.');
    }

    public function stopNotifications(Request $request)
    {
        $stopNotification = Provider::find($request->stopNotificationsCheckId);
        $stopNotification->update(['is_notifications' => $request->is_notifications]);
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
            'user_name' => 'required|alpha|min:3|max:40',
            'password' => 'required|min:8|max:20|regex:/^\S(.*\S)?$/',
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'email' => 'required|email|min:2|max:40|unique:App\Models\users,email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'medical_license' => 'required|alpha_num|max:20|min:3',
            'npi_number' => 'required|numeric|min:3|max_digits:7',
            'address1' => 'required|min:2|max:30|regex:/^[a-zA-Z0-9-, ]+$/',
            'address2' => 'required|min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'zip' => 'digits:6',
            'phone_number_alt' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'business_name' => 'required|min:3|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'provider_photo' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'business_website' => 'nullable|url|max:40|min:10',
            'admin_notes' => 'nullable|min:5|max:100|',
            'independent_contractor' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'background_doc' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'hipaa_docs' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'non_disclosure_doc' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
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
        $providerData->address1 = $request->address1;
        $providerData->address2 = $request->address2;
        $providerData->city = $request->city;
        $providerData->zip = $request->zip;
        $providerData->status = 'pending';
        $providerData->regions_id = $request->select_state;
        $providerData->business_name = $request->business_name;
        $providerData->business_website = $request->business_website;
        $providerData->admin_notes = $request->admin_notes;
        $providerData->role_id = $request->role;

        $providerData->save();

        foreach ($request->region_id as $region) {
            PhysicianRegion::create([

                'provider_id' => $providerData->id,
                'region_id' => $region,

            ]);
        }

        $data = PhysicianRegion::where('provider_id', $providerData->id)->pluck('id')->toArray();
        $ids = implode(',', $data);

        // make entry in user_roles table to identify the user(whether it is admin or physician)
        $user_roles = new UserRoles();
        $user_roles->user_id = $userProvider->id;
        $user_roles->role_id = 2;
        $user_roles->save();

        // store data in allusers table
        $providerAllUsers = new allusers();
        $providerAllUsers->user_id = $userProvider->id;
        $providerAllUsers->first_name = $request->first_name;
        $providerAllUsers->last_name = $request->last_name;
        $providerAllUsers->email = $request->email;
        $providerAllUsers->mobile = $request->phone_number;
        $providerAllUsers->street = $request->address1;
        $providerAllUsers->city = $request->city;
        $providerAllUsers->zipcode = $request->zip;
        $providerAllUsers->status = 'pending';
        $providerAllUsers->save();

        // store documents in request_wise_file
        $request_file = new RequestWiseFile();

        if (isset($request->provider_photo)) {
            $providerData->photo = $request->file('provider_photo')->getClientOriginalName();
            $path = $request->file('provider_photo')->storeAs('public/provider', $request->file('provider_photo')->getClientOriginalName());
            $providerData->save();
        }

        if (isset($request->independent_contractor)) {
            $providerData->IsAgreementDoc = 1;

            $file = $request->file('independent_contractor');
            $filename = $providerData->id . '_ICA' . '.' . "pdf";
            $path = $file->storeAs('public/provider', $filename);
            $providerData->save();
        }

        if (isset($request->background_doc)) {
            $providerData->IsBackgroundDoc = 1;

            $file = $request->file('background_doc');
            $filename = $providerData->id . '_BC' . '.' . "pdf";
            $path = $file->storeAs('public/provider', $filename);
            $providerData->save();
        }

        if (isset($request->hipaa_docs)) {
            $providerData->IsTrainingDoc = 1;

            $file = $request->file('hipaa_docs');
            $filename = $providerData->id . '_HCA' . '.' . "pdf";
            $path = $file->storeAs('public/provider', $filename);
            $providerData->save();
        }

        if (isset($request->non_disclosure_doc)) {
            $providerData->IsNonDisclosureDoc = 1;

            $file = $request->file('non_disclosure_doc');
            $filename = $providerData->id . '_NDD' . '.' . "pdf";
            $path = $file->storeAs('public/provider', $filename);
            $providerData->save();
        }

        // if (isset($request->license_doc)) {
        //     $request_file = new RequestWiseFile();
        //     $request_file->physician_id = $providerData->id;

        //     $request_file->file_name = $request->file('license_doc')->getClientOriginalName();

        //     $providerData->IsLicenseDoc = 1;

        //     $path = $request->file('license_doc')->storeAs('public', $request->file('license_doc')->getClientOriginalName());
        //     $request_file->save();
        //     $providerData->save();
        // }

        return redirect()->route('adminProvidersInfo');
    }

    public function regionName()
    {
        $regions = Regions::get();
        return view('/adminPage/provider/adminEditProvider', compact('regions'));
    }

    public function editProvider($id)
    {
        $getProviderData = Provider::with('users', 'role', 'Regions')->where('id', $id)->first();
        return view('/adminPage/provider/adminEditProvider', compact('getProviderData'));
    }

    public function updateProviderAccountInfo(Request $request, $id)
    {
        // update data of providers in users table
        $getUserIdFromProvider = Provider::select('user_id')->where('id', $id);
        $updateProviderInfoUsers = users::where('id', $getUserIdFromProvider->first()->user_id)->first();

        if (!empty($request->password)) {
            $updateProviderInfoUsers->password = Hash::make($request->password);
            $updateProviderInfoUsers->save();
        } else {
            $updateProviderInfoUsers->username = $request->user_name;
            $updateProviderInfoUsers->save();
        }
        $getProviderData = Provider::where('id', $id)->first();

        $getProviderData->status = $request->status_type;
        $getProviderData->role_id = $request->role;
        $getProviderData->save();

        $updateProviderDataAllUsers = allusers::where('user_id', $getUserIdFromProvider->first()->user_id)->first();
        if (!empty($updateProviderDataAllUsers)) {
            $updateProviderDataAllUsers->status = $request->status_type;
            $updateProviderDataAllUsers->save();
        }

        return back()->with('message', 'account information is updated');
    }

    public function providerInfoUpdate(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'email' => 'required|email|min:2|max:40|unique:App\Models\users,email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
            'medical_license' => 'required|alpha_num|max:20|min:3',
            'npi_number' => 'required|numeric|min:3|max_digits:7',
        ]);
        $getProviderInformation = Provider::with('users')->where('id', $id)->first();

        $getProviderInformation->first_name = $request->first_name;
        $getProviderInformation->last_name = $request->last_name;
        $getProviderInformation->email = $request->email;
        $getProviderInformation->syncEmailAddress = $request->alt_email;
        $getProviderInformation->mobile = $request->phone_number;
        $getProviderInformation->medical_license = $request->medical_license;
        $getProviderInformation->npi_number = $request->npi_number;

        $getProviderInformation->save();

        // update data in allusers table

        $getUserIdFromProvider = Provider::select('user_id')->where('id', $id)->first()->user_id;

        $updateProviderDataAllUsers = allusers::where('user_id', $getUserIdFromProvider)->first();

        if (empty($updateProviderDataAllUsers)) {
            return back()->with('message', 'Physician information is updated');
        } else {
            $updateProviderDataAllUsers->first_name = $request->first_name;
            $updateProviderDataAllUsers->last_name = $request->last_name;
            $updateProviderDataAllUsers->email = $request->email;
            $updateProviderDataAllUsers->mobile = $request->phone_number;
            $updateProviderDataAllUsers->save();
        }

        // update data in users table
        $updateProviderInfoUsers = users::where('id', $getUserIdFromProvider)->first();
        $updateProviderInfoUsers->email = $request->email;
        $updateProviderInfoUsers->phone_number = $request->phone_number;
        $updateProviderInfoUsers->save();

        return back()->with('message', 'Physician information is updated');
    }

    public function providerMailInfoUpdate(Request $request, $id)
    {

        $request->validate([
            'address1' => 'required|min:2|max:30',
            'address2' => 'required|min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'zip' => 'digits:6',
            'phone_number_alt' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
        ]);

        $getProviderInformation = Provider::with('users')->where('id', $id)->first();

        $getProviderInformation->city = $request->city;
        $getProviderInformation->address1 = $request->address1;
        $getProviderInformation->address2 = $request->address2;
        $getProviderInformation->zip = $request->zip;
        $getProviderInformation->alt_phone = $request->alt_phone_number;
        $getProviderInformation->regions_id = $request->regions;
        $getProviderInformation->save();

        $getUserIdFromProvider = Provider::select('user_id')->where('id', $id)->first()->user_id;

        if (empty($updateProviderDataAllUsers)) {
            return back()->with('message', 'Mailing and Billing information is updated');
        } 
        else {
            $updateProviderDataAllUsers = allusers::where('user_id', $getUserIdFromProvider)->first();
            $updateProviderDataAllUsers->street = $request->address1;
            $updateProviderDataAllUsers->city = $request->city;
            $updateProviderDataAllUsers->zipcode = $request->zip;
            $updateProviderDataAllUsers->save();

            return back()->with('message', 'Mailing and Billing information is updated');
        }
    }

    public function providerProfileUpdate(Request $request, $id)
    {
        $request->validate([
            'business_name' => 'required|min:3|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'provider_photo' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'business_website' => 'nullable|url|max:40|min:10',
            'admin_notes' => 'nullable|min:5|max:100|',
        ]);

        $getProviderInformation = Provider::where('id', $id)->first();

        $getProviderInformation->business_name = $request->business_name;
        $getProviderInformation->business_website = $request->business_website;
        $getProviderInformation->admin_notes = $request->admin_notes;

        if (isset($request->provider_photo)) {
            $getProviderInformation->photo = $request->file('provider_photo')->getClientOriginalName();
            $path = $request->file('provider_photo')->storeAs('public/provider', $request->file('provider_photo')->getClientOriginalName());
            $getProviderInformation->save();
        }

        $getProviderInformation->save();

        return back()->with('message', 'Provider Profile information is updated');
    }

    public function providerDocumentsUpdate(Request $request, $id)
    {
        $request->validate([
            'independent_contractor' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'background_doc' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'hipaa_docs' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'non_disclosure_doc' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
        ]);

        $getProviderInformation = Provider::where('id', $id)->first();

        if (isset($request->independent_doc)) {
            $getProviderInformation->IsAgreementDoc = 1;
            $file = $request->file('independent_doc');
            $filename = $getProviderInformation->id . '_ICA' . '.' . "pdf";
            $path = $file->storeAs('public/provider', $filename);
            $getProviderInformation->save();
        }

        if (isset($request->background_doc)) {
            $getProviderInformation->IsBackgroundDoc = 1;
            $file = $request->file('background_doc');
            $filename = $getProviderInformation->id . '_BC' . '.' . "pdf";
            $path = $file->storeAs('public/provider', $filename);
            $getProviderInformation->save();
        }

        if (isset($request->hipaa_docs)) {
            $getProviderInformation->IsTrainingDoc = 1;
            $file = $request->file('hipaa_docs');
            $filename = $getProviderInformation->id . '_HCA' . '.' . "pdf";
            $path = $file->storeAs('public/provider', $filename);
            $getProviderInformation->save();
        }

        if (isset($request->non_disclosure_doc)) {
            $getProviderInformation->IsNonDisclosureDoc = 1;
            $file = $request->file('non_disclosure_doc');
            $filename = $getProviderInformation->id . '_NDD' . '.' . "pdf";
            $path = $file->storeAs('public/provider', $filename);
            $getProviderInformation->save();
        }

        return back()->with('message', 'Document is uploaded');
    }

    public function deleteProviderAccount($id)
    {
        // soft delete in providers table
        $ProviderInfo = Provider::with('users')->where('id', $id)->first();
        $ProviderInfo->delete();

        //Soft delete in allusers table
        $providerDataAllUserDelete = allusers::where('user_id', $ProviderInfo->user_id)->first();
        $providerDataAllUserDelete->delete();

        return redirect()->route('adminProvidersInfo')->with('message', 'account is deleted');
    }

    // *** Show Provider Location ***
    public function providerLocations()
    {
        $providers = Provider::get();
        return view('adminPage/provider/providerLocation', compact('providers'));
    }

    public function fetchRolesName()
    {
        $fetchRoleName = Role::select('id', 'name')->where('account_type', 'physician')->get();
        return response()->json($fetchRoleName);
    }
}