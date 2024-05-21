<?php

namespace App\Services;

use App\Models\SMSLogs;
use Twilio\Rest\Client;
use App\Models\EmailLog;
use App\Models\Provider;
use App\Models\UserRoles;
use App\Models\ShiftDetail;
use App\Models\Users;
use App\Models\AllUsers;
use App\Mail\ContactProvider;
use App\Models\PhysicianRegion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminProviderService
{

    /**
     * it returns data for providers listing
     *
     * @return array
     */
    public function providersList()
    {
        $currentDate = now()->toDateString();
        $currentTime = now()->format('H:i');

        $onCallShifts = ShiftDetail::with('getShiftData')->where('shift_date', $currentDate)->where('start_time', '<=', $currentTime)->where('end_time', '>=', $currentTime)->get();
        $onCallPhysicianIds = $onCallShifts->whereNotNull('getShiftData.physician_id')->pluck('getShiftData.physician_id')->unique()->toArray();
        $providersData = Provider::with('role')->orderBy('created_at', 'asc')->paginate(10);

        return [
            'onCallPhysicianIds' => $onCallPhysicianIds,
            'providersData' => $providersData,
        ];
    }


    /**
     * it returns data of provider listing according to region selected for filtering
     *
     * @param mixed $request
     *
     * @return array
     */
    public function filterProviderList($request)
    {
        $currentDate = now()->toDateString();
        $currentTime = now()->format('H:i');

        $onCallShifts = ShiftDetail::with('getShiftData')->where('shift_date', $currentDate)->where('start_time', '<=', $currentTime)->where('end_time', '>=', $currentTime)->get();
        $onCallPhysicianIds = $onCallShifts->whereNotNull('getShiftData.physician_id')->pluck('getShiftData.physician_id')->unique()->toArray();

        if ($request->selectedId === "all") {
            $providersData = Provider::with('role')->orderBy('created_at', 'asc')->paginate(10);
        } else {
            $physicianRegions = PhysicianRegion::where('region_id', $request->selectedId)->pluck('provider_id');
            $providersData = Provider::with('role')->whereIn('id', $physicianRegions)->orderBy('created_at', 'asc')->paginate(10);
        }

        return [
            'onCallPhysicianIds' => $onCallPhysicianIds,
            'providersData' => $providersData,
        ];
    }

    /**
     * it facilitates contact to provider by SMS and Email
     *
     * @param mixed $request
     * @param mixed $id
     *
     * @return bool
     */
    public function contactToProvider($request, $id)
    {
        $receipientData = Provider::where('id', $id)->get();
        $receipientId = $id;
        $receipientName = $receipientData->first()->first_name;
        $receipientEmail = $receipientData->first()->email;
        $receipientMobile = $receipientData->first()->mobile;

        $enteredText = $request->contact_msg;
        if ($request->contact === "email") {
            // send email
            $providerData = Provider::get()->where('id', $request->provider_id);
            Mail::to($providerData->first()->email)->send(new ContactProvider($enteredText));

            EmailLog::create([
                'role_id' => 1,
                'admin_id' => 1,
                'provider_id' => $receipientId,
                'recipient_name' => $receipientName,
                'is_email_sent' => true,
                'sent_tries' => 1,
                'create_date' => now(),
                'sent_date' => now(),
                'email_template' => $enteredText,
                'subject_name' => 'notification to provider',
                'email' => $receipientEmail,
                'action'=>2,
            ]);
        } elseif ($request->contact === "sms") {
            // send SMS
            $sid = config('api.twilio_sid');
            $token = config('api.twilio_auth_token');
            $senderNumber = config('api.sender_number');

            $twilio = new Client($sid, $token);
            $twilio->messages
                ->create(
                    "+91 99780 71802", // to
                    [
                        "body" => "{$enteredText}",
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
                    'action' => 2,
                    'sms_template' => $enteredText,
                ]
            );
        } elseif ($request->contact === "both") {
            // send email
            $providerData = Provider::get()->where('id', $request->provider_id);
            Mail::to($providerData->first()->email)->send(new ContactProvider($enteredText));

            // send SMS
            $sid = config('api.twilio_sid');
            $token = config('api.twilio_auth_token');
            $senderNumber = config('api.sender_number');

            $twilio = new Client(
                $sid,
                $token
            );

            $twilio->messages
                ->create(
                    "+91 99780 71802",
                    [
                        "body" => "{$enteredText}",
                        "from" => $senderNumber,
                    ]
                );

            EmailLog::create([
                'role_id' => 1,
                'admin_id' => 1,
                'provider_id' => $receipientId,
                'recipient_name' => $receipientName,
                'is_email_sent' => true,
                'sent_tries' => 1,
                'create_date' => now(),
                'sent_date' => now(),
                'email_template' => $enteredText,
                'subject_name' => 'notification to provider',
                'email' => $receipientEmail,
                'action' => 2,
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
                    'action' => 2,
                    'sms_template' => $enteredText,
                ]
            );
        }
        return true;
    }


    /**
     * it stores data in users,allusers,physicianRegion,provider and userRoles
     *
     * @param mixed $request (input enter by admin)
     *
     * @return bool
     */
    public function createNewProvider($request)
    {
        // store data of providers in users table
        $storeProviderDataInUser = new Users();
        $storeProviderDataInUser->username = $request->user_name;
        $storeProviderDataInUser->password = Hash::make($request->password);
        $storeProviderDataInUser->email = $request->email;
        $storeProviderDataInUser->phone_number = $request->phone_number;
        $storeProviderDataInUser->save();

        // store data of providers in providers table
        $providerData = new Provider();
        $providerData->user_id = $storeProviderDataInUser->id;
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

        // store region in physician_region table
        foreach ($request->region_id as $region) {
            PhysicianRegion::create([
                'provider_id' => $providerData->id,
                'region_id' => $region,
            ]);
        }

        // make entry in user_roles table to identify the user(whether it is admin or physician)
        $user_roles = new UserRoles();
        $user_roles->user_id = $storeProviderDataInUser->id;
        $user_roles->role_id = 2;
        $user_roles->save();

        // store data in allusers table
        $providerAllUsers = new AllUsers();
        $providerAllUsers->user_id = $storeProviderDataInUser->id;
        $providerAllUsers->first_name = $request->first_name;
        $providerAllUsers->last_name = $request->last_name;
        $providerAllUsers->email = $request->email;
        $providerAllUsers->mobile = $request->phone_number;
        $providerAllUsers->street = $request->address1;
        $providerAllUsers->city = $request->city;
        $providerAllUsers->zipcode = $request->zip;
        $providerAllUsers->status = 'pending';
        $providerAllUsers->save();

        // store documents in local storage

        $storeFilePath = 'public/provider';

        if (isset($request->provider_photo)) {
            $providerData->photo = $request->file('provider_photo')->getClientOriginalName();
            $request->file('provider_photo')->storeAs($storeFilePath, $request->file('provider_photo')->getClientOriginalName());
            $providerData->save();
        }

        if (isset($request->independent_contractor)) {
            $providerData->IsAgreementDoc = 1;

            $file = $request->file('independent_contractor');
            $filename = $providerData->id . '_ICA.pdf';
            $file->storeAs($storeFilePath, $filename);
            $providerData->save();
        }

        if (isset($request->background_doc)) {
            $providerData->IsBackgroundDoc = 1;

            $file = $request->file('background_doc');
            $filename = $providerData->id . '_BC.pdf';
            $file->storeAs($storeFilePath, $filename);
            $providerData->save();
        }

        if (isset($request->hipaa_docs)) {
            $providerData->IsTrainingDoc = 1;

            $file = $request->file('hipaa_docs');
            $filename = $providerData->id . '_HCA.pdf';
            $file->storeAs($storeFilePath, $filename);
            $providerData->save();
        }

        if (isset($request->non_disclosure_doc)) {
            $providerData->IsNonDisclosureDoc = 1;

            $file = $request->file('non_disclosure_doc');
            $filename = $providerData->id . '_NDD.pdf';
            $file->storeAs( $storeFilePath, $filename);
            $providerData->save();
        }

        return true;
    }


 /**
  * it updates physician information in edit physician account
  *
  * @param mixed $request (input enter by admin)
  * @param mixed $id (id of provider)
  *
  * @return bool
  */

    public function updatePhysicianInformation($request, $id)
    {
        $getProviderInformation = Provider::where('id', $id)->first();
        $getProviderInformation->first_name = $request->first_name;
        $getProviderInformation->last_name = $request->last_name;
        $getProviderInformation->email = $request->email;
        $getProviderInformation->mobile = $request->phone_number;
        $getProviderInformation->medical_license = $request->medical_license;
        $getProviderInformation->npi_number = $request->npi_number;
        $getProviderInformation->save();

        $getUserIdFromProvider = Provider::select('user_id')->where('id', $id);

        $updateProviderInfoUsers = Users::where('id', $getUserIdFromProvider)->first();
        $updateProviderInfoUsers->email = $request->email;
        $updateProviderInfoUsers->phone_number = $request->phone_number;
        $updateProviderInfoUsers->save();

        $updateProviderDataAllUsers = AllUsers::where('user_id', $getUserIdFromProvider)->first();
        $updateProviderDataAllUsers->first_name = $request->first_name;
        $updateProviderDataAllUsers->last_name = $request->last_name;
        $updateProviderDataAllUsers->email = $request->email;
        $updateProviderDataAllUsers->mobile = $request->phone_number;
        $updateProviderDataAllUsers->save();

        return true;
    }


    /**
     * it updates Mailing & Billing Information in edit physician account
     *
     * @param mixed $request (input enter by admin)
     * @param mixed $id (id of provider)
     *
     * @return bool
     */
    public function updatePhysicianMailInformation($request, $id)
    {
        $getProviderInformation = Provider::where('id', $id)->first();
        $getProviderInformation->city = $request->city;
        $getProviderInformation->address1 = $request->address1;
        $getProviderInformation->address2 = $request->address2;
        $getProviderInformation->zip = $request->zip;
        $getProviderInformation->alt_phone = $request->alt_phone_number;
        $getProviderInformation->regions_id = $request->regions;
        $getProviderInformation->save();

        $getUserIdFromProvider = Provider::select('user_id')->where('id', $id);

        $updateProviderDataAllUsers = AllUsers::where('user_id', $getUserIdFromProvider)->first();
        $updateProviderDataAllUsers->street = $request->address1;
        $updateProviderDataAllUsers->city = $request->city;
        $updateProviderDataAllUsers->zipcode = $request->zip;
        $updateProviderDataAllUsers->save();

        return true;
    }

    /**
     * it updates Provider Profile in edit physician account
     *
     * @param mixed $request (input enter by admin)
     * @param mixed $id (id of provider)
     *
     * @return bool
     */
    public function updateProviderProfile($request, $id)
    {
        $getProviderInformation = Provider::where('id', $id)->first();
        $getProviderInformation->business_name = $request->business_name;
        $getProviderInformation->business_website = $request->business_website;
        $getProviderInformation->admin_notes = $request->admin_notes;

        if (isset($request->provider_photo)) {
            $getProviderInformation->photo = $request->file('provider_photo')->getClientOriginalName();
            $request->file('provider_photo')->storeAs('public/provider', $request->file('provider_photo')->getClientOriginalName());
            $getProviderInformation->save();
        }
        $getProviderInformation->save();

        return true;
    }

    /**
     * it updates Provider Onboarding Documents in edit physician account
     *
     * @param mixed $request (input enter by admin)
     * @param mixed $id (id of provider)
     *
     * @return bool
     */
    public function updateProviderDocumentsUpdate($request, $id)
    {
        $storeFilePath = 'public/provider';
        $getProviderInformation = Provider::where('id', $id)->first();

        if (isset($request->independent_contractor)) {
            $getProviderInformation->IsAgreementDoc = 1;
            $file = $request->file('independent_contractor');
            $filename = $getProviderInformation->id . '_ICA.pdf';
            $file->storeAs($storeFilePath, $filename);
            $getProviderInformation->save();
        }

        if (isset($request->background_doc)) {
            $getProviderInformation->IsBackgroundDoc = 1;
            $file = $request->file('background_doc');
            $filename = $getProviderInformation->id . '_BC.pdf';
            $file->storeAs($storeFilePath, $filename);
            $getProviderInformation->save();
        }

        if (isset($request->hipaa_docs)) {
            $getProviderInformation->IsTrainingDoc = 1;
            $file = $request->file('hipaa_docs');
            $filename = $getProviderInformation->id . '_HCA.pdf';
            $file->storeAs($storeFilePath, $filename);
            $getProviderInformation->save();
        }

        if (isset($request->non_disclosure_doc)) {
            $getProviderInformation->IsNonDisclosureDoc = 1;
            $file = $request->file('non_disclosure_doc');
            $filename = $getProviderInformation->id . '_NDD.pdf';
            $file->storeAs($storeFilePath, $filename);
            $getProviderInformation->save();
        }

        return true;
    }
}
