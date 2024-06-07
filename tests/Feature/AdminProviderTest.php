<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Provider;
use App\Models\UserRoles;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;

class AdminProviderTest extends TestCase
{
    public function admin()
    {
        $adminId = UserRoles::where('role_id', 1)->first()->user_id;
        return User::where('id', $adminId)->first();
    }

    /**
     * Admin Provider page can be rendered.
     */
    public function test_admin_provider_page_can_be_rendered(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/admin-providers');

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.provider.adminProvider')
            ->assertViewHasAll([
                'providersData', 'onCallPhysicianIds'
            ]);
    }

    /**
     * Admin create new Provider page can be rendered.
     */
    public function test_admin_create_new_provider_page_can_be_rendered(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/admin-new-provider');

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.provider.adminNewProvider')
            ->assertViewHas('regions');
    }

    /**
     * Admin create new Provider with no data.
     */
    public function test_admin_create_new_provider_with_no_data(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin-create-new-provider', [
                'user_name' => '',
                'password' => '',
                'role' => '',
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'phone_number' => '',
                'medical_license' => '',
                'npi_number' => '',
                'region_id' => '',
                'address1' => '',
                'address2' => '',
                'city' => '',
                'select_state' => '',
                'zip' => '',
                'phone_number_alt' => '',
                'business_name' => '',
                'business_website' => '',
                'admin_notes' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'user_name' => 'Please enter User Name',
            'password' => 'Please enter Password',
            'first_name' => 'Please enter First Name',
            'last_name' => 'Please enter Last Name',
            'role' => 'Please select a Role',
            'email' => 'Please enter Email',
            'phone_number' => 'Please enter Phone Number',
            'medical_license' => 'Please enter medical license',
            'npi_number' => 'Please enter NPI number',
            'region_id' => 'Please select atleast one Region',
            'address1' => 'Please enter a address1',
            'address2' => 'Please enter a address2',
            'city' => 'Please enter a city',
            'select_state' => 'Please select state',
            'zip' => 'Please enter 6 digits zipcode',
            'phone_number_alt' => 'Please enter Alternate Phone Number',
            'business_name' => 'Please enter Business Name',
            'business_website' => 'Please enter Business Website Url',
            'admin_notes' => 'Please enter Admin Notes',
        ]);
    }

    /**
     * Admin create new Provider with invalid data.
     */
    public function test_admin_create_new_provider_with_invalid_data(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin-create-new-provider', [
                'user_name' => '1234',
                'password' => '12',
                'role' => 9,
                'first_name' => '2345()*)(',
                'last_name' => '*)&*',
                'email' => 'asdf1234@123.12',
                'phone_number' => '&%^$$',
                'medical_license' => 'asdfasdf',
                'npi_number' => 'asdasd',
                'region_id[]' => '12',
                'address1' => '1234&^(^',
                'address2' => '!@#$@3',
                'city' => '1234',
                'select_state' => '&%$',
                'zip' => '1@#4',
                'phone_number_alt' => '!@#$',
                'business_name' => '!%#@4',
                'business_website' => 'ASDF123423',
                'admin_notes' => '1234',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'user_name' => 'Please enter only Alphabets in User name',
            'password' => 'Please enter more than 8 characters',
            'first_name' => 'Please enter only Alphabets in First name',
            'last_name' => 'Please enter only Alphabets in Last name',
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'medical_license' => 'Please enter only numbers',
            'npi_number' => 'Please enter only numbers',
            'address1' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in address1.',
            'address2' => 'Please enter alphabets in address2 name.',
            'city' => 'Please enter alphabets in city.',
            'zip' => 'Please enter 6 digits zipcode',
            'business_name' => 'Please enter alphabets in business name.',
            'business_website' => 'Please enter a valid business website URL starting with https://www.',
            'admin_notes' => 'Please enter more than 5 character',
        ]);
    }

    /**
     * Admin create new Provider with valid data and existing email.
     */
    public function test_admin_create_new_provider_with_valid_data_and_existing_email(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin-create-new-provider', [
                'user_name' => 'desegoqa',
                'password' => 'anything',
                'role' => 1,
                'first_name' => 'Ethan',
                'last_name' => 'Noel',
                'email' => 'nohovezazi@mailinator.com',
                'phone_number' => '+11751349589',
                'medical_license' => '8831212111',
                'npi_number' => '3272345345',
                'region_id' => [1, 2],
                'address1' => '46 Fabien Drive',
                'address2' => 'Non velit mollit ip',
                'city' => 'Ipsum reprehenderit',
                'select_state' => 2,
                'zip' => 884615,
                'phone_number_alt' => '3231234123',
                'business_name' => 'McKenzie Kent',
                'business_website' => 'https://www.nodus.org.au',
                'admin_notes' => 'Porro ullamco magna',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'email' => 'The email has already been taken.'
        ]);
    }

    /**
     * Admin create new Provider with valid data and new email.
     */
    public function test_admin_create_new_provider_with_valid_data_and_new_email(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/admin-create-new-provider', [
                'user_name' => 'desegoqa',
                'password' => 'anything',
                'role' => 16,
                'first_name' => 'Ethan',
                'last_name' => 'Noel',
                'email' => fake()->unique()->email(),
                'phone_number' => '+11751349589',
                'medical_license' => '8831212111',
                'npi_number' => '3272345345',
                'region_id' => [1, 2],
                'address1' => '46 Fabien Drive',
                'address2' => 'Non velit mollit ip',
                'city' => 'Ipsum reprehenderit',
                'select_state' => 2,
                'zip' => 884615,
                'phone_number_alt' => '3231234123',
                'business_name' => 'McKenzie Kent',
                'business_website' => 'https://www.nodus.org.au',
                'admin_notes' => 'Porro ullamco magna',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('message', 'account is created');
    }

    // Admin edit provider page can be rendered
    public function test_admin_edit_provider_page_can_be_rendered()
    {
        $admin = $this->admin();

        $providerId = Provider::orderBy('id', 'desc')->first()->id;

        $id = Crypt::encrypt($providerId);

        $response = $this->actingAs($admin)->get('/admin-edit-provider/{' . $id . '}');

        $response->assertStatus(Response::HTTP_OK);
    }

    // Admin edit proivders password with no data
    public function test_admin_edit_providers_password_with_no_data()
    {
        $admin = $this->admin();

        $providerId = Provider::orderBy('id', 'desc')->first()->id;

        $id = Crypt::encrypt($providerId);

        $response = $this->actingAs($admin)->postJson('/admin-provider-updated-accounts/{' . $id . '}', [
            'password' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'password' => 'The password field is required.',
            ]);
    }

    // Admin edit proivders password with invalid data
    public function test_admin_edit_providers_password_with_invalid_data()
    {
        $admin = $this->admin();

        $providerId = Provider::orderBy('id', 'desc')->first()->id;

        $id = Crypt::encrypt($providerId);

        $response = $this->actingAs($admin)->postJson('/admin-provider-updated-accounts/{' . $id . '}', [
            'password' => 'asdfas',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'password' => 'The password field must be at least 8 characters.',
            ]);
    }

    // Admin edit proivders password with valid data
    public function test_admin_edit_providers_password_with_valid_data()
    {
        $admin = $this->admin();

        $id = Provider::orderBy('user_id', 'desc')->first()->id;

        $response = $this->actingAs($admin)
            ->postJson("/admin-provider-updated-accounts/$id", [
                'id' => $id,
                'password' => 'newPassword',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('message', 'account information is updated');
    }

    // Admin edit proivders username with no data
    public function test_admin_edit_providers_account_information_with_no_data()
    {
        $admin = $this->admin();

        $id = Provider::orderBy('id', 'desc')->first()->id;

        $response = $this->actingAs($admin)
            ->postJson("/admin-provider-updated-accounts/$id", [
                'user_name' => '',
                'status_type' => '',
                'role' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'user_name' => 'The user name field is required.',
                'status_type' => 'The status type field is required.',
                'role' => 'The role field is required.',
            ]);
    }

    // Admin edit proivders username with invalid data
    public function test_admin_edit_providers_account_information_with_invalid_data()
    {
        $admin = $this->admin();

        $id = Provider::orderBy('id', 'desc')->first()->id;

        $response = $this->actingAs($admin)
            ->postJson("/admin-provider-updated-accounts/$id", [
                'user_name' => '*^^(*',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'user_name' => 'The user name field must only contain letters.',
            ]);
    }

    // Admin edit proivders username with valid data
    public function test_admin_edit_providers_account_information_with_valid_data()
    {
        $admin = $this->admin();

        $id = Provider::orderBy('id', 'desc')->first()->id;

        $response = $this->actingAs($admin)
            ->postJson("/admin-provider-updated-accounts/$id", [
                'id' => $id,
                'user_name' => 'newName',
                'status_type' => 'active',
                'role' => '17',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('message', 'account information is updated');
    }

    // Admin edit providers physician information with no data
    public function test_admin_edit_providers_physician_information_with_no_data()
    {
        $admin = $this->admin();

        $id = Provider::orderBy('id', 'desc')->first()->id;

        $response = $this->actingAs($admin)
            ->postJson("/admin-provider-updated-infos/$id", [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'phone_number' => '',
                'medical_license' => '',
                'npi_number' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'first_name' => 'The first name field is required.',
                'last_name' => 'The last name field is required.',
                'email' => 'The email field is required.',
                'phone_number' => 'The phone number field is required.',
                'medical_license' => 'The medical license field is required.',
                'npi_number' => 'The npi number field is required.',
            ]);
    }

    // Admin edit providers physician information with invalid data
    public function test_admin_edit_providers_physician_information_with_invalid_data()
    {
        $admin = $this->admin();

        $id = Provider::orderBy('id', 'desc')->first()->id;

        $response = $this->actingAs($admin)
            ->postJson("/admin-provider-updated-infos/$id", [
                'first_name' => '12',
                'last_name' => '!@#$!@',
                'email' => 'asdf!@1234.123',
                'phone_number' => '1234123423',
                'medical_license' => '1234',
                'npi_number' => '1234',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'first_name' => 'The first name field must be at least 3 characters.',
                'last_name' => 'The last name field must only contain letters.',
                'email' => 'The email field format is invalid.',
                'medical_license' => 'The medical license field must have at least 10 digits.',
                'npi_number' => 'The npi number field must have at least 10 digits.',
            ]);
    }

    // Admin edit providers physician information with valid data
    public function test_admin_edit_providers_physician_information_with_valid_data()
    {
        $admin = $this->admin();

        $id = Provider::orderBy('id', 'desc')->first()->id;

        $response = $this->actingAs($admin)
            ->postJson("/admin-provider-updated-infos/$id", [
                'first_name' => 'test',
                'last_name' => 'case',
                'email' => fake()->unique()->email(),
                'phone_number' => '1234123423',
                'medical_license' => '1234567890',
                'npi_number' => '1234567890',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('message', 'Physician information is updated');
    }

    // Admin edit providers mailing information with no data
    public function test_admin_edit_providers_mailing_information_with_no_data()
    {
        $admin = $this->admin();

        $id = Provider::orderBy('id', 'desc')->first()->id;

        $response = $this->actingAs($admin)
            ->postJson("/admin-provider-updated-mail-infos/$id", [
                'address1' => '',
                'address2' => '',
                'city' => '',
                'zip' => '',
                'regions' => '',
                'alt_phone_number' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'address1' => 'The address1 field is required.',
                'address2' => 'The address2 field is required.',
                'city' => 'The city field must be at least 2 characters.',
                'zip' => 'The zip field must be 6 digits.',
                'regions' => 'The regions field is required.',
                'alt_phone_number' => 'The alt phone number field is required.',
            ]);
    }

    // Admin edit providers mailing information with invalid data
    public function test_admin_edit_providers_mailing_information_with_invalid_data()
    {
        $admin = $this->admin();

        $id = Provider::orderBy('id', 'desc')->first()->id;

        $response = $this->actingAs($admin)
            ->postJson("/admin-provider-updated-mail-infos/$id", [
                'address1' => '!@#$',
                'address2' => '!@#4',
                'city' => '!@#$',
                'zip' => '123',
                'regions' => '123',
                'alt_phone_number' => '123412',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'address1' => 'The address1 field format is invalid.',
                'address2' => 'The address2 field format is invalid.',
                'city' => 'The city field format is invalid.',
                'zip' => 'The zip field must be 6 digits.',
            ]);
    }

    // Admin edit providers mailing information with valid data
    public function test_admin_edit_providers_mailing_information_with_valid_data()
    {
        $admin = $this->admin();

        $id = Provider::orderBy('id', 'desc')->first()->id;

        $response = $this->actingAs($admin)
            ->postJson("/admin-provider-updated-mail-infos/$id", [
                'address1' => 'test case address1',
                'address2' => 'test case address',
                'city' => 'testCity',
                'zip' => '123456',
                'regions' => '1',
                'alt_phone_number' => '1234567890',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('message', 'Mailing and Billing information is updated');
    }

    // Admin edit providers profile information with no data
    public function test_admin_edit_providers_profile_information_with_no_data()
    {
        $admin = $this->admin();

        $id = Provider::orderBy('id', 'desc')->first()->id;

        $response = $this->actingAs($admin)
            ->postJson("/admin-provider-updated-profile-data/$id", [
                'business_name' => '',
                'provider_photo' => '',
                'business_website' => '',
                'admin_notes' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'business_name' => 'The business name field is required.',
            ]);
    }

    // Admin edit providers profile information with invalid data
    public function test_admin_edit_providers_profile_information_with_invalid_data()
    {
        $admin = $this->admin();

        $id = Provider::orderBy('id', 'desc')->first()->id;

        $response = $this->actingAs($admin)
            ->postJson("/admin-provider-updated-profile-data/$id", [
                'business_name' => '!@#4',
                'provider_photo' => '!@34',
                'business_website' => '!@#4',
                'admin_notes' => '!@34',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'business_name' => 'The business name field format is invalid.',
                'provider_photo' => 'The provider photo field must be a file.',
                'business_website' => 'The business website field must be a valid URL.',
                'admin_notes' => 'The admin notes field must be at least 5 characters.',
            ]);
    }

    // Admin edit providers profile information with valid data
    public function test_admin_edit_providers_profile_information_with_valid_data()
    {
        $admin = $this->admin();

        $id = Provider::orderBy('id', 'desc')->first()->id;

        $response = $this->actingAs($admin)
            ->postJson("/admin-provider-updated-profile-data/$id", [
                'business_name' => 'TestBusiness',
                'business_website' => 'https://www.testbusiness.com',
                'admin_notes' => 'test note added through unit test case.',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('message', 'Provider Profile information is updated');
    }
}
