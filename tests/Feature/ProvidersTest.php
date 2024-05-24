<?php

namespace Tests\Feature;

use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ProvidersTest extends TestCase
{
    /**
     * Test successful send link form submission with valid data.
     *
     * @return void
     */
    public function test_send_link_form_with_valid_data()
    {
            $response = $this->postJson('/provider-send-mail', [
            'first_name' => 'shivesh',
            'last_name' =>'surani' ,
            'email' => 'shivesh@mail.com',
            'mobile_number' => '+1 403-288-7577' ,
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     *  Test successful send link form submission with empty_fields
     * @return void
     */

    public function test_send_link_form_with_empty_fields()
    {
        $response = $this->postJson('/provider-send-mail', [
            'first_name' => '',
            'last_name' => 'surani',
            'email' => 'shivesh@mail.com',
            'mobile_number' => '+1 403-288-7577',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
        
    }

    /**
     * Test successful send link form submission with invalid characters.
     *
     * @return void
     */
    public function test_send_link_form_with_invalid_characters()
    {
        $response = $this->postJson('/provider-send-mail', [
            'first_name' => '123!@#',
            'last_name' => 'surani',
            'email' => 'invalid_email',
            'mobile_number' => 'invalid_number',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful create request with valid data
     * @return void
     */

    public function test_create_request_form_with_valid_data()
    {
        $response = $this->postJson('/provider-request', [
            'first_name' => 'shivesh',
            'last_name' => 'surani',
            'email' => 'shivesh@mail.com',
            'phone_number' => '+1 403-288-7577',
            'street' => 'fgfgef',
            'city' => 'dfgedgf',
            'state' => 'gfdgfd',
            'zip' => '147852',
            'room' => '4',
            'note' => 'sddfsfd',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful create request with empty data
     * @return void
     */
    public function test_create_request_form_with_empty_data()
    {
        $response = $this->postJson('/provider-request', [
            'first_name' => '',
            'last_name' => 'surani',
            'email' => 'shivesh@mail.com',
            'phone_number' => '+1 403-288-7577',
            'street' => '',
            'city' => 'dfgedgf',
            'state' => '',
            'zip' => '',
            'room' => '4',
            'note' => 'sddfsfd',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful create request with invalid data
     * @return void
     */

    public function test_create_request_form_with_invalid_data()
    {
        $response = $this->postJson('/provider-request', [
            'first_name' => '21343243',
            'last_name' => '4543',
            'email' => 'shivesh@454mail.com',
            'phone_number' => '+1 403-288-7577',
            'street' => 'dcdgf',
            'city' => 'dfgedgf34',
            'state' => 'dfgfg45',
            'zip' => 'f1478523',
            'room' => '4ff',
            'note' => 'sddfsfd',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful create request to admin with valid data
     * @return void
     */
    public function test_send_request_to_admin_with_valid_data(){
        $response = $this->postJson('/provider-edit-profile', [
            "message" =>'hello admin'
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful create request to admin with empty data
     * @return void
     */

    public function test_send_request_to_admin_with_empty_data(){
        $response = $this->postJson('/provider-edit-profile', [
            "message" =>''
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful transfer case to admin with valid data
     * @return void
     */
    public function test_transfer_case_to_admin_with_valid_data(){
        $response = $this->postJson('/transfer-case', [
            "message" =>'transfer back'
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful create request to admin with empty data
     * @return void
     */

    public function test_transfer_case_to_admin_with_empty_data(){
        $response = $this->postJson('/transfer-case', [
            "message" =>''
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    
    /**
     * Test successful encounter form with valid data
     * @return void
     */
    public function test_encounter_form_valid_data(){
        $response = $this->postJson('/medical-form', [
            "first_name" => 'Xenos',
            "last_name" => 'Oneill',
            "location" => 'Ut non repellendus ',
            "date_of_birth" =>'25-01-2017',
            "service_date" =>'13-05-2024',
            "mobile" => '+1 601-603-4829',
            "email" => 'ryhobeluxe@mailinator.com',
            "present_illness_history" => 'Sint dolores nostru',
            "medical_history" => 'A dolorum iusto exce',
            "medications" => 'Laboris doloribus te',
            "allergies" => 'Maxime nulla non et ',
            "temperature" => '40',
            "heart_rate" => '48',
            "repository_rate" => '25',
            "sis_BP" => '68',
            "dia_BP" => '51',
            "oxygen" => '75',
            "pain" => 'Adipisicing repellen',
            "heent" => 'Ullam cum qui nulla ',
            "cv" => 'Excepturi accusantiu',
            "chest" => 'Consequatur volupta',
            "abd" => 'Ea aut soluta fugit',
            "extr" => 'Esse et aliquid qui',
            "skin" => 'Pariatur Rerum iure',
            "neuro" => 'Hic ad est veniam e',
            "other" => 'Ut id quibusdam quib',
            "diagnosis" => 'Sed et veritatis nob',
            "treatment_plan" => 'Voluptas cupidatat t',
            "medication_dispensed" => 'Aut ut consequuntur ',
            "procedure" => 'Ullamco doloribus ip',
            "followUp" => 'Ipsum tenetur possim',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful encounter form with empty data
     * @return void
     */
    public function test_encounter_form_with_empty_data(){
        $response = $this->postJson('/medical-form', [
            "first_name" => '',
            "last_name" => '',
            "location" => '',
            "date_of_birth" =>'',
            "service_date" =>'',
            "mobile" => '',
            "email" => '',
            "present_illness_history" => '',
            "medical_history" => '',
            "medications" => '',
            "allergies" => '',
            "temperature" => '',
            "heart_rate" => '',
            "repository_rate" => '',
            "sis_BP" => '',
            "dia_BP" => '',
            "oxygen" => '',
            "pain" => '',
            "heent" => '',
            "cv" => '',
            "chest" => '',
            "abd" => '',
            "extr" => '',
            "skin" => '',
            "neuro" => '',
            "other" => '',
            "diagnosis" => '',
            "treatment_plan" => '',
            "medication_dispensed" => '',
            "procedure" => '',
            "followUp" => '',
        ]);
    
        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful send order form with valid data
     * @return void
     */

    public function test_send_order_with_valid_data(){
        $response = $this->postJson('/provider-send-order',[
            'prescription' => 'Voluptatum anim elig',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful send order form with invalid data
     * @return void
     */
    public function test_send_order_with_invalid_data(){
        $response = $this->postJson('/provider-send-order',[
            'prescription' => 'Molestiae doloribus $%@#$#@434',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful send order form with empty data
     * @return void
     */
    public function test_send_order_with_empty_data(){
        $response = $this->postJson('/provider-send-order',[
            'prescription' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful view notes form with valid data
     * @return void
     */
    public function test_view_notes_with_valid_data(){
        $response = $this->postJson('/provider/view/notes/store',[
            'physician_note' => 'Physician Notes',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful view notes form with empty data
     * @return void
     */
    public function test_view_notes_with_empty_data(){
        $response = $this->postJson('/provider/view/notes/store',[
            'physician_note' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Test successful view notes form with invalid data
     * @return void
     */
    public function test_view_notes_with_invalid_data(){
        $response = $this->postJson('/provider/view/notes/store',[
            'physician_note' => '#$%$%',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }



    /**
     * Test successful reset password in my profile with valid data
     * @return void
     */
    public function test_reset_password_in_my_profile_with_valid_data()
    {
        $response = $this->postJson('/provider-reset-password', [
            'password' => 'doctor@gmail.com',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful reset password in my profile with invalid data
     * @return void
     */
    public function test_reset_password_in_my_profile_with_invalid_data()
    {
        $response = $this->postJson('/provider-reset-password', [
            'password' => 'do54',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful reset password in my profile with empty data
     * @return void
     */
    public function test_reset_password_in_my_profile_with_empty_data()
    {
        $response = $this->postJson('/provider-reset-password', [
            'password' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful schedule shift with valid data
     * @return void
     */
    public function test_schedule_shift_with_valid_data()
    {
        $response = $this->postJson('/provider-create-shift', [
            'region' => 'somnath',
            'shiftDate' => '15-05-2024',
            'shiftStartTime' => '11:00',
            'shiftEndTime' => '12:00',
            'checkbox' => '1',
            'repeatEnd' => '2',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }



    /**
     * Test successful schedule shift with invalid data
     * @return void
     */
    public function test_schedule_shift_with_invalid_data()
    {
        $response = $this->postJson('/provider-create-shift', [
            'region' => 'somnath',
            'shiftDate' => '11-05-2024',
            'shiftStartTime' => '15:00',
            'shiftEndTime' => '12:00',
            'checkbox' => '7',
            'repeatEnd' => '5',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful schedule shift with empty data
     * @return void
     */
    public function test_schedule_shift_with_empty_data()
    {
        $response = $this->postJson('/provider-create-shift', [
            'region' => '',
            'shiftDate' => '',
            'shiftStartTime' => '',
            'shiftEndTime' => '',
            'checkbox' => '',
            'repeatEnd' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful edit scheduled shift with valid data
     * @return void
     */
    public function test_edit_scheduled_shift_with_valid_data()
    {
        $response = $this->postJson('/provider-edit-shift', [
            'shiftDate' => '22-05-2024',
            'shiftStartTime' => '12:00',
            'shiftEndTime' => '14:00',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful edit scheduled shift with invalid data
     * @return void
     */
    public function test_edit_scheduled_shift_with_invalid_data()
    {
        $response = $this->postJson('/provider-edit-shift', [
            'shiftDate' => '2-05-2024',
            'shiftStartTime' => '15:00',
            'shiftEndTime' => '13:00',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    /**
     * Test successful edit scheduled shift with valid data
     * @return void
     */
    public function test_edit_scheduled_shift_with_empty_data()
    {
        $response = $this->postJson('/provider-edit-shift', [
            'shiftDate' => '',
            'shiftStartTime' => '',
            'shiftEndTime' => '',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

}
