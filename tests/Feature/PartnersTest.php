<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use App\Models\HealthProfessional;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class PartnersTest extends TestCase
{
    private function adminLoggedIn()
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();
        return $admin;
    }

    public function test_view_add_business_page(){
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->get('/add-business');
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test successful add business form with valid data
     * @return void
     */
    // public function test_add_business_with_valid_data()
    // {
    //     $admin = $this->adminLoggedIn();
    //     $response = $this->actingAs($admin)->postJson('/add-business', [
    //         'business_name' => 'businessName',
    //         'profession' => '4',
    //         'fax_number' => '46101',
    //         'mobile' => '+1 776-977-4023',
    //         'email' => fake()->unique()->email(),
    //         'business_contact' => '1478523690',
    //         'street' => 'Culpa ullam error qu',
    //         'city' => 'Et autem et aperiam ',
    //         'state' => 'Dolor architecto off',
    //         'zip' => '529430',
    //     ]);

    //     $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('admin.partners')->assertSessionHas('businessAdded', 'Business Added Successfully!');
    // }

    /**
     * Test successful add business form with invalid data
     * @return void
     */
    public function test_add_business_with_invalid_data()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/add-business', [
            'business_name' => 'Garrison 44',
            'profession' => '2',
            'fax_number' => '4610454455454',
            'mobile' => '+1 776-977-402345',
            'email' => 'refe@mailinato45r.com',
            'business_contact' => '3314545698752',
            'street' => 'Culp#$%#$%345a ul',
            'city' => 'Et aut#%$%#$em et ape',
            'state' => 'Dolor #$%$%#545architec',
            'zip' => '529434540',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'business_name' => 'Please enter only Alphabets in First name',
            'fax_number' => 'The fax number field must not have more than 8 digits.',
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'business_contact' => 'Please enter exactly 10 digits',
            'street' => 'Only alphabets, Numbers and ,_-. allowed.',
            'city' => 'Please enter alpbabets in city name.',
            'state' => 'Please enter alpbabets in state name.',
            'zip' => 'Please enter 6 digits zipcode',
        ]);
    }

    /**
     * Test successful add business form with empty data
     * @return void
     */
    public function test_add_business_with_empty_data()
    {
        $admin = $this->adminLoggedIn();
        $response = $this->actingAs($admin)->postJson('/add-business', [
            'business_name' => '',
            'fax_number' => '',
            'mobile' => '',
            'email' => '',
            'business_contact' => '',
            'street' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'business_name' => 'Please enter Business Name',
            'profession' => 'Please enter Profession',
            'fax_number' => 'Please enter Fax numbers',
            'mobile' => 'Please enter Phone Number',
            'email' => 'Please enter Email',
            'business_contact' => 'Please enter Business Contact',
            'street' => 'Please enter a street',
            'city' => 'Please enter a city',
            'state' => 'Please enter a state',
            'zip' => 'The zip field is required.',
        ]);
    }

    /**
     * test case successfull of viewe partners page
     * @return void
     */
    // public function test_view_partners_page(){
    //     $admin = $this->adminLoggedIn();
    //     $id = HealthProfessional::first()->id;

    //     $response = $this->actingAs($admin)->get("/partners/$id");

    //     $response->assertStatus(Response::HTTP_OK);
    //     $response->assertViewIs('adminPage.partners.partners');

    //     $vendorsData = $response->getOriginalContent()->getData()['vendors']->items()[0]->getAttributes();

    //     $this->assertTrue(array_key_exists('id', $vendorsData));
    //     $this->assertTrue(array_key_exists('profession', $vendorsData));
    //     $this->assertTrue(array_key_exists('vendor_name', $vendorsData));
    //     $this->assertTrue(array_key_exists('fax_number', $vendorsData));
    //     $this->assertTrue(array_key_exists('address', $vendorsData));
    //     $this->assertTrue(array_key_exists('city', $vendorsData));
    //     $this->assertTrue(array_key_exists('state', $vendorsData));
    //     $this->assertTrue(array_key_exists('zip', $vendorsData));
    //     $this->assertTrue(array_key_exists('phone_number', $vendorsData));
    //     $this->assertTrue(array_key_exists('email', $vendorsData));
    //     $this->assertTrue(array_key_exists('business_contact', $vendorsData));
    //     $this->assertTrue(array_key_exists('region_id', $vendorsData));
    // }


    /**
     * test succesfull of delete partners
     * @return void
     */
    // public function test_delete_partner()
    // {
    //     $admin = $this->adminLoggedIn();
    //     $id = HealthProfessional::select('id')->latest()->value('id');

    //     $response = $this->actingAs($admin)->get("/delete-business/$id");
    //     $response->assertStatus(302);
    // }

    /**
     * test successful of view edit partners page
     * @return void
     */

    // public function test_view_partners_edit_page(){
    //     $admin = $this->adminLoggedIn();
    //     $id = Crypt::encrypt(HealthProfessional::first()->id);

    //     $response = $this->actingAs($admin)->get("/update-business/$id");

    //     $response->assertStatus(Response::HTTP_OK);
    //     $response->assertViewIs('adminPage.partners.updateBusiness');

    //     $vendorsData = $response->getOriginalContent()->getData()['vendor']->getAttributes();

    //     $this->assertTrue(array_key_exists('id', $vendorsData));
    //     $this->assertTrue(array_key_exists('profession', $vendorsData));
    //     $this->assertTrue(array_key_exists('vendor_name', $vendorsData));
    //     $this->assertTrue(array_key_exists('fax_number', $vendorsData));
    //     $this->assertTrue(array_key_exists('address', $vendorsData));
    //     $this->assertTrue(array_key_exists('city', $vendorsData));
    //     $this->assertTrue(array_key_exists('state', $vendorsData));
    //     $this->assertTrue(array_key_exists('zip', $vendorsData));
    //     $this->assertTrue(array_key_exists('phone_number', $vendorsData));
    //     $this->assertTrue(array_key_exists('email', $vendorsData));
    //     $this->assertTrue(array_key_exists('business_contact', $vendorsData));
    //     $this->assertTrue(array_key_exists('region_id', $vendorsData));
    // }


    /**
     * test successfull of update partners with invalid data
     * @return void
     */
    public function test_update_partners_with_invalid_data(){
        $admin = $this->adminLoggedIn();
        $id = HealthProfessional::select('id')->latest()->value('id');

        $response = $this->actingAs($admin)->postJson("/update-businesses",[
            'vendor_id ' => $id,
            'business_name' => 'Garrison 44',
            'profession' => '2',
            'fax_number' => '4610454455454',
            'mobile' => '+1 776-977-402345',
            'email' => 'refe@mailinato45r.com',
            'business_contact' => '3314545698752',
            'street' => 'Culp#$%#$%345a ul',
            'city' => 'Et aut#%$%#$em et ape',
            'state' => 'Dolor #$%$%#545architec',
            'zip' => '529434540',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'business_name' => 'Please enter only Alphabets in First name',
            'fax_number' => 'The fax number field must not have more than 8 digits.',
            'email' => 'Please enter a valid email (format: alphanum@alpha.domain).',
            'business_contact' => 'Please enter exactly 10 digits',
            'street' => 'Only alphabets, Numbers and ,_-. allowed.',
            'city' => 'Please enter alpbabets in city name.',
            'state' => 'Please enter alpbabets in state name.',
            'zip' => 'Please enter 6 digits zipcode',
        ]);

    }


    /**
     * test successfull of update business with empty data
     * @return void
     */
    public function test_update_business_with_empty_data(){
        $admin = $this->adminLoggedIn();
        $id = HealthProfessional::select('id')->latest()->value('id');

        $response = $this->actingAs($admin)->postJson("/update-businesses", [
            'vendor_id ' => $id,
            'business_name' => '',
            'fax_number' => '',
            'mobile' => '',
            'email' => '',
            'business_contact' => '',
            'street' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors([
            'business_name' => 'Please enter Business Name',
            'profession' => 'Please enter Profession',
            'fax_number' => 'Please enter Fax numbers',
            'mobile' => 'Please enter Phone Number',
            'email' => 'Please enter Email',
            'business_contact' => 'Please enter Business Contact',
            'street' => 'Please enter a street',
            'city' => 'Please enter a city',
            'state' => 'Please enter a state',
            'zip' => 'The zip field is required.',
        ]);
    }


    // public function test_update_partners_with_valid_data(){
    //     $admin = $this->adminLoggedIn();

    //     $id = HealthProfessional::select('id')->latest()->value('id');

    //     $response = $this->actingAs($admin)->postJson('/update-businesses', [
    //         'vendor_id' => $id,
    //         'business_name' => 'Cameran',
    //         'profession' => '5',
    //         'fax_number' => '7136',
    //         'mobile' => '+1 767-633-9979',
    //         'email' => 'sosedipi@mailinator.com',
    //         'business_contact' => '2345454354',
    //         'street' => 'Sunt voluptatem at ',
    //         'city' => 'Illo est veniam el',
    //         'state' => 'Asperiores minim dol',
    //         'zip' => '860624',
    //     ]);


    //     $response->assertStatus(Response::HTTP_FOUND)->assertRedirectToRoute('admin.partners')->assertSessionHas('changesSaved', 'Changes Saved Successfully!');
    // }
}
