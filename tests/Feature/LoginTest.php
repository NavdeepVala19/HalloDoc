<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class LoginTest extends TestCase
{
    /**
     * Test admin and provider login with valid credentials
     *
     * @return void
     */
    public function test_admin_login_with_valid_credentials(): void
    {
        $response = $this->postJson('/admin-logged-in', [
            'email' => 'admin@mail.com',
            'password' => 'admin@mail.com',
        ]);
        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('admin.dashboard');
    }

    /**
     * Test admin and provider login with valid credentials
     *
     * @return void
     */
    public function test_provider_login_with_valid_credentials(): void
    {
        $response = $this->postJson('/admin-logged-in', [
            'email' => 'doctor@gmail.com',
            'password' => 'doctor@gmail.com',
        ]);
        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('provider.dashboard');
    }

    /**
     * Test admin and provider login with invalid credentials
     *
     * @return void
     */
    public function test_admin_and_provider_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/admin-logged-in', [
            'email' => 'invalid@new343mail.co',
            'password' => '12343fdhgd23',
        ]);


        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'email' => 'The email field format is invalid.',
        ]);
    }

    /**
     * Test admin and provider login with no data entered
     *
     * @return void
     */
    public function test_admin_and_provider_login_with_no_data(): void
    {
        $response = $this->postJson('/admin-logged-in', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'email' => 'The email field is required.',
            'password' => 'The password field is required.',
        ]);
    }

    /**
     * Test admin login with invalid email credentials
     *
     * @return void
     */
    public function test_admin_login_with_invalid_email_credentials(): void
    {
        $response = $this->postJson('/admin-logged-in', [
            'email' => 'admi45544n@mail.com',
            'password' => 'admin@mail.com',
        ]);
        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'We could not find an account associated with that email address.');
    }


    /**
     * Test admin login with invalid password credentials
     *
     * @return void
     */
    public function test_admin_login_with_invalid_password_credentials(): void
    {
        $response = $this->postJson('/admin-logged-in', [
            'email' => 'admin@mail.com',
            'password' => 'a443sgffgdmin12345',
        ]);
        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'Incorrect Password, Please Enter Correct Password.');
    }


    /**
     * Test provider login with invalid email credentials
     *
     * @return void
     */
    public function test_provider_login_with_invalid_email_credentials(): void
    {
        $response = $this->postJson('/admin-logged-in', [
            'email' => 'doctoeffgr@gmail.com',
            'password' => 'doctor@gmail.com',
        ]);
        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'We could not find an account associated with that email address.');
    }

    /**
     * Test provider login with invalid password credentials
     *
     * @return void
     */
    public function test_provider_login_with_invalid_password_credentials(): void
    {
        $response = $this->postJson('/admin-logged-in', [
            'email' => 'doctor@gmail.com',
            'password' => 'dgnegnwiotniogto',
        ]);
        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'Incorrect Password, Please Enter Correct Password.');
    }

    /**
     * Test patient login with valid credentials
     *
     * @return void
     */
    public function test_patient_login_with_valid_credentials(): void
    {
        $response = $this->postJson('/admin-logged-in', [
            'email' => 'shivesh@mail.com',
            'password' => 'shivesh@mail.com',
        ]);
        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'Invalid Credentials');
    }
}
