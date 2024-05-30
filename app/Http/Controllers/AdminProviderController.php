<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProviderForm;
use App\Models\AllUsers;
use App\Models\PhysicianLocation;
use App\Models\PhysicianRegion;
use App\Models\Provider;
use App\Models\Regions;
use App\Models\Role;
use App\Models\Users;
use App\Services\AdminProviderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AdminProviderController extends Controller
{
    /**
     * listing of providersname,status,role,call status
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function readProvidersInfo(AdminProviderService $adminProviderService)
    {
        try {
            $providers = $adminProviderService->providersList();
            $onCallPhysicianIds = $providers['onCallPhysicianIds'];
            $providersData = $providers['providersData'];
            return view('/adminPage/provider/adminProvider', compact('providersData', 'onCallPhysicianIds'));
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }

    /**
     * filtering of physician by region through ajax and it lists providersname,status,role,call status
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */

    public function filterPhysicianThroughRegions(Request $request, AdminProviderService $adminProviderService)
    {
        $providers = $adminProviderService->filterProviderList($request);
        $onCallPhysicianIds = $providers['onCallPhysicianIds'];
        $providersData = $providers['providersData'];

        $data = view('/adminPage/provider/adminProviderFilterData')->with(['providersData' => $providersData, 'onCallPhysicianIds' => $onCallPhysicianIds])->render();
        return response()->json(['html' => $data]);
    }

    /**
     * perform filtering of physician by region through ajax and it lists providername,status,role,call status in mobile view
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function filterPhysicianThroughRegionsMobileView(Request $request, AdminProviderService $adminProviderService)
    {
        $providers = $adminProviderService->filterProviderList($request);
        $onCallPhysicianIds = $providers['onCallPhysicianIds'];
        $providersData = $providers['providersData'];

        $data = view('/adminPage/provider/adminProviderFilterMobileData')->with(['providersData' => $providersData, 'onCallPhysicianIds' => $onCallPhysicianIds])->render();
        return response()->json(['html' => $data]);
    }

    /**
     * this function perform send email and sms to provider
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function sendMailToContactProvider(Request $request, $id, AdminProviderService $adminProviderService)
    {
        $request->validate([
            'contact_msg' => 'required|min:2|max:100',
        ]);
        try {
            $adminProviderService->contactToProvider($request, $id);
            return redirect()->route('admin.providers.list')->with('message', 'Your message has been sent successfully.');
        } catch (\Throwable $th) {
            return view('errors.500');
        }
    }

    /**
     * this function perform stop notification through ajax
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function stopNotifications(Request $request)
    {
        $stopNotification = Provider::find($request->stopNotificationsCheckId);
        $stopNotification->update(['is_notifications' => $request->is_notifications]);
    }

    /**
     * display create new provider account page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function newProvider()
    {
        $regions = Regions::get();
        return view('/adminPage/provider/adminNewProvider', compact('regions'));
    }

    /**
     * it stores data in provider,user,allusers and physician_region table
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminCreateNewProvider(ProviderForm $request, AdminProviderService $adminProviderService)
    {
        $adminProviderService->createNewProvider($request);
        return redirect()->route('admin.providers.list')->with('message', 'account is created');
    }

    /**
     * display edit provider page
     *
     * @param mixed $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function editProvider($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $getProviderData = Provider::with('users', 'role', 'Regions')->where('id', $id)->first();
            return view('/adminPage/provider/adminEditProvider', compact('getProviderData'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * it update password and username in users table
     * it update role and status in provider table
     * and also update status in allusers table
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProviderAccountInfo(Request $request, $id)
    {
        // update data of providers in users table
        $getUserIdFromProvider = Provider::where('id', $id)->value('user_id');
        $updateProviderInfoUsers = Users::where('id', $getUserIdFromProvider)->first();

        if ($request->has('password')) {
            $request->validate([
                'password' => 'required|min:8|max:50|regex:/^\S(.*\S)?$/',
            ]);
            $updateProviderInfoUsers->password = Hash::make($request->password);
            $updateProviderInfoUsers->save();
        } else {
            $request->validate([
                'user_name' => 'required|alpha|min:3|max:40',
            ]);

            $updateProviderInfoUsers->username = $request->user_name;
            $updateProviderInfoUsers->save();

            $getProviderData = Provider::where('id', $id)->first();
            $getProviderData->status = $request->status_type;
            $getProviderData->role_id = $request->role;
            $getProviderData->save();

            AllUsers::where('user_id', $getUserIdFromProvider)->update([
                'status' => $request->status_type,
            ]);
        }

        return back()->with('message', 'account information is updated');
    }

    /**
     * update firstname,lastname,email,mobile,medical license and npi number in provider table
     * update firstname,lastname,email,mobile in allusers table
     * update email and mobile in users table
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function providerInfoUpdate(Request $request, $id, AdminProviderService $adminProviderService)
    {
        $request->validate([
            'first_name' => 'required|min:3|max:15|alpha',
            'last_name' => 'required|min:3|max:15|alpha',
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required',
            'medical_license' => 'required|numeric|max_digits:10|min_digits:10',
            'npi_number' => 'required|numeric|min_digits:10|max_digits:10',
        ]);

        $adminProviderService->updatePhysicianInformation($request, $id);
        return back()->with('message', 'Physician information is updated');
    }

    /**
     * update address1,address2,city,zipcode,state,alternate phone number in provider table
     * update address1,city,zipcode in allusers table
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */

    public function providerMailInfoUpdate(Request $request, $id, AdminProviderService $adminProviderService)
    {
        $request->validate([
            'address1' => 'required|min:2|max:50',
            'address2' => 'required|min:2|max:30',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'zip' => 'digits:6',
            'alt_phone_number' => 'required|regex:/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/',
        ]);

        $adminProviderService->updatePhysicianMailInformation($request, $id);
        return back()->with('message', 'Mailing and Billing information is updated');
    }

    /**
     *  update businessname,website,adminnotes,provider_photo in provider table
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function providerProfileUpdate(Request $request, $id, AdminProviderService $adminProviderService)
    {
        $request->validate([
            'business_name' => 'required|min:3|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'provider_photo' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'business_website' => 'nullable|url|max:40|min:10',
            'admin_notes' => 'nullable|min:5|max:200|',
        ]);

        $adminProviderService->updateProviderProfile($request, $id);
        return back()->with('message', 'Provider Profile information is updated');
    }

    /**
     * update onboarding document in local storage
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */

    public function providerDocumentsUpdate(Request $request, $id, AdminProviderService $adminProviderService)
    {
        $request->validate([
            'independent_contractor' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'background_doc' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'hipaa_docs' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
            'non_disclosure_doc' => 'nullable|file|mimes:jpg,png,jpeg,pdf,doc|max:2048',
        ]);

        $adminProviderService->updateProviderDocumentsUpdate($request, $id);
        return back()->with('message', 'Document is uploaded');
    }

    /**
     * delete(softDelete) provider account from allusers,physicianRegion,users and provider table
     *
     * @param mixed $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteProviderAccount($id)
    {
        // soft delete
        $providerInfo = Provider::where('id', $id)->first();

        AllUsers::where('user_id', $providerInfo->user_id)->delete();
        Users::where('id', $providerInfo->user_id)->delete();
        PhysicianRegion::where('provider_id', $id)->delete();
        Provider::where('id', $id)->delete();

        return redirect()->route('admin.providers.list')->with('message', 'account is deleted');
    }

    /**
     * fetch role name from role table and display through ajax
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function fetchRolesName()
    {
        $fetchRoleName = Role::select('id', 'name')->where('account_type', 'physician')->get();
        return response()->json($fetchRoleName);
    }

    /**
     * Show Provider Location
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function providerLocations()
    {
        return view('adminPage/provider/providerLocation');
    }

    public function providerMapLocations()
    {
        $providers = PhysicianLocation::all();
        $locations = $providers->map(function ($provider) {
            return [
                'latitude' => $provider->latitude,
                'longitude' => $provider->longitude,
            ];
        });
        return response()->json(['locations' => $locations->toArray()]);
    }
}
