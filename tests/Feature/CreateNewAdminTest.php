<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use Symfony\Component\HttpFoundation\Response;

class CreateNewAdminTest extends TestCase
{
    /**
     * create new admin with valid data
     * @return void
     */
    public function test_admin_create_new_admin_with_valid_data(): void
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();

        $email = fake()->unique()->email();
        $response = $this->actingAs($admin)->postJson('/admin-new-account-created', [
            'user_name' => fake()->unique()->firstName(),
            'password' => 'Duran@mailinator.com',
            'role' => '1',
            'first_name' => fake()->unique()->firstName(),
            'last_name' => fake()->unique()->lastName(),
            'email' => $email,
            'confirm_email' => $email,
            'phone_number' => fake()->unique()->phoneNumber(),
            'region_id' => [1,3,4],
            'address1' => '138 South Milton Boulevard',
            'address2' => 'Nulla tenetur archit',
            'city' => 'Amet temporibus min',
            'state' => '3',
            'zip' => '832033',
            'alt_mobile' => '5735225445',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('admin.user.access')->assertSessionHas('successMessage', 'new admin account is created successfully');
    }


    /**
     * create new admin with valid data and existing email
     * @return void
     */
    public function test_admin_create_new_admin_with_valid_data_and_existing_email(): void
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();

        $response = $this->actingAs($admin)->postJson('/admin-new-account-created', [
            'user_name' => 'Mariam',
            'password' => 'Duran@mailinator.com',
            'role' => '1',
            'first_name' => 'Mariam',
            'last_name' => 'Duran',
            'email' => 'admin@mail.com',
            'confirm_email' => 'admin@mail.com',
            'phone_number' => '+11886974119',
            'region_id' => [1, 3, 4],
            'address1' => '138 South Milton Boulevard',
            'address2' => 'Nulla tenetur archit',
            'city' => 'Amet temporibus min',
            'state' => '3',
            'zip' => '832033',
            'alt_mobile' => '5735225445',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email' => 'The email has already been taken.']);
    }


    /**
     * create new admin with invalid data
     * @return void
     */
    public function test_admin_create_new_admin_with_invalid_data(): void
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();

        $response = $this->actingAs($admin)->postJson('/admin-new-account-created', [
            'user_name' => 'cugod5465y',
            'password' => '',
            'role' => '',
            'first_name' => '5465',
            'last_name' => '5465656',
            'email' => 'admin@34mail.com',
            'confirm_email' => 'admin@34m32ail.com',
            'phone_number' => '+1 811-534-46345659',
            'region_id' => '',
            'address1' => '10 Greenfghg Firs$#%#$%t Parkway',
            'address2' => 'Obcaecafghh45ti veniam Na45435',
            'city' => 'Eum liberffghgo sed ex54656534545erc',
            'state' => '',
            'zip' => '3432432',
            'alt_mobile' => '32434',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'user_name' => 'Please enter only Alphabets in User name',
            'first_name' => 'Please enter only Alphabets in First name',
            'last_name' => 'Please enter only Alphabets in Last name',
            'password' => 'Please enter Password',
            'confirm_email' => 'Confirm Email should be same as Email',
            'address1' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in address1.',
            'address2' => 'Please enter alpbabets in address2 name.',
            'city' => 'Please enter alpbabets in city.',
            'zip' => 'Please enter 6 digits zipcode',
            'alt_mobile' => 'Please enter exactly 10 digits in Alternate Phone Number',
            'role' => 'Please select a Role',
            'state' => 'Please select state',
            'region_id' => 'Please select atleast one Region',
        ]);
    }
    /**
     * create new admin with empty data
     * @return void
     */
    public function test_admin_create_new_admin_with_empty_data(): void
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();

        $response = $this->actingAs($admin)->postJson('/admin-new-account-created', [
            'user_name' => '',
            'password' => '',
            'role' => '',
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'confirm_email' => '',
            'phone_number' => '',
            'region_id' => '',
            'address1' => '',
            'address2' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'alt_mobile' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'email' => 'Please enter Email',
            'user_name' => 'Please enter User Name',
            'first_name' => 'Please enter First Name',
            'last_name' => 'Please enter Last Name',
            'password' => 'Please enter Password',
            'confirm_email' => 'Please enter Confirm Email',
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

}
