<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Http\Response;

class SchedulingTest extends TestCase
{
    // ------------------ ADMIN SCHEDULING -------------------
    /**
     * Admin providers-on-call page can be rendered.
     */
    public function test_admin_providers_on_call_page_can_be_rendered(): void
    {
        $response = $this->get('/providers-on-call');

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin shifts for review page can be rendered.
     */
    public function test_admin_shifts_for_review_page_can_be_rendered(): void
    {
        $response = $this->get('/shifts-review');

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin Scheduling page can be rendered.
     */
    public function test_admin_scheduling_page_can_be_rendered(): void
    {
        $response = $this->get('/scheduling');

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Admin create single shift with valid data.
     */
    public function test_admin_create_single_shift_with_valid_data(): void
    {
        $startTime = date('H:i:00');
        $dateTime = Carbon::parse($startTime);
        $endTime = $dateTime->addMinutes(30);
        $endTimeString = $endTime->format('H:i:00');
        $response = $this->postJson('/create-shift', [
            'region' => 1,
            'physician' => 1,
            'shiftDate' => date('Y-m-d'),
            'shiftStartTime' => $startTime,
            'shiftEndTime' => $endTimeString,
            'is_repeat' => null,
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }
    /**
     * Admin create single shift with invalid data.
     */

    /**
     * Admin create single shift with empty data.
     */

    /**
     * Admin create repeating shifts.
     */
    public function test_admin_create_repeating_shifts(): void
    {
        $startTime = date('H:i:00');
        $dateTime = Carbon::parse($startTime);
        $endTime = $dateTime->addMinutes(30);
        $endTimeString = $endTime->format('H:i:00');
        $response = $this->postJson('/create-shift', [
            'region' => 1,
            'physician' => 1,
            'shiftDate' => date('Y-m-d'),
            'shiftStartTime' => $startTime,
            'shiftEndTime' => $endTimeString,
            'is_repeat' => 'on',
            'checkbox[]' => 0,
            'repeatEnd' => 2,
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }


    // ------------------ PROVIDER SCHEDULING -------------------
    /**
     * Provider Scheduling page can be rendered.
     */
    public function test_provider_scheduling_page_can_be_rendered(): void
    {
        $response = $this->get('/');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Provider create single shift.
     */
    public function test_provider_create_single_shift(): void
    {
        $startTime = date('H:i:00');
        $dateTime = Carbon::parse($startTime);
        $endTime = $dateTime->addMinutes(30);
        $endTimeString = $endTime->format('H:i:00');
        $response = $this->postJson('/create-shift', [
            'region' => 1,
            'physician' => 1,
            'shiftDate' => date('Y-m-d'),
            'shiftStartTime' => $startTime,
            'shiftEndTime' => $endTimeString,
            'is_repeat' => null,
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * Provider create repeating shifts.
     */
    public function test_provider_create_repeating_shifts(): void
    {
        $startTime = date('H:i:00');
        $dateTime = Carbon::parse($startTime);
        $endTime = $dateTime->addMinutes(30);
        $endTimeString = $endTime->format('H:i:00');
        $response = $this->postJson('/create-shift', [
            'region' => 1,
            'physician' => 1,
            'shiftDate' => date('Y-m-d'),
            'shiftStartTime' => $startTime,
            'shiftEndTime' => $endTimeString,
            'is_repeat' => 'on',
            'checkbox[]' => 0,
            'repeatEnd' => 2,
        ]);

        $response->assertStatus(Response::HTTP_FOUND);
    }
}
