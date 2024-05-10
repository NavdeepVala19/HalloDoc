<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
            'password' => 'admin12345',
        ]);
        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('admin.dashboard');
    }

    /**
     * Test admin and provider login with valid credentials
     *
     * @return void
     */
    public function test_provider_login_with_valid_credentials(): void
    {
        $response = $this->postJson('/admin-logged-in', [
            'email' => 'physician1@mail.com',
            'password' => 'physician1',
        ]);
        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirectToRoute('provider.dashboard');
    }

    /**
     * Test admin and provider login with invalid credentials
     *
     * @return void
     */
    public function test_admin_and_provider_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/admin-logged-in', [
            'email' => 'invalid@newmail.co',
            'password' => '1234323',
        ]);


        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error');
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
    }
}
