<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class SchedulingController extends Controller
{
    public function schedulingCalendarView()
    {
        return view('adminPage.scheduling.scheduling');
    }
    public function providerData()
    {
        $providers = Provider::get();
        $formattedData = [];
        foreach ($providers as $provider) {
            $formattedData[] = [
                'id' => $provider->id,
                'physician' => $provider->first_name .  " " . $provider->last_name,
                'photo' => $provider->photo
            ];
        }
        return response()->json($formattedData);

    }
    public function providersOnCall()
    {
        return view('adminPage.scheduling.providerOnCall');
    }
    public function shiftsReviewView()
    {
        return view('adminPage.scheduling.shiftsForReview');
    }
}
