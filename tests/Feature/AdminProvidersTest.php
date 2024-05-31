<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Provider;
use App\Models\UserRoles;
use Symfony\Component\HttpFoundation\Response;

class AdminProvidersTest extends TestCase
{
    /**
     * admin create new Provider with valid data
     * @return void
     */

     private function adminLoggedIn(){
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
     }


    // public function test_admin_create_new_provider_with_valid_data(): void
    // {
    //     $userId = UserRoles::where('role_id', 1)->value('user_id');
    //     $admin = User::where('id', $userId)->first();

    //     $response = $this->actingAs($admin)->postJson('/admin-create-new-provider', [
    //         'user_name' => fake()->unique()->firstName(),
    //         'password' => 'cugody123',
    //         'role' => fake()->unique()->numberBetween(1,4),
    //         'first_name' => fake()->unique()->firstName(),
    //         'last_name' => fake()->unique()->lastName(),
    //         'email' => fake()->unique()->email(),
    //         'phone_number' => '+1 811-534-4639',
    //         'medical_license' => '2635463557',
    //         'npi_number' => '9493758979',
    //         'region_id' => [4,5],
    //         'address1' => '10 Green First Parkway',
    //         'address2' => 'Obcaecati veniam Na',
    //         'city' => 'Eum libero sed exerc',
    //         'select_state' => '3',
    //         'zip' => '311662',
    //         'phone_number_alt' => '2932364769',
    //         'business_name' => 'Caryn Moore',
    //         'business_website' => 'https://www.xutureguty.com',
    //         'admin_notes' => 'Fugiat aute archite',
    //         'provider_photo' => '',
    //         'independent_contractor' => '',
    //         'background_doc' => '',
    //         'hipaa_docs' => '',
    //         'non_disclosure_doc' => '',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND);
    // }


    /**
     * admin create new Provider with valid data and existing email
     * @return void
     */
    // public function test_admin_create_new_provider_with_valid_data_and_existing_email(): void
    // {
    //     $userId = UserRoles::where('role_id', 1)->value('user_id');
    //     $admin = User::where('id', $userId)->first();

    //     $response = $this->actingAs($admin)->postJson('/admin-create-new-provider', [
    //         'user_name' => 'cugody',
    //         'password' => 'cuggfhody',
    //         'role' => '3',
    //         'first_name' => 'Chaney',
    //         'last_name' => 'French',
    //         'email' => 'doctor@gmail.com',
    //         'phone_number' => '+1 811-534-4639',
    //         'medical_license' => '2635463557',
    //         'npi_number' => '9493758979',
    //         'region_id' => [2,4,5],
    //         'address1' => '10 Green First Parkway',
    //         'address2' => 'Obcaecati veniam Na',
    //         'city' => 'Eum libero sed exerc',
    //         'select_state' => '3',
    //         'zip' => '311662',
    //         'phone_number_alt' => '2932364769',
    //         'business_name' => 'Caryn Moore',
    //         'business_website' => 'https://www.xutureguty.com',
    //         'admin_notes' => 'Fugiat aute archite',
    //         'provider_photo' => '',
    //         'independent_contractor' => '',
    //         'background_doc' => '',
    //         'hipaa_docs' => '',
    //         'non_disclosure_doc' => '',
    //     ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertJsonValidationErrors(['email'=> 'The email has already been taken.']);
    // }

    /**
     * admin create new Provider with invalid data
     * @return void
     */
    public function test_admin_create_new_provider_with_invalid_data(): void
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();

