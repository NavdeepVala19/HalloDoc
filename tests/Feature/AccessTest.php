<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\AllUsers;
use App\Models\RoleMenu;
use App\Models\UserRoles;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class AccessTest extends TestCase
{
    private function adminLoggedIn()
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
        return $admin;
    }

    /**
     * Test successful create role with valid data for account type admin
     * @return void
     */
    // public function test_create_role_with_valid_data_for_account_type_admin()
    // {
    //     $admin = $this->adminLoggedIn();
    //     $response = $this->actingAs($admin)->postJson('/create-access', [
    //         'role_name' => '1',
    //         'role' => 'administrator',
    //         'menu_checkbox' => [2, 3]
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('admin.access.view')->assertSessionHas('accessOperation', 'New access created successfully!');
    // }

    /**
     * Test successful create role with valid data for account type provider
     * @return void
     */
    // public function test_create_role_with_valid_data_for_account_type_provider()
    // {
    //     $admin = $this->adminLoggedIn();
    //     $response = $this->actingAs($admin)->postJson('/create-access', [
    //         'role_name' => '2',
    //         'role' => 'dentist',
    //         'menu_checkbox' => [21, 22]
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('admin.access.view')->assertSessionHas('accessOperation', 'New access created successfully!');
    // }


    /**
     * Test successful create role with invalid data
     * @return void
     */
    public function test_create_role_with_invalid_data()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/create-access', [
            'role_name' => '2',
            'role' => '%$#$#%$%',
            'menu_checkbox' => []
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'menu_checkbox' => 'The menu checkbox field is required.',
            'role' => 'The role field must only contain letters and numbers.',
        ]);
    }


    /**
     * Test successful create role with valid data
     * @return void
     */
    public function test_create_role_with_empty_data()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/create-access', [
            'role' => '',
            'menu_checkbox' => ''
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'menu_checkbox' => 'The menu checkbox field is required.',
            'role' => 'The role field is required.',
        ]);
    }


    /**
     * Test successfull of user access page view
     * @return void
     */
    public function test_view_user_access_data()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->get('/user-access');

        $accessRecords = $response->getOriginalContent()->getData()['userAccessData']->items()[0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.access.userAccess');

        $this->assertTrue(array_key_exists('name', $accessRecords));
        $this->assertTrue(array_key_exists('first_name', $accessRecords));
        $this->assertTrue(array_key_exists('mobile', $accessRecords));
        $this->assertTrue(array_key_exists('status', $accessRecords));
        $this->assertTrue(array_key_exists('user_id', $accessRecords));
    }


    // public function test_filter_user_access_view_account_type(){

    //     $admin = $this->adminLoggedIn();
    //     $response = $this->actingAs($admin)->post('/user-access/filter',[
    //         'role_name'=> 'admin',
    //     ]);

    //     dd($response);
    //     // $accessRecords = $response->getOriginalContent()->getData()['userAccessDataFiltering']->items()[0]->getAttributes();
    //     // dd($accessRecords);

    //     $response->assertStatus(Response::HTTP_OK);

    //     // $this->assertTrue(array_key_exists('name', $accessRecords));
    //     // $this->assertTrue(array_key_exists('first_name', $accessRecords));
    //     // $this->assertTrue(array_key_exists('mobile', $accessRecords));
    //     // $this->assertTrue(array_key_exists('status', $accessRecords));
    //     // $this->assertTrue(array_key_exists('user_id', $accessRecords));
    // }


    public function test_view_edit_page_in_user_access(){
        $admin = $this->adminLoggedIn();

        $id = AllUsers::select('allusers.user_id')
            ->leftJoin('user_roles', 'user_roles.user_id','=', 'allusers.user_id')
            ->whereIn('user_roles.role_id', [1, 2])->value('user_id');

        $response = $this->actingAs($admin)->get("/user-access-edit/$id");

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * view account access page
     * @return void
     */

    public function test_view_account_access()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->get('/access');

        $access = $response->getOriginalContent()->getData()['roles'][0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.access.access');

        $this->assertTrue(array_key_exists('name', $access));;
        $this->assertTrue(array_key_exists('account_type', $access));
        $this->assertTrue(array_key_exists('id', $access));
    }


    /**
     * view create role page
     * @return void
     */

    public function test_view_create_role()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->get('/create-role');

        $role = $response->getOriginalContent()->getData()['menus'][0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.access.createRole');

        $this->assertTrue(array_key_exists('name', $role));
        $this->assertTrue(array_key_exists('account_type', $role));
        $this->assertTrue(array_key_exists('id', $role));
    }

    /**
     * delete account access 
     * @return void
     */
    // public function test_delete_role_access(){
    //     $admin = $this->adminLoggedIn();

    //     $id = Role::first()->id;
    //     $response = $this->actingAs($admin)->get("/delete-access/$id");

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('accessOperation', 'Access role deleted successfully!');
    // }


    /**
     * test successfull view edit access page
     * @return void
     */
    public function test_view_edit_access_page()
    {
        $admin = $this->adminLoggedIn();

        $id = Crypt::encrypt(Role::first()->id);
        $response = $this->actingAs($admin)->get("/edit-access/$id");

        $role = $response->getOriginalContent()->getData()['role']->getAttributes();
        $roleMenu = $response->getOriginalContent()->getData()['roleMenus'][0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.access.editAccess');

        $this->assertTrue(array_key_exists('name', $role));
        $this->assertTrue(array_key_exists('account_type', $role));
        $this->assertTrue(array_key_exists('id', $role));
        $this->assertTrue(array_key_exists('id', $roleMenu));
        $this->assertTrue(array_key_exists('role_id', $roleMenu));
        $this->assertTrue(array_key_exists('menu_id', $roleMenu));
    }

    /**
     *  edit role access
     * @return void
     */
    // public function test_edit_access()
    // {
    //     $admin = $this->adminLoggedIn();

    //     $roleId = Role::first()->id;
    //     $response = $this->actingAs($admin)->post("/edit-access-data", [
    //         'role' => 'supremeAdmin',
    //         'menu_checkbox' => [2, 5, 6, 8],
    //         'roleId' => $roleId,
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('admin.access.view')->assertSessionHas('accessOperation', 'Your Changes Are successfully Saved!');
    // }

    /**
     * test case successfull of delete access
     * @return void
     */
    // public function test_delete_access()
    // {
    //     $admin = $this->adminLoggedIn();

    //     $roleId = Role::first()->id;
    //     $response = $this->actingAs($admin)->get("/delete-access/$roleId");

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('accessOperation', 'Access role deleted successfully!');
    // }



}
