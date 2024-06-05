<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserRoles;
use App\Models\RequestClient;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;

class RecordsTest extends TestCase
{
    public function admin()
    {
        $adminId = UserRoles::where('role_id', 1)->first()->user_id;
        return User::where('id', $adminId)->first();
    }

    // search record page can be rendered
    public function test_search_record_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/search-records');

        $records = $response->getOriginalContent()->getData()['records'][0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.records.searchRecords')
            ->assertViewHas('records');

        $this->assertTrue(array_key_exists('request_type_id', $records));
        $this->assertTrue(array_key_exists('id', $records));
        $this->assertTrue(array_key_exists('email', $records));
        $this->assertTrue(array_key_exists('street', $records));
        $this->assertTrue(array_key_exists('first_name', $records));
        $this->assertTrue(array_key_exists('city', $records));
        $this->assertTrue(array_key_exists('state', $records));
        $this->assertTrue(array_key_exists('zipcode', $records));
        $this->assertTrue(array_key_exists('notes', $records));
        $this->assertTrue(array_key_exists('physician_notes', $records));
        $this->assertTrue(array_key_exists('admin_notes', $records));
        $this->assertTrue(array_key_exists('created_date', $records));
        $this->assertTrue(array_key_exists('closed_date', $records));
    }

    // search records filter with existing data
    public function test_search_records_filter_with_existing_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/search-records/search', [
                'request_status' => '',
                'patient_name' => 'Kevin',
                'request_type' => '',
                'from_date_of_service' => '',
                'to_date_of_service' => '',
                'provider_name' => '',
                'email' => '',
                'phone_number' => '',
            ]);

        $records = $response->getOriginalContent()->getData()['records'][0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.records.searchRecords')
            ->assertViewHas('records');

        $this->assertTrue(array_key_exists('request_type_id', $records));
        $this->assertTrue(array_key_exists('id', $records));
        $this->assertTrue(array_key_exists('email', $records));
        $this->assertTrue(array_key_exists('street', $records));
        $this->assertTrue(array_key_exists('first_name', $records));
        $this->assertTrue(array_key_exists('city', $records));
        $this->assertTrue(array_key_exists('state', $records));
        $this->assertTrue(array_key_exists('zipcode', $records));
        $this->assertTrue(array_key_exists('patient_notes', $records));
        $this->assertTrue(array_key_exists('physician_notes', $records));
        $this->assertTrue(array_key_exists('admin_notes', $records));
        $this->assertTrue(array_key_exists('created_date', $records));
        $this->assertTrue(array_key_exists('closed_date', $records));
    }

    // search records export data
    public function test_search_records_export_data()
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)
            ->postJson('/search-records/export', [
                'request_status' => '',
                'patient_name' => 'Kevin',
                'request_type' => '',
                'from_date_of_service' => '',
                'to_date_of_service' => '',
                'provider_name' => '',
                'email' => '',
                'phone_number' => '',
            ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * delete search record
     * @return void
     */
    public function test_delete_search_record()
    {
        $admin = $this->admin();

        $id = RequestClient::orderBy('id', 'desc')->first()->id;

        $response = $this->actingAs($admin)->get("/search-records/delete/$id");

        $response->assertStatus(Response::HTTP_FOUND)->assertSessionHas('message', 'record is permanently delete');
    }

    // email logs page can be rendered
    public function test_email_logs_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/email-logs');

        $emails = $response->getOriginalContent()->getData()['emails'][0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.records.emailLogs')
            ->assertViewHas('emails');

        $this->assertTrue(array_key_exists('role_id', $emails));
        $this->assertTrue(array_key_exists('recipient_name', $emails));
        $this->assertTrue(array_key_exists('email', $emails));
        $this->assertTrue(array_key_exists('subject_name', $emails));
        $this->assertTrue(array_key_exists('confirmation_number', $emails));
        $this->assertTrue(array_key_exists('create_date', $emails));
        $this->assertTrue(array_key_exists('sent_date', $emails));
        $this->assertTrue(array_key_exists('is_email_sent', $emails));
        $this->assertTrue(array_key_exists('sent_tries', $emails));
        $this->assertTrue(array_key_exists('action', $emails));
    }

    /**
     * email logs filtered data
     * @return void
     */
    public function test_email_logs_filtered_data()
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)
            ->get('/search-email-logs', [
                'role_id' => '1',
                'receiver_name' => '',
                'created_date' => '',
                'sent_date' => '',
                'email' => '',
            ]);

        $emails = $response->getOriginalContent()->getData()['emails'][0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.records.emailLogs')
            ->assertViewHas('emails');

        $this->assertTrue(array_key_exists('role_id', $emails));
        $this->assertTrue(array_key_exists('recipient_name', $emails));
        $this->assertTrue(array_key_exists('email', $emails));
        $this->assertTrue(array_key_exists('subject_name', $emails));
        $this->assertTrue(array_key_exists('confirmation_number', $emails));
        $this->assertTrue(array_key_exists('create_date', $emails));
        $this->assertTrue(array_key_exists('sent_date', $emails));
        $this->assertTrue(array_key_exists('is_email_sent', $emails));
        $this->assertTrue(array_key_exists('sent_tries', $emails));
        $this->assertTrue(array_key_exists('action', $emails));
    }

    // sms logs page can be rendered
    public function test_sms_logs_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/sms-logs');

        $smsLogs = $response->getOriginalContent()->getData()['smsLogs'][0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.records.smsLogs')
            ->assertViewHas('smsLogs');

        $this->assertTrue(array_key_exists('role_id', $smsLogs));
        $this->assertTrue(array_key_exists('recipient_name', $smsLogs));
        $this->assertTrue(array_key_exists('mobile_number', $smsLogs));
        $this->assertTrue(array_key_exists('confirmation_number', $smsLogs));
        $this->assertTrue(array_key_exists('created_date', $smsLogs));
        $this->assertTrue(array_key_exists('sent_date', $smsLogs));
        $this->assertTrue(array_key_exists('is_sms_sent', $smsLogs));
        $this->assertTrue(array_key_exists('sent_tries', $smsLogs));
        $this->assertTrue(array_key_exists('action', $smsLogs));
    }

    /**
     * sms logs filtered data
     * @return void
     */
    public function test_sms_logs_filtered_data()
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)->get('/sms-logs/search', [
            'role_type' => '2',
            'receiver_name' => '',
            'created_date' => '',
            'sent_date' => '',
            'phone_number' => '',
        ]);

        $smsLogs = $response->getOriginalContent()->getData()['smsLogs'][0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.records.smsLogs')
            ->assertViewHas('smsLogs');

        $this->assertTrue(array_key_exists('role_id', $smsLogs));
        $this->assertTrue(array_key_exists('recipient_name', $smsLogs));
        $this->assertTrue(array_key_exists('mobile_number', $smsLogs));
        $this->assertTrue(array_key_exists('confirmation_number', $smsLogs));
        $this->assertTrue(array_key_exists('created_date', $smsLogs));
        $this->assertTrue(array_key_exists('sent_date', $smsLogs));
        $this->assertTrue(array_key_exists('is_sms_sent', $smsLogs));
        $this->assertTrue(array_key_exists('sent_tries', $smsLogs));
        $this->assertTrue(array_key_exists('action', $smsLogs));
    }

    // patient history page can be rendered
    public function test_patient_history_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/patient-history');

        $patients = $response->getOriginalContent()->getData()['patients'][0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.records.patientHistory')
            ->assertViewHas('patients');

        $this->assertTrue(array_key_exists('first_name', $patients));
        $this->assertTrue(array_key_exists('last_name', $patients));
        $this->assertTrue(array_key_exists('phone_number', $patients));
        $this->assertTrue(array_key_exists('email', $patients));
        $this->assertTrue(array_key_exists('street', $patients));
        $this->assertTrue(array_key_exists('city', $patients));
        $this->assertTrue(array_key_exists('state', $patients));
    }

    /**
     * patient history filter data
     * @return void
     */
    public function test_patient_history_filter_data()
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)
            ->get('/search-patient-data', [
                'first_name' => 'Kevin',
                'last_name' => '',
                'email' => '',
                'phone_number' => '',
            ]);

        $patients = $response->getOriginalContent()->getData()['patients'][0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.records.patientHistory')
            ->assertViewHas('patients');

        $this->assertTrue(array_key_exists('first_name', $patients));
        $this->assertTrue(array_key_exists('last_name', $patients));
        $this->assertTrue(array_key_exists('phone_number', $patients));
        $this->assertTrue(array_key_exists('email', $patients));
        $this->assertTrue(array_key_exists('street', $patients));
        $this->assertTrue(array_key_exists('city', $patients));
        $this->assertTrue(array_key_exists('state', $patients));
    }

    // patient record page can be rendered
    public function test_patient_record_page_can_be_rendered()
    {
        $admin = $this->admin();

        $requestClientId = RequestClient::first()->id;

        $id = Crypt::encrypt($requestClientId);

        $response = $this->actingAs($admin)->get('/patient-records/{' . $id . '}');

        $data = $response->getOriginalContent()->getData()['data'][0]->getAttributes();
        $request = $response->getOriginalContent()->getData()['data'][0]->getRelations()['request']->getAttributes();
        $statusTable = $response->getOriginalContent()->getData()['data'][0]->getRelations()['request']->getRelations()['statusTable']->getAttributes();
        $provider = $response->getOriginalContent()->getData()['data'][0]->getRelations()['request']->getRelations()['provider']->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.records.patientRecords')
            ->assertViewHasAll(['data', 'documentCount', 'isFinalize']);

        $this->assertTrue(array_key_exists('first_name', $data));
        $this->assertTrue(array_key_exists('last_name', $data));
        $this->assertTrue(array_key_exists('phone_number', $data));
        $this->assertTrue(array_key_exists('email', $data));
        $this->assertTrue(array_key_exists('street', $data));
        $this->assertTrue(array_key_exists('city', $data));
        $this->assertTrue(array_key_exists('state', $data));
        $this->assertTrue(array_key_exists('confirmation_no', $request));
        $this->assertTrue(array_key_exists('id', $request));
        $this->assertTrue(array_key_exists('status_type', $statusTable));
        $this->assertTrue(array_key_exists('first_name', $provider));
        $this->assertTrue(array_key_exists('last_name', $provider));
    }

    // block history page can be rendered
    public function test_block_history_page_can_be_rendered()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/block-history');

        $blockData = $response->getOriginalContent()->getData()['blockData'][0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.records.blockHistory')
            ->assertViewHas('blockData');

        $this->assertTrue(array_key_exists('patient_name', $blockData));
        $this->assertTrue(array_key_exists('phone_number', $blockData));
        $this->assertTrue(array_key_exists('email', $blockData));
        $this->assertTrue(array_key_exists('created_date', $blockData));
        $this->assertTrue(array_key_exists('reason', $blockData));
        $this->assertTrue(array_key_exists('is_active', $blockData));
        $this->assertTrue(array_key_exists('request_id', $blockData));
    }

    // block history filtered data
    public function test_block_history_filtered_data()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson('/block-history/search', [
                'patient_name' => 'Aline',
                'date' => '',
                'email' => '',
                'phone_number' => '',
            ]);

        $blockData = $response->getOriginalContent()->getData()['blockData'][0]->getAttributes();

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('adminPage.records.blockHistory')
            ->assertViewHas('blockData');

        $this->assertTrue(array_key_exists('patient_name', $blockData));
        $this->assertTrue(array_key_exists('id', $blockData));
        $this->assertTrue(array_key_exists('email', $blockData));
        $this->assertTrue(array_key_exists('created_date', $blockData));
        $this->assertTrue(array_key_exists('reason', $blockData));
        $this->assertTrue(array_key_exists('is_active', $blockData));
        $this->assertTrue(array_key_exists('request_id', $blockData));
        $this->assertTrue(array_key_exists('phone_number', $blockData));
        $this->assertTrue(array_key_exists('request_id', $blockData));
    }
}
