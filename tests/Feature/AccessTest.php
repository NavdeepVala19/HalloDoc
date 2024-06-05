<?php

namespace Tests\Feature;

use App\Models\AllUsers;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;

class AccessTest extends TestCase
{
    public function admin()
    {
        $adminId = UserRoles::where('role_id', 1)->first()->user_id;
        return User::where('id', $adminId)->first();
    }

    // user access page can be rendered
    public function test_user_access_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/user-access');

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.access.userAccess')
            ->assertViewHas('userAccessData');
    }

    // admin create new admin page can be rendered
    public function test_admin_create_new_admin_page_can_be_rendered()
    {

        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/admin-create-new-admin');

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.createAdminAccount')
            ->assertViewHas('regions');
    }


    // admin create new admin with no data
    public function test_admin_create_new_admin_with_no_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin-new-account-created', [
                'user_name' => '',
                'password' => '',
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'confirm_email' => '',
                'phone_number' => '',
                'address1' => '',
                'address2' => '',
                'city' => '',
                'zip' => '',
                'alt_mobile' => '',
                'role' => '',
                'state' => '',
                'region_id' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'user_name' => 'Please enter User Name',
            'password' => 'Please enter Password',
            'first_name' => 'Please enter First Name',
            'last_name' => 'Please enter Last Name',
            'email' => 'Please enter Email',
            'confirm_email' => 'Please enter Email',
            'phone_number' => 'Please enter Phone Number',
            'address1' => 'Please enter a address1',
            'address2' => 'Please enter a address2',
            'city' => 'Please enter more than 2 alphabets in city',
            'zip' => 'Please enter 6 digits zipcode',
            'alt_mobile' => 'Please enter Alternate Phone Number',
            'role' => 'Please select a Role',
            'state' => 'Please select state',
            'region_id' => 'Please select atleast one Region',
        ]);
    }

    // admin create new admin with invalid data
    public function test_admin_create_new_admin_with_invalid_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin-new-account-created', [
                'user_name' => '!@#$@',
                'password' => '!@',
                'first_name' => '!@$@!4',
                'last_name' => 'as',
                'email' => 'ASD@1234.123',
                'confirm_email' => 'asdf@as1234.123',
                'phone_number' => 'asd',
                'address1' => 'ASD !@#$',
                'address2' => '!@#4',
                'city' => '!@#$@13',
                'zip' => '1234',
                'alt_mobile' => 'asdas',
                'role' => 'Asd',
                'state' => '!@#',
                'region_id' => 'ASD',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'user_name' => 'Please enter only Alphabets in User name',
            'password' => 'Please enter more than 8 characters',
            'first_name' => 'Please enter only Alphabets in First name',
            'last_name' => 'Please enter more than 3 Alphabets',
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'confirm_email' => 'Confirm Email should be same as Email',
            'address1' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in address1.',
            'address2' => 'Please enter alphabets in address2 name.',
            'city' => 'Please enter alphabets in city',
            'zip' => 'Please enter 6 digits zipcode',
            'alt_mobile' => 'Please enter exactly 10 digits in Alternate Phone Number',
        ]);
    }

    // admin create new admin with valid data and new email
    public function test_admin_create_new_admin_with_valid_data_and_new_email()
    {
        $admin = $this->admin();

        $email = fake()->unique()->email();

        $response = $this->actingAs($admin)
            ->postJson('/admin-new-account-created', [
                'user_name' => 'TestAdmin',
                'password' => 'testadmin',
                'first_name' => 'Test',
                'last_name' => 'Admin',
                'email' => $email,
                'confirm_email' => $email,
                'phone_number' => '1234567890',
                'address1' => 'new address, test way',
                'address2' => 'opposite of test hospital',
                'city' => 'dreamCity',
                'zip' => '123456',
                'alt_mobile' => '2345678901',
                'role' => '1',
                'state' => 1,
                'region_id' => [1, 2],
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('admin.user.access');
    }

    // admin create new admin with valid data and existing email
    public function test_admin_create_new_admin_with_valid_data_and_existing_email()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin-new-account-created', [
                'user_name' => 'TestAdmin',
                'password' => 'testadmin',
                'first_name' => 'Test',
                'last_name' => 'Admin',
                'email' => 'admin@mail.com',
                'confirm_email' => 'admin@mail.com',
                'phone_number' => '1234567890',
                'address1' => 'new address, test way',
                'address2' => 'opposite of test hospital',
                'city' => 'dreamCity',
                'zip' => '123456',
                'alt_mobile' => '2345678901',
                'role' => '1',
                'state' => 1,
                'region_id' => [1, 2],
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'email' => 'The email has already been taken.',
            ]);
    }

    // ------------------- NOT WORKING -------------------
    // admin profile update page can be rendered
    // public function test_admin_profile_update_page_can_be_rendered()
    // {
    //     $admin = $this->admin();

    //     $userRolesId = UserRoles::where('role_id', 1)->orderBy('id', 'desc')->value('user_id');
    //     $adminId = AllUsers::where('user_id', $userRolesId)->first()->id;
    //     $id = Crypt::encrypt($adminId);

    //     $response = $this->actingAs($admin)->get('/admin-profile-update/{' . $id . '}');

    //     $response->assertStatus(Response::HTTP_OK)
    //         ->assertViewIs('adminPage/adminProfile')
    //         ->assertViewHas('adminProfileData');
    // }
    // ---------------------------------------------------

    /**
     * admin update profile password with empty data
     *
     * @return void
     */
    public function test_admin_update_profile_password_with_empty_data(): void
    {
        $admin = $this->admin();

        $userId = UserRoles::where('role_id', 1)->orderBy('id', 'desc')->value('user_id');

        $response = $this->actingAs($admin)
            ->postJson("/admin-update-password/$userId", [
                'password' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'password' => 'The password field is required.'
            ]);
    }

    /**
     * admin update profile password with invalid data
     *
     * @return void
     */
    public function test_admin_update_profile_password_with_invalid_data(): void
    {
        $admin = $this->admin();

        $userId = UserRoles::where('role_id', 1)->orderBy('id', 'desc')->value('user_id');

        $response = $this->actingAs($admin)
            ->postJson("/admin-update-password/$userId", [
                'password' => 'asd',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'password' => 'The password field must be at least 8 characters.'
            ]);
    }

    /**
     * admin update profile password with valid data
     *
     * @return void
     */
    public function test_admin_update_profile_password_with_valid_data(): void
    {
        $admin = $this->admin();

        $userId = UserRoles::where('role_id', 1)->orderBy('id', 'desc')->value('user_id');

        $response = $this->actingAs($admin)
            ->postJson("/admin-update-password/$userId", [
                'password' => 'admin@mail.com',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('message', 'Your password is updated successfully');
    }

    /**
     * admin update administrator information with valid data
     *
     * @return void
     */
    public function test_admin_update_administrator_information_with_valid_data(): void
    {
        $admin = $this->admin();

        $userId = UserRoles::where('role_id', 1)->orderBy('id', 'desc')->value('user_id');

        $email = fake()->unique()->email();

        $response = $this->actingAs($admin)
            ->postJson("/admin-info-updates/$userId", [
                'first_name' => 'Navdeep',
                'last_name' => 'Vala',
                'email' => $email,
                'confirm_email' => $email,
                'phone_number' => '1478523690',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('message', 'Your Administration Information is updated successfully');
    }

    /**
     * admin update administrator information with invalid data
     *
     * @return void
     */
    public function test_admin_update_administrator_information_with_invalid_data(): void
    {
        $admin = $this->admin();

        $userId = UserRoles::where('role_id', 1)->orderBy('id', 'desc')->value('user_id');

        $response = $this->actingAs($admin)
            ->postJson("/admin-info-updates/$userId", [
                'first_name' => '!@#$!2',
                'last_name' => 'q',
                'email' => 'admin@m343ail.com',
                'confirm_email' => 'admin@mai43l.com',
                'phone_number' => '147852360',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'first_name' => 'The first name field must only contain letters.',
                'last_name' => 'The last name field must be at least 2 characters.',
                'email' => 'The email field format is invalid.',
                'confirm_email' => 'The confirm email field format is invalid.',
            ]);
    }

    /**
     * admin update administrator information with empty data
     *
     * @return void
     */
    public function test_admin_update_administrator_information_with_empty_data(): void
    {
        $admin = $this->admin();

        $userId = UserRoles::where('role_id', 1)->orderBy('id', 'desc')->value('user_id');

        $response = $this->actingAs($admin)
            ->postJson("/admin-info-updates/$userId", [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'confirm_email' => '',
                'phone_number' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'first_name' => 'The first name field is required.',
                'last_name' => 'The last name field is required.',
                'email' => 'The email field is required.',
                'confirm_email' => 'The confirm email field is required.',
                'phone_number' => 'The phone number field is required.',
            ]);
    }

    /**
     * admin update mailing information with valid data
     *
     * @return void
     */
    public function test_admin_update_mailing_information_with_valid_data(): void
    {
        $admin = $this->admin();

        $userId = UserRoles::where('role_id', 1)->orderBy('id', 'desc')->value('user_id');

        $response = $this->actingAs($admin)
            ->postJson("/admin-mail-updates/$userId", [
                'address1' => 'Ambuja Cement',
                'address2' => 'North Colony',
                'city' => 'Kodinar',
                'select_state' => '1',
                'zip' => '362715',
                'alt_mobile' => '1234567890',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('message', 'Your Mailing and Billing Information is updated successfully');
    }

    /**
     * admin update mailing information with invalid data
     *
     * @return void
     */
    public function test_admin_update_mailing_information_with_invalid_data(): void
    {
        $admin = $this->admin();

        $userId = UserRoles::where('role_id', 1)->orderBy('id', 'desc')->value('user_id');

        $response = $this->actingAs($admin)
            ->postJson("/admin-mail-updates/$userId", [
                'address1' => 'Ambuja Cement !@#$23',
                'address2' => 'North Colony !@#$',
                'city' => 'Kodinar !@#$',
                'select_state' => '1 !@3',
                'zip' => '36271512',
                'alt_mobile' => '123456789012',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'address1' => 'The address1 field format is invalid.',
                'address2' => 'The address2 field format is invalid.',
                'city' => 'The city field format is invalid.',
                'zip' => 'The zip field must be 6 digits.',
                'alt_mobile' => 'The alt mobile field must not have more than 10 digits.',
            ]);
    }

    /**
     * admin update mailing information with empty data
     *
     * @return void
     */
    public function test_admin_update_mailing_information_with_empty_data(): void
    {
        $admin = $this->admin();

        $userId = UserRoles::where('role_id', 1)->orderBy('id', 'desc')->value('user_id');

        $response = $this->actingAs($admin)
            ->postJson("/admin-mail-updates/$userId", [
                'address1' => '',
                'address2' => '',
                'city' => '',
                'select_state' => '',
                'zip' => '',
                'alt_mobile' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'address1' => 'The address1 field is required.',
                'address2' => 'The address2 field must be at least 2 characters.',
                'city' => 'The city field must be at least 2 characters.',
                'zip' => 'The zip field must be 6 digits.',
                'alt_mobile' => 'The alt mobile field is required.',
            ]);
    }

    // Account access page can be rendered
    public function test_account_access_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/access');

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.access.access')
            ->assertViewHas('roles');
    }

    // Create role page can be rendered
    public function test_create_role_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/create-role');

        $data = $response->getOriginalContent()->getData()['menus'][0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.access.createRole');

        $this->assertTrue(array_key_exists('id', $data));
        $this->assertTrue(array_key_exists('name', $data));
        $this->assertTrue(array_key_exists('account_type', $data));
    }

    /**
     * create role with valid data
     * @return void
     */
    public function test_create_role_with_valid_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->postJson('/create-access', [
            'role_name' => '1',
            'role' => 'newAccount',
            'menu_checkbox' => [1, 2, 3, 4],
        ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('admin.access.view')
            ->assertSessionHas('accessOperation', 'New access created successfully!');
    }

    /**
     * create role with invalid data
     * @return void
     */
    public function test_create_role_with_invalid_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/create-access', [
                'role' => 'test account name new created',
                'role_name' => '3',
                'menu_checkbox' => [40, 41]
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'role' => 'The role field must not be greater than 20 characters.',
                'role_name' => 'The selected role name is invalid.',
            ]);
    }

    /**
     * create role with empty data
     * @return void
     */
    public function test_create_role_with_empty_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/create-access', [
                'role' => '',
                'role_name' => '',
                'menu_checkbox' => ''
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'role' => 'The role field is required.',
            'role_name' => 'The role name field is required.',
            'menu_checkbox' => 'The menu checkbox field is required.',
        ]);
    }
}
