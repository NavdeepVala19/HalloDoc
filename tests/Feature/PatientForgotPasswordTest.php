<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class PatientForgotPasswordTest extends TestCase
{
    /**
     * Email not entered or not in proper format.
     *
     * @return void
     */
    public function test_email_not_entered_or_not_in_proper_format(): void
    {
        $response = $this->postJson('/patient/forgot-password-link', [
            'email' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Email entered is not of an registered patient
     *
     * @return void
     */
    public function test_email_not_registered(): void
    {
        $response = $this->postJson('/patient/forgot-password-link', [
            'email' => 'admin@mail.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'no such email is registered');
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
