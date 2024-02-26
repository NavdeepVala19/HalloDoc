<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;

class AdminDashboardController extends Controller
{
    protected $twilioService;
    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }
    public function sendSMS(Request $request)
    {

        $request->validate([
            'phone_number' => 'required',
            'message' => 'required',
        ]);
        // dd($request);
        $to = '+13159152948';
        $response = $this->twilioService->sendSMS($to,$request->phone_number, $request->message);
        dd($response);

        if ($response->sid) {
            return redirect('admin.dashboard')->with('success', 'SMS sent successfully');
        } else {
            return redirect('admin.dashboard')->with('success', 'failed to send SMS');
        }
    }
}
