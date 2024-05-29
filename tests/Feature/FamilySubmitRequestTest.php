<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;

class FamilySubmitRequestTest extends TestCase
{
    /**
     * Details entered are empty
     *
     * @return void
     */
    public function test_family_entered_data_is_empty(): void
    {
        $response = $this->postJson('/family-created', [
            'family_first_name' => '',
            'family_last_name' => '',
            'family_phone_number' => '',
            'family_email' => '',
            'family_relation' => '',
            'symptoms' => '',
            'first_name' => '',
            'last_name' => '',
            'date_of_birth' => '',
            'email' => '',
            'phone_number' => '',
            'street' => '',
            'city' => '',
            'state' => '',
            'zipcode' => '',
            'room' => '',
            'docs' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'Please enter First Name',
            'last_name' => 'Please enter Last Name',
            'email' => 'Please enter Email',
            'phone_number' => 'Please enter Phone Number',
            'date_of_birth' => 'Please enter Date of Birth',
            'street' => 'Please enter a street',
            'city' => 'Please enter a city',
            'state' => 'Please enter a state',
            'zipcode' => 'Please enter 6 digits zipcode',
            'family_first_name' => 'Please enter First Name',
            'family_last_name' => 'Please enter Last Name',
            'family_email' => 'Please enter Email',
            'family_phone_number' => 'Please enter Phone Number',
            'family_relation' => 'Please enter a relation with patient',
        ]);
    }

    /**
     * Details entered are not valid
     *
     * @return void
     */
    public function test_family_entered_data_is_not_valid(): void
    {
        $response = $this->postJson('/family-created', [
            'family_first_name' => 'Claire565',
            'family_last_name' => 'Sanford5465',
            'family_phone_number' => '04199 425 094',
            'family_email' => 'hakyrox@ma56546ilinator.com',
            'family_relation' => 'Quas aliqua Recusan565',
            'symptoms' => '$#^%^^4',
            'first_name' => 'Mohammad676576',
            'last_name' => 'Bradford675676',
            'date_of_birth' => '12-12-2030',
            'email' => 'ledihanijy@m56456ailinator.com',
            'phone_number' => '',
            'street' => 'Anim ut iure enim ei^&^&56',
            'city' => 'Dolore qui sint id s^&%&',
            'state' => 'Perspiciatis aut qu^%%^&%^',
            'zipcode' => '78039676',
            'room' => '',
            'docs' => '',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'first_name' => 'Please enter only Alphabets in First name',
            'last_name' => 'Please enter only Alphabets in Last name',
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'phone_number' => 'Please enter Phone Number',
            'family_first_name' => 'Please enter only Alphabets in First name',
            'family_last_name' => 'Please enter only Alphabets in Last name',
            'family_email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'family_relation' => 'Please enter valid relation (Format : alphabets-alphabets).',
            'date_of_birth' => 'Date of Birth should not be greater than today',
            'street' => 'Only alphabets,dash,underscore,space,comma and numbers are allowed in street name.',
            'city' => 'Please enter alpbabets in city name.',
            'state' => 'Please enter alpbabets in state name.',
            'zipcode' => 'Please enter 6 digits zipcode',
            'symptoms' => 'Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.',
        ]);
    }

    /**
     * Details entered are correct and new request is created with an existing email
     *
     * @return void
     */
    public function test_family_create_request_with_valid_data_and_existing_email(): void
    {
        $response = $this->postJson('/family-created', [
            'family_first_name' => 'Olivia',
            'family_last_name' => 'Oliver',
            'family_phone_number' => '1234567890',
            'family_email' => 'asdf@new.com',
            'family_relation' => 'cousin',
            'symptoms' => 'skin problem',
            'first_name' => 'otto',
            'last_name' => 'garrate',
            'date_of_birth' => '2004-12-12',
            'email' => 'otto@new.com',
            'phone_number' => '1234567880',
            'street' => 'Street 1',
            'city' => 'new city',
            'state' => 'states',
            'zipcode' => '123456',
            'room' => '',
            'docs' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Request is Submitted');
    }

    /**
     * Details entered are correct and new request is created with an new email
     *
     * @return void
     */
    public function test_family_create_request_with_valid_data_and_new_email(): void
    {
        $response = $this->postJson('/family-created', [
            'family_first_name' => 'Olivia',
            'family_last_name' => 'Oliver',
            'family_phone_number' => '1234567890',
            'family_email' => 'asdf@new.com',
            'family_relation' => 'cousin',
            'symptoms' => 'skin problem',
            'first_name' => 'otto',
            'last_name' => 'garrate',
            'date_of_birth' => '2004-12-12',
            'email' => fake()->unique()->email(),
            'phone_number' => '1234567880',
            'street' => 'Street 1',
            'city' => 'new city',
            'state' => 'states',
            'zipcode' => '123456',
            'room' => '',
            'docs' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('submit.request')->assertSessionHas('message', 'Email for Create Account is Sent and Request is Submitted');
    }
}
