<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class PatientForgotPasswordTest extends TestCase
{
    /**
     * email is empty.
     *
     * @return void
     */
    public function test_email_not_entered_is_empty(): void
    {
        $response = $this->postJson('/patient/forgot-password-link', [
            'email' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'email' => 'The email field is required.',
        ]);
    }

    /**
     * email format is invalid.
     *
     * @return void
     */
    public function test_email_not_in_proper_format(): void
    {
        $response = $this->postJson('/patient/forgot-password-link', [
            'email' => 'shivesh@ma45il.com',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'email' => 'The email field format is invalid.',
        ]);
    }

    /**
     * Email entered is not of an registered patient or admin/provider
     *
     * @return void
     */
    public function test_email_not_registered(): void
    {
        $response = $this->postJson('/patient/forgot-password-link', [
            'email' => 'admin@mail.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'No such email is registered');
    }

    /**
     * Proper Email entered for reset password
     *
     * @return void
     */
    public function test_proper_email_entered_for_reset_password(): void
    {
        $response = $this->postJson('/patient/forgot-password-link', [
            'email' => 'lejig@mailinator.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('patient.login.view')->assertSessionHas('success', 'E-mail is sent for password reset.');
    }
}
