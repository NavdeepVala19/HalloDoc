<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Regions;
use Illuminate\Http\Request;

class SchedulingController extends Controller
{
    public function schedulingCalendarView()
    {
        $regions = Regions::get();
        return view('adminPage.scheduling.scheduling', compact('regions'));
    }
    public function providerData()
    {
        $providers = Provider::get();
        $formattedData = [];
        foreach ($providers as $provider) {
            $formattedData[] = [
                'id' => $provider->id,
                'physician' => $provider->first_name .  " " . $provider->last_name,
                'photo' => $provider->photo,
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
    public function createShiftData(Request $request)
    {
        dd($request->all());
    }
}
