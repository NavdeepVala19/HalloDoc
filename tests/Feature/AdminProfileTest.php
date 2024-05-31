<?php

namespace Tests\Feature;

use App\Models\Admin;
use Tests\TestCase;
use App\Models\User;
use App\Models\Provider;
use App\Models\UserRoles;
use Symfony\Component\HttpFoundation\Response;

class AdminProfileTest extends TestCase
{
    /**
     * Details entered are correct and admin profile password is updated
     *
     * @return void
     */
    // public function test_update_admin_profile_password_with_valid_data(): void
    // {
    //     $userId = UserRoles::where('role_id', 1)->value('user_id');
    //     $admin = User::where('id', $userId)->first();
    //     $userId = User::first()->id;

    //     $response = $this->actingAs($admin)->postJson("/admin-update-password/$userId", [
    //         'password' => 'admin@mail.com',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('message', 'Your password is updated successfully');
    // }

    /**
     * Details are empty 
     *
     * @return void
     */
    // public function test_update_admin_profile_password_with_empty_data(): void
    // {
    //     $userId = UserRoles::where('role_id', 1)->value('user_id');
    //     $admin = User::where('id', $userId)->first();
    //     $userId = User::first()->id;

    //     $response = $this->actingAs($admin)->postJson("/admin-update-password/$userId", [
    //         'password' => '',
    //     ]);

    //     $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    //     $response->assertJsonValidationErrors([
    //         'password' => 'The password field is required.'
    //     ]);
    // }


    /**
     * Details entered are correct and admin information is updated
     *
     * @return void
     */
    // public function test_update_admin_administrator_information_with_valid_data(): void
    // {
    //     $userId = UserRoles::where('role_id', 1)->value('user_id');
    //     $admin = User::where('id', $userId)->first();
    //     $userId = User::first()->id;

    //     $response = $this->actingAs($admin)->postJson("/admin-info-updates/$userId", [
    //         'first_name' => 'shivesh',
    //         'last_name' => 'surani',
    //         'email' => 'admin@mail.com',
    //         'confirm_email' => 'admin@mail.com',
    //         'phone_number' => '1478523690',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('message', 'Your Administration Information is updated successfully');
    // }


    /**
     * Details entered are incorrect 
     *
     * @return void
     */
    public function test_update_admin_administrator_information_with_invalid_data(): void
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
        $userId = User::first()->id;

        $response = $this->actingAs($admin)->postJson("/admin-info-updates/$userId", [
            'first_name' => 'shiv343esh',
            'last_name' => 'su343rani',
            'email' => 'admin@m343ail.com',
            'confirm_email' => 'admin@mai43l.com',
            'phone_number' => '1478523690',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'The first name field must only contain letters.',
            'last_name' => 'The last name field must only contain letters.',
            'email' => 'The email field format is invalid.',
            'confirm_email' => 'The confirm email field format is invalid.',
        ]);
    }


    /**
     * Details entered are empty
     *
     * @return void
     */
    public function test_update_admin_administrator_information_with_empty_data(): void
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
        $userId = User::first()->id;

        $response = $this->actingAs($admin)->postJson("/admin-info-updates/$userId", [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'confirm_email' => '',
            'phone_number' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'The first name field is required.',
            'last_name' => 'The last name field is required.',
            'email' => 'The email field is required.',
            'confirm_email' => 'The confirm email field is required.',
            'phone_number' => 'The phone number field is required.',
        ]);
    }


    /**
     * Details entered are correct and admin mailing information is updated
     *
     * @return void
     */
    // public function test_update_admin_mailing_information_with_valid_data(): void
    // {
    //     $userId = UserRoles::where('role_id', 1)->value('user_id');
    //     $admin = User::where('id', $userId)->first();
    //     $userId = User::first()->id;

    //     $response = $this->actingAs($admin)->postJson("/admin-mail-updates/$userId", [
    //         'address1' => 'billionaires row',
    //         'address2' => 'manhattan',
    //         'city' => 'new york',
    //         'zip' => '147852',
    //         'alt_mobile' => '9513572584',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('message', 'Your Mailing and Billing Information is updated successfully');
    // }


    /**
     * Details entered are incorrect
     *
     * @return void
     */
    public function test_update_admin_mailing_information_with_invalid_data(): void
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
        $userId = User::first()->id;

        $response = $this->actingAs($admin)->postJson("/admin-mail-updates/$userId", [
            'address1' => 'billionai%^$^%res row',
            'address2' => 'manhatt$%$%an',
            'city' => 'ne$^%^$5w york',
            'zip' => '14754852',
            'alt_mobile' => '951357255554684',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'address1' => 'The address1 field format is invalid.',
            'address2' => 'The address2 field format is invalid.',
            'city' => 'The city field format is invalid.',
            'zip' => 'The zip field must be 6 digits.',
            'alt_mobile' => 'The alt mobile field must not have more than 10 digits.',
        ]);
    }

    /**
     * Details entered are empty
     *
     * @return void
     */
    public function test_update_admin_mailing_information_with_empty_data(): void
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
        $userId = User::first()->id;

        $response = $this->actingAs($admin)->postJson("/admin-mail-updates/$userId", [
            'address1' => '',
            'address2' => '',
            'city' => ' ',
            'zip' => '',
            'alt_mobile' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'address1' => 'The address1 field is required.',
            'address2' => 'The address2 field must be at least 2 characters.',
            'city' => 'The city field must be at least 2 characters.',
            'zip' => 'The zip field must be 6 digits.',
            'alt_mobile' => 'The alt mobile field is required.',
        ]);
    }
}