        $response = $this->actingAs($admin)->postJson('/admin-create-new-provider', [
            'user_name' => 'cugody325',
            'password' => 'cugo#$%^#^   dy',
            'role' => '3',
            'first_name' => 'Cha3454ney',
            'last_name' => 'Frenc3543h',
            'email' => 'famytulos@mail4354inator.com',
            'phone_number' => '+1 811-534-4rgg639',
            'medical_license' => '2635465fgfrg3557',
            'npi_number' => '949375fggg8979',
            'region_id' => [1,3,4],
            'address1' => '10 Green First Par3656kway',
            'address2' => 'Obcaecati vr546545eniam Na',
            'city' => 'Eum 435665 sed exerc',
            'select_state' => '3',
            'zip' => '311656562',
            'phone_number_alt' => '293236443453769',
            'business_name' => 'Caryn Moore34354',
            'business_website' => 'https://www.xutureg435435uty435.com',
            'admin_notes' => 'Fugiat aute archite',
            'provider_photo' => '4',
            'independent_contractor' => '',
            'background_doc' => '',
            'hipaa_docs' => '',
            'non_disclosure_doc' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'user_name' => 'Please enter only Alphabets in User name',
            'first_name' => 'Please enter only Alphabets in First name',
            'last_name' => 'Please enter only Alphabets in Last name',
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'medical_license' => 'Please enter exactly 10 digits',
            'npi_number' => 'Please enter exactly 10 digits',
            'address2' => 'Please enter alpbabets in address2 name.',
            'city' => 'Please enter alpbabets in city.',
            'zip' => 'Please enter 6 digits zipcode',
            'business_name' => 'Please enter alphabets in business name.',
        ]);

    }


    /**
     * admin create new Provider with empty data
     * @return void
     */
    public function test_admin_create_new_provider_with_empty_data(): void
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();

        $response = $this->actingAs($admin)->postJson('/admin-create-new-provider', [
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
            'provider_photo' => '',
            'independent_contractor' => '',
            'background_doc' => '',
            'hipaa_docs' => '',
            'non_disclosure_doc' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'user_name' => 'Please enter User Name',
            'password' => 'Please enter Password',
            'role' => 'Please select a Role',
            'first_name' => 'Please enter First Name',
            'last_name' => 'Please enter Last Name',
            'email' => 'Please enter Email',
            'phone_number' => 'Please enter Phone Number',
            'medical_license' => 'Please enter medical license',
            'npi_number' => 'Please enter NPI number',
            'region_id' => 'Please select atleast one Region',
            'address1' => 'Please enter a address1',
            'address2' => 'Please enter a address2',
            'select_state' => 'Please select state',
            'city' => 'Please enter a city',
            'phone_number_alt' => 'Please enter Alternate Phone Number',
            'zip' => 'Please enter 6 digits zipcode',
            'business_name' => 'Please enter Business Name',
            'business_website' => 'Please enter Business Website Url',
            'admin_notes' => 'Please enter Admin Notes',
        ]);
    }


    /**
     * succesfull edit of physician information with valid data
     * @return void
     */
    // public function test_edit_physician_information_valid_data()
    // {
    //     $userId = UserRoles::where('role_id', 1)->value('user_id');
    //     $admin = User::where('id', $userId)->first();
    //     $providerId = Provider::first()->id;
    
    //     $response = $this->actingAs($admin)->postJson("/admin-provider-updated-infos/$providerId", [
    //         'first_name' => 'shivesh',
    //         'last_name' => 'surani',
    //         'email' => 'doctor@gmail.com',
    //         'phone_number' => '1234567890',
    //         'medical_license' => '1478523690',
    //         'npi_number' => '1478523690',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('message', 'Physician information is updated');
    // }

    /**
     * succesfull edit of physician information with invalid data
     * @return void
     */
    public function test_edit_physician_information_invalid_data()
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
        $providerId = Provider::first()->id;
    
        $response = $this->actingAs($admin)->postJson("/admin-provider-updated-infos/$providerId", [
            'first_name' => 'shives454h',
            'last_name' => 'sura5545ni',
            'email' => 'doctor@g454mail.com',
            'phone_number' => '1234567890',
            'medical_license' => '147852369340',
            'npi_number' => '147852369450',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'The first name field must only contain letters.',
            'last_name' => 'The last name field must only contain letters.',
            'email' => 'The email field format is invalid.',
            'medical_license' => 'The medical license field must not have more than 10 digits.',
            'npi_number' => 'The npi number field must not have more than 10 digits.',
        ]);
    }

    /**
     * succesfull edit of physician information with empty data
     * @return void
     */
    public function test_edit_physician_information_empty_data()
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
        $providerId = Provider::first()->id;
    
        $response = $this->actingAs($admin)->postJson("/admin-provider-updated-infos/$providerId", [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'phone_number' => '',
            'medical_license' => '',
            'npi_number' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'The first name field is required.',
            'last_name' => 'The last name field is required.',
            'email' => 'The email field is required.',
            'phone_number' => 'The phone number field is required.',
            'medical_license' => 'The medical license field is required.',
            'npi_number' => 'The npi number field is required.',
        ]);
    }


    /**
     * succesfull edit of mailing and billing information with valid data
     * @return void
     */
    // public function test_edit_mailing_information_valid_data()
    // {
    //     $userId = UserRoles::where('role_id', 1)->value('user_id');
    //     $admin = User::where('id', $userId)->first();
    //     $providerId = Provider::first()->id;
    
    //     $response = $this->actingAs($admin)->postJson("/admin-provider-updated-mail-infos/$providerId", [
    //         'address1' => 'billionaires row',
    //         'address2' => 'manhattan',
    //         'city' => 'new york',
    //         'zip' => '147852',
    //         'alt_phone_number' => '1234567890',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('message', 'Mailing and Billing information is updated');
    // }


    /**
     * succesfull edit of mailing and billing information with invalid data
     * @return void
     */
    public function test_edit_mailing_information_invalid_data()
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
        $providerId = Provider::first()->id;
    
        $response = $this->actingAs($admin)->postJson("/admin-provider-updated-mail-infos/$providerId", [
            'address1' => 'billionaires r%$$%3ow',
            'address2' => 'manha4354ttan',
            'city' => 'new yor435k',
            'zip' => '14785452',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'address1' => 'The address1 field format is invalid.',
            'address2' => 'The address2 field format is invalid.',
            'city' => 'The city field format is invalid.',
            'zip' => 'The zip field must be 6 digits.',
        ]);
    }


    /**
     * succesfull edit of mailing and billing information with empty data
     * @return void
     */
    public function test_edit_mailing_information_empty_data()
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
        $providerId = Provider::first()->id;
    
        $response = $this->actingAs($admin)->postJson("/admin-provider-updated-mail-infos/$providerId", [
            'address1' => '',
            'address2' => '',
            'city' => '',
            'zip' => '',
            'alt_phone_number' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'address1' => 'The address1 field is required.',
            'address2' => 'The address2 field is required.',
            'city' => 'The city field must be at least 2 characters.',
            'zip' => 'The zip field must be 6 digits.',
            'alt_phone_number' => 'The alt phone number field is required.',
        ]);
    }

    /**
     * succesfull edit of provider profile with valid data
     * @return void
     */

    // public function test_edit_provider_profile_valid_data()
    // {
    //     $userId = UserRoles::where('role_id', 1)->value('user_id');
    //     $admin = User::where('id', $userId)->first();
    //     $providerId = Provider::first()->id;
    
    //     $response = $this->actingAs($admin)->postJson("/admin-provider-updated-profile-data/$providerId", [
    //         'business_name' => 'skin clinic',
    //         'business_website' => 'https://www.skin.net',
    //         'admin_notes' => ' Aut aut aut at quos',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('message', 'Provider Profile information is updated');
    // }


    /**
     * succesfull edit of provider profile with invalid data
     * @return void
     */

    public function test_edit_provider_profile_invalid_data()
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
        $providerId = Provider::first()->id;
    
        $response = $this->actingAs($admin)->postJson("/admin-provider-updated-profile-data/$providerId", [
            'business_name' => 'skin cli$#@$nic',
            'admin_notes' => '$%#$%%4',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'business_name' => 'The business name field format is invalid.',
            'admin_notes' => 'The admin notes field format is invalid.',
        ]);
    }


    /**
     * succesfull edit of provider profile with empty data
     * @return void
     */

    public function test_edit_provider_profile_empty_data()
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
        $providerId = Provider::first()->id;
    
        $response = $this->actingAs($admin)->postJson("/admin-provider-updated-profile-data/$providerId", [
            'business_name' => '',
            'business_website' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'business_name' => 'The business name field is required.',
            'business_website' => 'The business website field is required.',
        ]);
    }
}
