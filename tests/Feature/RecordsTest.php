<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use App\Models\BlockRequest;
use App\Models\RequestClient;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class RecordsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    private function adminLogin()
    {
        $userId = UserRoles::where('role_id', 1)->value('user_id');
        $admin = User::where('id', $userId)->first();

        return $admin;
    }

    /**
     * this test case is about to view search records page and it is GET Request
     * @return void
     */
    public function test_search_records_view_data(): void
    {
        $admin = $this->adminLogin();
        $response = $this->actingAs($admin)->get('/search-records');

        $content = $response->getOriginalContent();
        $data = $content->getData();
        $records = $data['records'];

        $clients = $records->items();

        $firstClientAttributes = $clients[0]->getAttributes();
 
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.records.searchRecords');
        

        $this->assertTrue(array_key_exists('request_type_id', $firstClientAttributes)); 
        $this->assertTrue(array_key_exists('id', $firstClientAttributes)); 
        $this->assertTrue(array_key_exists('email', $firstClientAttributes)); 
        $this->assertTrue(array_key_exists('street', $firstClientAttributes)); 
        $this->assertTrue(array_key_exists('first_name', $firstClientAttributes)); 
        $this->assertTrue(array_key_exists('city', $firstClientAttributes)); 
        $this->assertTrue(array_key_exists('state', $firstClientAttributes)); 
        $this->assertTrue(array_key_exists('zipcode', $firstClientAttributes)); 
        $this->assertTrue(array_key_exists('notes', $firstClientAttributes)); 
        $this->assertTrue(array_key_exists('physician_notes', $firstClientAttributes)); 
        $this->assertTrue(array_key_exists('admin_notes', $firstClientAttributes)); 
        $this->assertTrue(array_key_exists('created_date', $firstClientAttributes)); 
        $this->assertTrue(array_key_exists('closed_date', $firstClientAttributes)); 
 

    }


    /**
     * test case of filter search records with existing data
     * @return void
     */
    public function test_filter_search_records_with_existing_data()
    {
        $admin = $this->adminLogin();
        $response = $this->actingAs($admin)->postJson('/search-records/search', [
            'request_status' => '1',
            'patient_name' => '',
            'request_type' => '',
            'from_date_of_service' => '',
            'to_date_of_service' => '',
            'provider_name' => '',
            'email' => 'shivesh@mail.com',
            'phone_number' => '',
        ]);

        $data = $response->getOriginalContent()->getData();
        $records = $data['records'];

        $clients = $records->items();

        $firstClientAttributes = $clients[0]->getAttributes();
 
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.records.searchRecords');

        $this->assertTrue(array_key_exists('request_type_id', $firstClientAttributes));
        $this->assertTrue(array_key_exists('id', $firstClientAttributes));
        $this->assertTrue(array_key_exists('email', $firstClientAttributes));
        $this->assertTrue(array_key_exists('street', $firstClientAttributes));
        $this->assertTrue(array_key_exists('first_name', $firstClientAttributes));
        $this->assertTrue(array_key_exists('city', $firstClientAttributes));
        $this->assertTrue(array_key_exists('state', $firstClientAttributes));
        $this->assertTrue(array_key_exists('zipcode', $firstClientAttributes));
        $this->assertTrue(array_key_exists('patient_notes', $firstClientAttributes));
        $this->assertTrue(array_key_exists('physician_notes', $firstClientAttributes));
        $this->assertTrue(array_key_exists('admin_notes', $firstClientAttributes));
        $this->assertTrue(array_key_exists('created_date', $firstClientAttributes));
        $this->assertTrue(array_key_exists('closed_date', $firstClientAttributes)); 
    }


    public function test_export_search_records(){
        $admin = $this->adminLogin();
        $response = $this->actingAs($admin)->postJson('/search-records/export', [
            'request_status' => '1',
            'patient_name' => '',
            'request_type' => '',
            'from_date_of_service' => '',
            'to_date_of_service' => '',
            'provider_name' => '',
            'email' => 'shivesh@mail.com',
            'phone_number' => '',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }
    
    /**
     * test case of delete search record
     * @return void
     */

    // public function test_delete_search_records(){
    //     $admin = $this->adminLogin();
    //     $id = RequestClient::first()->id;
    //     $response = $this->actingAs($admin)->get("/search-records/delete/$id");

    //     $response->assertStatus(Response::HTTP_FOUND);
    // }


    /**
     * test case of view email logs data 
     * @return void
     */
    public function test_email_logs_view_data(){
        $admin = $this->adminLogin();
        $response = $this->actingAs($admin)->get('/email-logs');

        $data = $response->getOriginalContent()->getData();
        $emails = $data['emails']->items();

        $log = $emails[0]->getAttributes();
        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.records.emailLogs');

        $this->assertTrue(array_key_exists('role_id', $log));
        $this->assertTrue(array_key_exists('recipient_name', $log));
        $this->assertTrue(array_key_exists('email', $log));
        $this->assertTrue(array_key_exists('subject_name', $log));
        $this->assertTrue(array_key_exists('confirmation_number', $log));
        $this->assertTrue(array_key_exists('create_date', $log));
        $this->assertTrue(array_key_exists('sent_date', $log));
        $this->assertTrue(array_key_exists('is_email_sent', $log));
        $this->assertTrue(array_key_exists('sent_tries', $log));
        $this->assertTrue(array_key_exists('action', $log));
 
    }


    /**
     * email logs filtered data 
     * @return void
     */
    public function test_email_logs_filtered_data(){
        $admin = $this->adminLogin();
        $response = $this->actingAs($admin)->get('/search-email-logs', [
            'role_id' => '1',
            'receiver_name' => '',
            'created_date' => '',
            'sent_date' => '',
            'email' => 'shivesh@mail.com',
        ]);

        $data = $response->getOriginalContent()->getData();
        $emails = $data['emails']->items();

        $log = $emails[0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.records.emailLogs');

        $this->assertTrue(array_key_exists('role_id', $log));
        $this->assertTrue(array_key_exists('recipient_name', $log));
        $this->assertTrue(array_key_exists('email', $log));
        $this->assertTrue(array_key_exists('subject_name', $log));
        $this->assertTrue(array_key_exists('confirmation_number', $log));
        $this->assertTrue(array_key_exists('create_date', $log));
        $this->assertTrue(array_key_exists('sent_date', $log));
        $this->assertTrue(array_key_exists('is_email_sent', $log));
        $this->assertTrue(array_key_exists('sent_tries', $log));
        $this->assertTrue(array_key_exists('action', $log));
    }


    /**
     * test case of SMSLogs view 
     * @return void
     */
    public function test_sms_logs_view(){
        $admin = $this->adminLogin();
        $response = $this->actingAs($admin)->get('/sms-logs');

        $data = $response->getOriginalContent()->getData();
        $smsLogs = $data['smsLogs']->items();

        $log = $smsLogs[0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.records.smsLogs');

        $this->assertTrue(array_key_exists('role_id', $log));
        $this->assertTrue(array_key_exists('recipient_name', $log));
        $this->assertTrue(array_key_exists('mobile_number', $log));
        $this->assertTrue(array_key_exists('confirmation_number', $log));
        $this->assertTrue(array_key_exists('created_date', $log));
        $this->assertTrue(array_key_exists('sent_date', $log));
        $this->assertTrue(array_key_exists('is_sms_sent', $log));
        $this->assertTrue(array_key_exists('sent_tries', $log));
        $this->assertTrue(array_key_exists('action', $log));
    }


    /**
     * test case of filtered sms logs view
     * @return void
     */
    public function test_filter_sms_logs_view(){
        $admin = $this->adminLogin();
        $response = $this->actingAs($admin)->get('/sms-logs/search', [
            'role_type' => '2',
            'receiver_name' => 'shivesh surani',
            'created_date' => '',
            'sent_date' => '',
            'phone_number' => '	1234567890',
        ]);

        $data = $response->getOriginalContent()->getData();
        $smsLogs = $data['smsLogs']->items();

        $log = $smsLogs[0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.records.smsLogs');

        $this->assertTrue(array_key_exists('role_id', $log));
        $this->assertTrue(array_key_exists('recipient_name', $log));
        $this->assertTrue(array_key_exists('mobile_number', $log));
        $this->assertTrue(array_key_exists('confirmation_number', $log));
        $this->assertTrue(array_key_exists('created_date', $log));
        $this->assertTrue(array_key_exists('sent_date', $log));
        $this->assertTrue(array_key_exists('is_sms_sent', $log));
        $this->assertTrue(array_key_exists('sent_tries', $log));
        $this->assertTrue(array_key_exists('action', $log));
    }


    /**
     * test case of patient history view 
     * @return void
     */
    public function test_patient_history_view_data(){
        $admin = $this->adminLogin();
        $response = $this->actingAs($admin)->get('/patient-history');

        $data = $response->getOriginalContent()->getData();
        $patientData = $data['patients']->items();

        $patientRecords = $patientData[0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.records.patientHistory');

        $this->assertTrue(array_key_exists('first_name', $patientRecords));
        $this->assertTrue(array_key_exists('last_name', $patientRecords));
        $this->assertTrue(array_key_exists('phone_number', $patientRecords));
        $this->assertTrue(array_key_exists('email', $patientRecords));
        $this->assertTrue(array_key_exists('street', $patientRecords));
        $this->assertTrue(array_key_exists('city', $patientRecords));
        $this->assertTrue(array_key_exists('state', $patientRecords));
    }

    /**
     * test case of filter patient history view 
     * @return void
     */
    public function test_filter_patient_history_view_data(){
        $admin = $this->adminLogin();
        $firstName = RequestClient::first()->name;
        $response = $this->actingAs($admin)->get('/search-patient-data', [
            'first_name' => $firstName,
            'last_name' => '',
            'email' => '',
            'phone_number' => '',
        ]);;

        $data = $response->getOriginalContent()->getData();
        $patientData = $data['patients']->items();

        $patientRecords = $patientData[0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.records.patientHistory');

        $this->assertTrue(array_key_exists('first_name', $patientRecords));
        $this->assertTrue(array_key_exists('last_name', $patientRecords));
        $this->assertTrue(array_key_exists('phone_number', $patientRecords));
        $this->assertTrue(array_key_exists('email', $patientRecords));
        $this->assertTrue(array_key_exists('street', $patientRecords));
        $this->assertTrue(array_key_exists('city', $patientRecords));
        $this->assertTrue(array_key_exists('state', $patientRecords));
    }

    /**
     * test case of patient record view 
     * @return void
     */
    public function test_patient_record_view_data()
    {
        $admin = $this->adminLogin();
        $id = Crypt::encrypt(RequestClient::first()->id);
        $response = $this->actingAs($admin)->get("/patient-records/$id");

        $records = $response->getOriginalContent()->getData();
        $patientData = $records['data'][0]->getAttributes();

        $confirmationNo = $records['data'][0]->getRelations()['request']->getAttributes();
        $status = $records['data'][0]->getRelations()['request']->getRelations()['statusTable']->getAttributes();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.records.patientRecords');

        $this->assertTrue(array_key_exists('first_name', $patientData));
        $this->assertTrue(array_key_exists('last_name', $patientData));
        $this->assertTrue(array_key_exists('confirmation_no', $confirmationNo));
        $this->assertTrue(array_key_exists('status_type', $status));
    }


    /**
     * test case of block history view 
     * @return void
     */
    public function test_block_history_view(){
        $admin = $this->adminLogin();
        $response = $this->actingAs($admin)->get('/block-history');

        $data = $response->getOriginalContent()->getData();
        $blockPatient = $data['blockData']->items();

        $blockRecords = $blockPatient[0]->getAttributes();
        $patientNameRecords = $blockPatient[0]->getRelations()['requestClient']->getAttributes();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.records.blockHistory');

        $this->assertTrue(array_key_exists('first_name', $patientNameRecords));
        $this->assertTrue(array_key_exists('phone_number', $blockRecords));
        $this->assertTrue(array_key_exists('email', $blockRecords));
        $this->assertTrue(array_key_exists('created_at', $blockRecords));
        $this->assertTrue(array_key_exists('reason', $blockRecords));
        $this->assertTrue(array_key_exists('is_active', $blockRecords));
        $this->assertTrue(array_key_exists('request_id', $blockRecords));
    }


    public function test_filtered_block_history_view(){
        $admin = $this->adminLogin();
        $email = BlockRequest::first()->email;
        $response = $this->actingAs($admin)->postJson('/block-history/search',[
            'patient_name'=> '',
            'date'=>'',
            'email'=> $email,
            'phone_number'=>'',
        ]);

        $data = $response->getOriginalContent()->getData();
        $blockPatient = $data['blockData']->items();
        $blockRecords = $blockPatient[0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('adminPage.records.blockHistory');

        $this->assertTrue(array_key_exists('patient_name', $blockRecords));
        $this->assertTrue(array_key_exists('id', $blockRecords));
        $this->assertTrue(array_key_exists('email', $blockRecords));
        $this->assertTrue(array_key_exists('created_date', $blockRecords));
        $this->assertTrue(array_key_exists('reason', $blockRecords));
        $this->assertTrue(array_key_exists('is_active', $blockRecords));
        $this->assertTrue(array_key_exists('request_id', $blockRecords));
        $this->assertTrue(array_key_exists('phone_number', $blockRecords));
        $this->assertTrue(array_key_exists('request_id', $blockRecords));
    }


    /**
     * test case of unblock patient
     * @return void
     */
    // public function test_unblock_patient(){
    //     $admin = $this->adminLogin();

    //     $id = RequestTable::where('status',10)->value('id');
    //     $response = $this->actingAs($admin)->get("/block-history/unblock/$id");

    //     $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('message', 'patient is unblock');
    // }
}
