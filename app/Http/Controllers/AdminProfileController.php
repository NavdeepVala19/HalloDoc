<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Services\AdminCreateRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    /**
     * this page will show when admin edit their profile through user access page
     *
     * @param mixed $id ( $id is the id of users table)
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminProfile($id, AdminCreateRequestService $adminCreateRequestService)
    {
        try {
            $id = Crypt::decrypt($id);
            $adminProfileData = $adminCreateRequestService->adminProfile($id);
            return view('adminPage/adminProfile', compact('adminProfileData'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * this page will show admin profile edit and admin can route to this page from any page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminProfilePage(AdminCreateRequestService $adminCreateRequestService)
    {
        $adminData = Auth::user();
        $adminProfileData = $adminCreateRequestService->adminProfile($adminData->id);
        return view('adminPage/adminProfile', compact('adminProfileData'));
    }

    /**
     * it will update password in users table
     *
     * @param \Illuminate\Http\Request $request  (the input which is enter by user(admin))
     * @param mixed $id (id of users table)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminChangePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8|max:30',
        ]);
        $updatePassword = Users::where('id', $id)->first();
        $updatePassword->password = Hash::make($request->password);

        return back()->with('message', 'Your password is updated successfully');
    }

    /**
     * it will update firstname,lastname,email,mobile in allusers and admin table
     *
     * @param \Illuminate\Http\Request $request  (the input which is enter by user)
     * @param mixed $id (id of users table)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminInfoUpdate(Request $request, $id, AdminCreateRequestService $adminCreateRequestService)
    {
        $request->validate([
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'required|min:2|max:30',
            'email' => 'required|email|min:2|max:40|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'confirm_email' => 'required|min:2|max:40|email|regex:/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/',
            'phone_number' => 'required',
        ]);

        $adminCreateRequestService->updateAdminInformation($request, $id);
        return back()->with('message', 'Your Administration Information is updated successfully');
    }

    /**
     * it will update address1,address2 ,city,zip ,state,alternate mobile in admin and allusers table
     *
     * @param \Illuminate\Http\Request $request  (the input which is enter by user)
     * @param mixed $id (id of users table)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminMailInfoUpdate(Request $request, $id, AdminCreateRequestService $adminCreateRequestService)
    {
        $request->validate([
            'address1' => 'required|min:2|max:50',
            'address2' => 'min:2|max:30|regex:/^[a-zA-Z ,_-]+?$/',
            'city' => 'min:2|max:30|regex:/^[a-zA-Z ]+?$/',
            'zip' => 'digits:6',
            'alt_mobile' => 'required|min_digits:10|max_digits:10',
        ]);

        $adminCreateRequestService->updateAdminMailInformation($request, $id);
        return back()->with('message', 'Your Mailing and Billing Information is updated successfully');
    }
}
