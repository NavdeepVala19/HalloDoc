<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminProfileForm;
use App\Models\Menu;
use App\Models\Provider;
use App\Models\Regions;
use App\Models\Role;
use App\Models\RoleMenu;
use App\Models\Roles;
use App\Services\UserAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AccessController extends Controller
{
    /**
     * Display the access page.
     *
     * @return \Illuminate\View\View
     */
    public function accessView()
    {
        $roles = Role::orderByDesc('id')->get();
        return view('adminPage.access.access', compact('roles'));
    }

    /**
     * Display the create role page.
     *
     * @return \Illuminate\View\View
     */
    public function createRoleView()
    {
        $menus = Menu::get();
        return view('adminPage.access.createRole', compact('menus'));
    }

    /**
     * Fetch roles data from the Menu table based on the given ID.
     *
     * @param int|null $id The ID of the account type (optional).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchRoles($id = null)
    {
        if ($id === 0) {
            $menus = Menu::get();
            return response()->json($menus);
        }
        if ($id === '1') {
            $menus = Menu::where('account_type', 'Admin')->get();
            return response()->json($menus);
        }
        if ($id === '2') {
            $menus = Menu::where('account_type', 'Physician')->get();
            return response()->json($menus);
        }
    }

    /**
     * Creating different Access for different roles
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing role information and menu checkboxes.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAccess(Request $request)
    {
        $request->validate([
            'role_name' => 'required',
            'role' => 'required',
            'menu_checkbox' => 'required',
        ]);
        if ($request->role_name === '1') {
            $roleId = Role::insertGetId(['name' => $request->role, 'account_type' => 'admin']);
        } elseif ($request->role_name === '2') {
            $roleId = Role::insertGetId(['name' => $request->role, 'account_type' => 'physician']);
        }

        foreach ($request->input('menu_checkbox') as $value) {
            RoleMenu::create([
                'role_id' => $roleId,
                'menu_id' => $value,
            ]);
        }
        return redirect()->route('admin.access.view')->with('accessOperation', 'New access created successfully!');
    }

    /**
     * Deletes a complete role.
     *
     * @param int|null $id The ID of the role to be deleted.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAccess($id = null)
    {
        Role::where('id', $id)->delete();
        return redirect()->back()->with('accessOperation', 'Access role deleted successfully!');
    }

    /**
     * Displays the edit Access Page with pre-filled data.
     *
     * @param int|null $id The ID of the role to be edited.
     *
     * @return \Illuminate\View\View
     */
    public function editAccess($id = null)
    {
        try {
            $roleId = Crypt::decrypt($id);

            $role = Role::where('id', $roleId)->first();
            $roleMenus = RoleMenu::where('role_id', $roleId)->get();
            $menus = Menu::where('account_type', $role->account_type)->get();
            return view('adminPage.access.editAccess', compact('role', 'roleMenus', 'menus'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * Edit Access of a role previously created.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing the role data.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editAccessData(Request $request)
    {
        $request->validate([
            'role_name' => 'required',
            'role' => 'required',
            'menu_checkbox' => 'required',
        ]);

        Role::where('id', $request->roleId)->update([
            'name' => $request->role,
            // 'account_type' => $request->role,
        ]);

        RoleMenu::where('role_id', $request->roleId)->delete();

        foreach ($request->input('menu_checkbox') as $value) {
            RoleMenu::create([
                'role_id' => $request->roleId,
                'menu_id' => $value,
            ]);
        }
        return redirect()->route('admin.access.view')->with('accessOperation', 'Your Changes Are successfully Saved!');
    }

    /**
     * listing of user access page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function userAccess(UserAccessService $userAccessService)
    {
        $userAccessData = $userAccessService->userAccessList();
        return view('adminPage.access.userAccess', compact('userAccessData'));
    }

    /**
     *  route admin to edit account page as per accountType(admin/provider)
     *
     * @param mixed $id (id of user table)
     *
     * @return mixed|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function userAccessEdit($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $userAccessRoleName = Roles::select('name')
                ->leftJoin('user_roles', 'user_roles.role_id', 'roles.id')
                ->where('user_roles.user_id', $id)
                ->get();
            if ($userAccessRoleName->value('name') === 'admin') {
                return redirect()->route('edit.admin.profile', ['id' => Crypt::encrypt($id)]);
            }
            $getProviderId = Provider::where('user_id', $id)->value('id');
            return redirect()->route('admin.edit.providers', ['id' => Crypt::encrypt($getProviderId)]);
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * filtering listing in user access page through ajax
     *
     * @param \Illuminate\Http\Request $request (account type(all/admin/provider))
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function filterUserAccessAccountTypeWise(Request $request, UserAccessService $userAccessService)
    {
        $userAccessDataFiltering = $userAccessService->filterAccountWise($request);
        $data = view('adminPage.access.userAccessFiltering')->with('userAccessDataFiltering', $userAccessDataFiltering)->render();

        return response()->json(['html' => $data]);
    }

    /**
     *same as above in mobile view
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function filterUserAccessAccountTypeWiseMobileView(Request $request, UserAccessService $userAccessService)
    {
        $userAccessDataFiltering = $userAccessService->filterAccountWise($request);
        $data = view('adminPage.access.userAccessFilterMobileView')->with('userAccessDataFiltering', $userAccessDataFiltering)->render();

        return response()->json(['html' => $data]);
    }

    /**
     * displaying create admin account page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminAccount()
    {
        $regions = Regions::get();
        return view('adminPage.createAdminAccount', compact('regions'));
    }

    /**
     * it stores data in admin ,users,allusers and make role_id '1' in user_roles
     *
     * @param \Illuminate\Http\Request
     *
     * @return mixed|\Illuminate\Http\RedirectResponse
     */

    public function createAdminAccount(AdminProfileForm $request, UserAccessService $userAccessService)
    {
        $userAccessService->createAdminAccount($request);
        return redirect()->route('admin.user.access')->with('successMessage', 'new admin account is created successfully');
    }

    /**
     * fetch state for admin account create through ajax
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */

    public function fetchRegionsForState()
    {
        $fetchedRegions = Regions::get();
        return response()->json($fetchedRegions);
    }

    /**
     *fetch roles for admin account create through ajax
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function fetchRolesForAdminAccountCreate()
    {
        $fetchedRoles = Role::select('id', 'name')->where('account_type', 'admin')->get();
        return response()->json($fetchedRoles);
    }
}
