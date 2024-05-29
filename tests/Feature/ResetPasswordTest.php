<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class ResetPasswordTest extends TestCase
{

    /** admin and provider reset password */

    /**
     * No email entered, then return back and show error
     *
     * @return void
     */
    public function test_no_email_entered_in_reset_password(): void
    {
        $response = $this->postJson('/reset-password-link', [
            'email' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'email' => 'The email field is required.',
        ]);
    }

    /**
     * Invalid email entered (email format is not valid)
     *
     * @return void
     */
    public function test_invalid_email_entered_for_reset_password(): void
    {
        $response = $this->postJson('/reset-password-link', [
            'email' => 'admin@343mail.com',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'email' => 'The email field format is invalid.',
        ]);
    }

    /**
     * valid email entered (which is either admin or provider emails)
     *
     * @return void
     */
    public function test_valid_email_entered_for_reset_password(): void
    {
        $response = $this->postJson('/reset-password-link', [
            'email' => 'admin@mail.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('login')->assertSessionHas('message', 'E-mail is sent for password reset');
    }


    /**
     * valid email entered (patient email)
     *
     * @return void
     */
    public function test_patient_valid_email_entered_for_reset_password(): void
    {
        $response = $this->postJson('/reset-password-link', [
            'email' => 'shivesh@mail.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('error', 'no such email is registered');
    }
}
