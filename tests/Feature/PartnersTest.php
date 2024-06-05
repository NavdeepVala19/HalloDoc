<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Http\Response;
use App\Models\HealthProfessional;
use Illuminate\Support\Facades\Crypt;

class PartnersTest extends TestCase
{
    public function admin()
    {
        $adminId = UserRoles::where('role_id', 1)->first()->user_id;
        return User::where('id', $adminId)->first();
    }

    // Partners page can be rendered
    public function test_partners_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('add-business');

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.partners.addBusiness')
            ->assertViewHas('types');
    }

    /**
     * add business page can be rendered
     * @return void
     */
    public function test_add_business_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/add-business');

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.partners.addBusiness')
            ->assertViewHas('types');
    }

    /**
     * add business form with empty data
     * @return void
     */
    public function test_add_business_with_empty_data()
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)
            ->postJson('/add-business', [
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
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'business_name' => 'The business name field is required.',
                'fax_number' => 'The fax number field is required.',
                'mobile' => 'The mobile field is required.',
                'email' => 'The email field is required.',
                'business_contact' => 'The business contact field is required.',
                'street' => 'The street field is required.',
                'city' => 'The city field is required.',
                'state' => 'The state field is required.',
                'zip' => 'The zip field is required.',
            ]);
    }

    /**
     * add business form with invalid data
     * @return void
     */
    public function test_add_business_with_invalid_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/add-business', [
                'business_name' => 'Garrison 44',
                'profession' => 50,
                'fax_number' => '4610454455454',
                'mobile' => '+1 776-977-402345',
                'email' => 'refe@mailinato45r.com',
                'business_contact' => '3314545698752',
                'street' => 'Culp#$%#$%345a ullam error qu',
                'city' => 'Et aut#%$%#$em et aperiam ',
                'state' => 'Dolor #$%$%#545architecto off',
                'zip' => '529434540',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors([
            'business_name' => 'The business name field must only contain letters.',
            'fax_number' => 'The fax number field must not have more than 8 digits.',
            'email' => 'The email field format is invalid.',
            'business_contact' => 'The business contact field must not have more than 10 digits.',
            'street' => 'The street field must not be greater than 25 characters.',
            'city' => 'The city field format is invalid.',
            'state' => 'The state field format is invalid.',
            'zip' => 'The zip field must not have more than 6 digits.',
        ]);
    }

    /**
     * add business form with valid data
     * @return void
     */
    public function test_add_business_with_valid_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/add-business', [
                'business_name' => 'Garrison',
                'profession' => 1,
                'fax_number' => '4610',
                'mobile' => '+1 776-977-4023',
                'email' => 'refe@mailinator.com',
                'business_contact' => '3315698752',
                'street' => 'Culpa',
                'city' => 'Etasdfas',
                'state' => 'Dolor',
                'zip' => '529430',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('businessAdded', 'Business Added Successfully!');
    }
    // update business page can be rendered
    public function test_update_business_page_can_be_rendered()
    {
        $admin = $this->admin();

        $vendorId = HealthProfessional::orderBy('id', 'desc')->value('id');

        $id = Crypt::encrypt($vendorId);

        $response = $this->actingAs($admin)->get("/update-business/{$id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.partners.updateBusiness')
            ->assertViewHasAll(['vendor', 'professions']);
    }

    // update business with empty data
    public function test_update_business_with_empty_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/update-businesses', [
                'business_name' => '',
                'profession' => '',
                'fax_number' => '',
                'mobile' => '',
                'email' => '',
                'business_contact' => '',
                'address' => '',
                'city' => '',
                'state' => '',
                'zip' => '',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'business_name' => 'The business name field is required.',
                'profession' => 'The profession field is required.',
                'fax_number' => 'The fax number field is required.',
                'mobile' => 'The mobile field is required.',
                'email' => 'The email field is required.',
                'business_contact' => 'The business contact field is required.',
                'street' => 'The street field is required.',
                'city' => 'The city field is required.',
                'state' => 'The state field is required.',
                'zip' => 'The zip field is required.',
            ]);
    }

    // update business with invalid data
    public function test_update_business_with_invalid_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/update-businesses', [
                'business_name' => 'asd',
                'profession' => 'as',
                'fax_number' => '1231234234',
                'mobile' => '',
                'email' => 'asd1234@123.123',
                'business_contact' => '1234',
                'street' => '!@#4',
                'city' => '!@#$',
                'state' => '!@#$',
                'zip' => '1234',
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'business_name' => 'The business name field must be at least 5 characters.',
                'profession' => 'The profession field must be a number.',
                'fax_number' => 'The fax number field must not have more than 8 digits.',
                'mobile' => 'The mobile field is required.',
                'email' => 'The email field format is invalid.',
                'business_contact' => 'The business contact field must have at least 10 digits.',
                'street' => 'The street field format is invalid.',
                'city' => 'The city field format is invalid.',
                'state' => 'The state field format is invalid.',
                'zip' => 'The zip field must have at least 6 digits.',
            ]);
    }

    // update business with valid data
    public function test_update_business_with_valid_data()
    {
        $admin = $this->admin();

        $vendorId = HealthProfessional::orderBy('id', 'desc')->value('id');
        $response = $this->actingAs($admin)
            ->postJson('/update-businesses', [
                'vendor_id' => $vendorId,
                'business_name' => 'TestBusiness',
                'profession' => '1',
                'fax_number' => '12345678',
                'mobile' => '1234567890',
                'email' => 'testBusiness@mail.com',
                'business_contact' => '1234567890',
                'street' => 'test street business',
                'city' => 'test city business',
                'state' => 'test state business',
                'zip' => '123456',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('changesSaved', 'Changes Saved Successfully!');
    }

    // delete business on partners page
    public function test_delete_business_on_partners_page()
    {
        $admin = $this->admin();

        $id = HealthProfessional::orderBy('id', 'desc')->value('id');

        $response = $this->actingAs($admin)
            ->get("/delete-business/{$id}");

        $response->assertStatus(Response::HTTP_FOUND);
    }
}
