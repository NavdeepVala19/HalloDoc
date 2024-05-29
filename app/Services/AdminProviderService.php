<?php

namespace App\Services;

use App\Mail\ContactProvider;
use App\Models\Admin;
use App\Models\AllUsers;
use App\Models\EmailLog;
use App\Models\PhysicianRegion;
use App\Models\Provider;
use App\Models\ShiftDetail;
use App\Models\SMSLogs;
use App\Models\UserRoles;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

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

        if ($request->selectedId === 'all') {
            $providersData = Provider::with('role')->orderBy('created_at', 'asc')->paginate(10);
        } else {
            $physicianRegions = PhysicianRegion::where('region_id', $request->selectedId)->pluck('provider_id');
            $providersData = Provider::with('role')->whereIn('id', $physicianRegions)->oldest()->paginate(10);
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
        $providerEmail = Provider::where('id', $id)->value('email');
        $enteredText = $request->contact_msg;
        if ($request->contact === 'email') {
            $this->sendEmail($providerEmail, $enteredText);   // send email
            $this->entryInEmailLogs($request, $id);   // entry in email logs
        } elseif ($request->contact === 'sms') {
            $this->sendSMS($enteredText);    // send SMS
            $this->entryInSmsLogs($request, $id);   // entry in smslogs
        } elseif ($request->contact === 'both') {
            $this->sendEmail($providerEmail, $enteredText);  // send email
            $this->sendSMS($enteredText);  // send SMS
            $this->entryInEmailLogs($request, $id);  // entry in email logs
            $this->entryInSmsLogs($request, $id);  // entry in smslogs
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
        $userId = $this->storeNewProviderInUsers($request);
        $providerId = $this->storeNewProvider($request, $userId);
        $this->storeInPhysicianRegion($request, $providerId);
        $this->storeProviderInAllUsers($request, $userId);

        $this->uploadNewProviderPhoto($request, $providerId);
        $this->uploadProvidersDocuments($request, $providerId);
        $this->uploadOnBoardDocuments($request, $providerId);

        return true;
    }

    /**
     * updateAccountInformation in users, provider and AllUsers Table
     *
     * @param mixed $request(input enter by user)
     * @param mixed $id(id of provider table)
     * @param mixed $userId (id of user table)
     *
     * @return bool
     */

    public function updateAccountInformation($request, $id, $userId)
    {
        $this->updateAccountInfoInUsers($request, $userId);
        $this->updateAccountInProvider($request, $id);
        $this->updateAccountInAllUsers($request, $userId);

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
        $this->updatePhysicianInformationInProvider($request, $id);
        $this->updatePhysicianInformationInUsers($request, $id);
        $this->updatePhysicianInformationInAllUsers($request, $id);

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
        $this->updateMailingInformationInProvider($request, $id);
        $this->updateMailingInformationInAllUsers($request, $id);

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
        $this->updateProviderProfileData($request, $id);
        $this->uploadNewProviderPhoto($request, $id);

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
        $this->uploadProvidersDocuments($request, $id);
        $this->uploadOnBoardDocuments($request, $id);
        return true;
    }

    /**
     * update provider profile data
     *
     * @param mixed $request
     * @param mixed $id (id of provider)
     *
     * @return void
     */
    private function updateProviderProfileData($request, $id)
    {
        $getProviderInformation = Provider::where('id', $id)->first();
        $getProviderInformation->business_name = $request->business_name;
        $getProviderInformation->business_website = $request->business_website;
        $getProviderInformation->admin_notes = $request->admin_notes;
        $getProviderInformation->save();
    }

    /**
     * update account information of provider in users table
     *
     * @param mixed $userId (id of user table)
     * @param mixed $request
     *
     * @return void
     */
    private function updateAccountInfoInUsers($userId, $request)
    {
        $updateProviderInfoUsers = Users::where('id', $userId)->first();
        $updateProviderInfoUsers->username = $request->user_name;
        $updateProviderInfoUsers->save();
    }

    /**
     * update account information of provider in provider table
     *
     * @param mixed $userId (id of provider table)
     * @param mixed $request
     *
     * @return void
     */
    private function updateAccountInProvider($request, $id)
    {
        $getProviderData = Provider::where('id', $id)->first();
        $getProviderData->status = $request->status_type;
        $getProviderData->role_id = $request->role;
        $getProviderData->save();
    }

    /**
     * update account information of provider in all users table
     *
     * @param mixed $request
     * @param mixed $userId (id of user table)
     *
     * @return void
     */
    private function updateAccountInAllUsers($request, $userId)
    {
        $updateProviderDataAllUsers = AllUsers::where('user_id', $userId)->first();
        $updateProviderDataAllUsers->status = $request->status_type;
        $updateProviderDataAllUsers->save();
    }

    /**
     * update physician information in provider
     *
     * @param mixed $request
     * @param mixed $id
     *
     * @return void
     */
    private function updatePhysicianInformationInProvider($request, $id)
    {
        $getProviderInformation = Provider::where('id', $id)->first();
        $getProviderInformation->first_name = $request->first_name;
        $getProviderInformation->last_name = $request->last_name;
        $getProviderInformation->email = $request->email;
        $getProviderInformation->mobile = $request->phone_number;
        $getProviderInformation->medical_license = $request->medical_license;
        $getProviderInformation->npi_number = $request->npi_number;
        $getProviderInformation->save();
    }

    /**
     * update physician information in users
     *
     * @param mixed $request
     * @param mixed $id
     *
     * @return void
     */
    private function updatePhysicianInformationInUsers($request, $id)
    {
        $getUserIdFromProvider = Provider::select('user_id')->where('id', $id);

        $updateProviderInfoUsers = Users::where('id', $getUserIdFromProvider)->first();
        $updateProviderInfoUsers->email = $request->email;
        $updateProviderInfoUsers->phone_number = $request->phone_number;
        $updateProviderInfoUsers->save();
    }

    /**
     * update physician information in allusers
     *
     * @param mixed $request
     * @param mixed $id
     *
     * @return void
     */
    private function updatePhysicianInformationInAllUsers($request, $id)
    {
        $getUserIdFromProvider = Provider::select('user_id')->where('id', $id);

        $updateProviderDataAllUsers = AllUsers::where('user_id', $getUserIdFromProvider)->first();
        $updateProviderDataAllUsers->first_name = $request->first_name;
        $updateProviderDataAllUsers->last_name = $request->last_name;
        $updateProviderDataAllUsers->email = $request->email;
        $updateProviderDataAllUsers->mobile = $request->phone_number;
        $updateProviderDataAllUsers->save();
    }

    /**
     * update physician mailing information in provider
     *
     * @param mixed $request
     * @param mixed $id
     *
     * @return void
     */
    private function updateMailingInformationInProvider($request, $id)
    {
        $getProviderInformation = Provider::where('id', $id)->first();
        $getProviderInformation->city = $request->city;
        $getProviderInformation->address1 = $request->address1;
        $getProviderInformation->address2 = $request->address2;
        $getProviderInformation->zip = $request->zip;
        $getProviderInformation->alt_phone = $request->alt_phone_number;
        $getProviderInformation->regions_id = $request->regions;
        $getProviderInformation->save();
    }

    /**
     * update physician mailing information in allusers
     *
     * @param mixed $request
     * @param mixed $id
     *
     * @return void
     */

    private function updateMailingInformationInAllUsers($request, $id)
    {
        $getUserIdFromProvider = Provider::select('user_id')->where('id', $id);

        $updateProviderDataAllUsers = AllUsers::where('user_id', $getUserIdFromProvider)->first();
        $updateProviderDataAllUsers->street = $request->address1;
        $updateProviderDataAllUsers->city = $request->city;
        $updateProviderDataAllUsers->zipcode = $request->zip;
        $updateProviderDataAllUsers->save();
    }

    /**
     * store new provider data in users table
     *
     * @param mixed $request
     *
     * @return int id(id of user table)
     */
    private function storeNewProviderInUsers($request)
    {
        $storeProviderDataInUser = new Users();
        $storeProviderDataInUser->username = $request->user_name;
        $storeProviderDataInUser->password = Hash::make($request->password);
        $storeProviderDataInUser->email = $request->email;
        $storeProviderDataInUser->phone_number = $request->phone_number;
        $storeProviderDataInUser->save();

        $user_roles = new UserRoles();
        $user_roles->user_id = $storeProviderDataInUser->id;
        $user_roles->role_id = 2;
        $user_roles->save();

        return $storeProviderDataInUser->id;
    }

    /**
     * store new provider data in provider and physician region
     *
     * @param mixed $request
     * @param mixed $userId (id of user table)
     *
     * @return int id (id of provider table)
     */
    private function storeNewProvider($request, $userId)
    {
        $providerData = new Provider();
        $providerData->user_id = $userId;
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

        return $providerData->id;
    }

    /**
     * store region select by user in physician region
     *
     * @param mixed $request
     * @param mixed $providerId (id of provider)
     *
     * @return void
     */
    private function storeInPhysicianRegion($request, $providerId)
    {
        foreach ($request->region_id as $region) {
            PhysicianRegion::create([
                'provider_id' => $providerId,
                'region_id' => $region,
            ]);
        }
    }

    /**
     * store new provider in allusers table
     *
     * @param mixed $request
     * @param mixed $userId (id of user table)
     *
     * @return void
     */
    private function storeProviderInAllUsers($request, $userId)
    {
        $providerAllUsers = new AllUsers();
        $providerAllUsers->user_id = $userId;
        $providerAllUsers->first_name = $request->first_name;
        $providerAllUsers->last_name = $request->last_name;
        $providerAllUsers->email = $request->email;
        $providerAllUsers->mobile = $request->phone_number;
        $providerAllUsers->street = $request->address1;
        $providerAllUsers->city = $request->city;
        $providerAllUsers->zipcode = $request->zip;
        $providerAllUsers->status = 'pending';
        $providerAllUsers->save();
    }

    /**
     * upload provider photo
     *
     * @param mixed $request
     * @param mixed $providerId (id of provider table)
     *
     * @return void
     */
    private function uploadNewProviderPhoto($request, $providerId)
    {
        $storeFilePath = 'public/provider';
        $providerData = Provider::where('id', $providerId)->first();

        if (isset($request->provider_photo)) {
            $providerData->photo = $request->file('provider_photo')->getClientOriginalName();
            $request->file('provider_photo')->storeAs($storeFilePath, $request->file('provider_photo')->getClientOriginalName());
            $providerData->save();
        }
    }

    /**
     * upload onboarding documents
     *
     * @param mixed $request
     * @param mixed $providerId (id of provider table)
     *
     * @return void
     */
    private function uploadProvidersDocuments($request, $providerId)
    {
        $storeFilePath = 'public/provider';
        $providerData = Provider::where('id', $providerId)->first();

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
    }

    /**
     * upload onboarding documents
     *
     * @param mixed $request
     * @param mixed $providerId (id of provider table)
     *
     * @return void
     */
    private function uploadOnBoardDocuments($request, $providerId)
    {
        $storeFilePath = 'public/provider';
        $providerData = Provider::where('id', $providerId)->first();

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
            $file->storeAs($storeFilePath, $filename);
            $providerData->save();
        }
    }

    /**
     * after email enter log in email logs
     *
     * @param mixed $request
     * @param mixed $id (id of provider table)
     *
     * @return void
     */
    private function entryInEmailLogs($request, $id)
    {
        $receipientData = Provider::where('id', $id)->first();
        $adminId = Admin::select('id')->where('user_id', Auth::user()->id)->value('id');
        EmailLog::create([
            'provider_id' => $id,
            'role_id' => 1,
            'sent_tries' => 1,
            'admin_id' => $adminId,
            'recipient_name' => $receipientData->first_name,
            'email' => $receipientData->email,
            'email_template' => $request->contact_msg,
            'is_email_sent' => true,
            'create_date' => now(),
            'sent_date' => now(),
            'subject_name' => 'notification to provider',
            'action' => 2,
        ]);
    }

    /**
     * after sms send enter log in smslogs
     *
     * @param mixed $request
     * @param mixed $id (id of provider table)
     *
     * @return void
     */
    private function entryInSmsLogs($request, $id)
    {
        $receipientData = Provider::where('id', $id)->first();
        $adminId = Admin::where('user_id', Auth::user()->id)->value('id');
        SMSLogs::create(
            [
                'provider_id' => $id,
                'role_id' => 1,
                'sent_tries' => 1,
                'is_sms_sent' => 1,
                'action' => 2,
                'admin_id' => $adminId,
                'recipient_name' => $receipientData->first_name,
                'mobile_number' => $receipientData->mobile,
                'created_date' => now(),
                'sent_date' => now(),
                'sms_template' => $request->contact_msg,
            ]
        );
    }

    /**
     * send email
     *
     * @param mixed $email
     * @param mixed $message (user enter message)
     *
     * @return void
     */
    private function sendEmail($email, $message)
    {
        Mail::to($email)->send(new ContactProvider($message));
    }

    /**
     * send SMS
     *
     * @param mixed $enteredText (user enterED message)
     *
     * @return void
     */

    private function sendSMS($enteredText)
    {
        $sid = config('api.twilio_sid');
        $token = config('api.twilio_auth_token');
        $senderNumber = config('api.sender_number');

        $twilio = new Client($sid, $token);
        $twilio->messages
            ->create(
                '+91 99780 71802', // to
                [
                    'body' => "{$enteredText}",
                    'from' => $senderNumber,
                ]
            );
    }
}
