<?php

namespace App\Http\Controllers;

use App\Models\PhysicianRegion;
use App\Models\Shift;
use App\Models\Regions;
use App\Models\Provider;
use App\Models\ShiftDetail;
use Illuminate\Http\Request;
use App\Models\ShiftDetailRegion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SchedulingController extends Controller
{
    // Display Admin Scheduling page
    public function schedulingCalendarView()
    {
        $regions = Regions::get();
        return view('adminPage.scheduling.scheduling', compact('regions'));
    }

    // Fetch provider data to display in calendar (resources)
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

    // Filter shift as per the region selected
    public function shiftFilter($id)
    {
        // If no region selected, return all the shifts
        if ($id == 0) {
            $shifts = Shift::with('shiftDetail')->get();

            $formattedShift = $shifts->map(function ($event) {
                return [
                    'shiftId' => $event->id,
                    'title' => $event->provider->first_name . " " . $event->provider->last_name,
                    'shiftDate' => $event->shiftDetail->shift_date,
                    'startTime' => $event->shiftDetail->start_time,
                    'endTime' => $event->shiftDetail->end_time,
                    'resourceId' => $event->physician_id,
                    'physician_id' => $event->physician_id,
                    'region_id' => $event->shiftDetail->shiftDetailRegion->region_id,
                    'region_name' => $event->shiftDetail->shiftDetailRegion->region->region_name,
                    'is_repeat' => $event->is_repeat,
                    'week_days' => explode(',', $event->week_days),
                    'repeat_upto' => $event->repeat_upto,
                    'status' => $event->shiftDetail->status
                ];
            });

            return response()->json($formattedShift->toArray());
        }
        $shiftDetailIds = ShiftDetailRegion::where('region_id', $id)->pluck('shift_detail_id')->toArray();
        // $shifts = ShiftDetail::where('id', $shiftDetailIds)->pluck('shift_id');
        $filteredShift = ShiftDetail::whereIn('id', $shiftDetailIds)->pluck('shift_id');
        $shifts = Shift::with('shiftDetail')->whereIn('id', $filteredShift)->get();

        $formattedShift = $shifts->map(function ($event) {
            return [
                'shiftId' => $event->id,
                'title' => $event->provider->first_name . " " . $event->provider->last_name,
                'shiftDate' => $event->shiftDetail->shift_date,
                'startTime' => $event->shiftDetail->start_time,
                'endTime' => $event->shiftDetail->end_time,
                'resourceId' => $event->physician_id,
                'physician_id' => $event->physician_id,
                'region_id' => $event->shiftDetail->shiftDetailRegion->region_id,
                'region_name' => $event->shiftDetail->shiftDetailRegion->region->region_name,
                'is_repeat' => $event->is_repeat,
                'week_days' => explode(',', $event->week_days),
                'repeat_upto' => $event->repeat_upto,
                'status' => $event->shiftDetail->status
            ];
        });

        return response()->json($formattedShift->toArray());
    }

    // Display ProvidersOnCall page with shift details
    public function providersOnCall()
    {
        $regions = Regions::get();
        $currentDate = now()->toDateString();
        $currentTime = now()->format('H:i');

        $onCallShifts = ShiftDetail::with('getShiftData')->where('shift_date', $currentDate)
            ->where('start_time', '<=', $currentTime)->where('end_time', '>=', $currentTime)->get();

        $onCallPhysicianIds = $onCallShifts->whereNotNull('getShiftData.physician_id')->pluck('getShiftData.physician_id')->unique()->toArray();
        $onCallPhysicians = Provider::whereIn('id', $onCallPhysicianIds)->get();

        $offDutyPhysicians = Provider::whereNotIn('id', $onCallPhysicianIds)->get();

        return view('adminPage.scheduling.providerOnCall', compact('regions', 'onCallPhysicians', 'offDutyPhysicians'));
    }

    // Filter Providers based on region selected for Providers on Call page (AJAX Call)
    public function filterProviderByRegion($id)
    {
        $currentDate = now()->toDateString();
        $currentTime = now()->format('H:i');

        $onCallShifts = ShiftDetail::with('getShiftData')->where('shift_date', $currentDate)
            ->where('start_time', '<=', $currentTime)->where('end_time', '>=', $currentTime)->get();

        $onCallPhysicianIds = $onCallShifts->whereNotNull('getShiftData.physician_id')->pluck('getShiftData.physician_id')->unique()->toArray();
        $offDutyPhysicianIds = Shift::whereNotIn('physician_id', $onCallPhysicianIds)->pluck('physician_id')->unique()->toArray();

        // If all regions selected display all the physicians
        if ($id == 0) {
            $onDutyFilterPhysicianIds = PhysicianRegion::whereIn('provider_id', $onCallPhysicianIds)->pluck('provider_id')->unique()->toArray();
            $onCallPhysicians = Provider::whereIn('id', $onDutyFilterPhysicianIds)->get();

            $offDutyPhysicians = Provider::whereNotIn('id', $onCallPhysicianIds)->get();

            $physicians = ['onDutyPhysicians' => $onCallPhysicians, 'offDutyPhysicians' => $offDutyPhysicians];
            return response()->json($physicians);
        } else {
            $onDutyFilterPhysicianIds = PhysicianRegion::whereIn('provider_id', $onCallPhysicianIds)->where('region_id', $id)->pluck('provider_id')->unique()->toArray();
            $onCallPhysicians = Provider::whereIn('id', $onDutyFilterPhysicianIds)->get();

            $offDutyFilterPhysicianIds = PhysicianRegion::whereNotIn('provider_id', $onCallPhysicianIds)->where('region_id', $id)->pluck('provider_id')->unique()->toArray();
            $offDutyPhysicians = Provider::whereIn('id', $offDutyFilterPhysicianIds)->get();

            $physicians = ['onDutyPhysicians' => $onCallPhysicians, 'offDutyPhysicians' => $offDutyPhysicians];
            return response()->json($physicians);
        }
    }

    // Display ShiftsForReview Page
    public function shiftsReviewView()
    {
        $shiftDetails = ShiftDetail::whereHas('getShiftData')->where('status', 'pending')->paginate(10);
        $regions = Regions::get();

        return view('adminPage.scheduling.shiftsForReview', compact('shiftDetails', 'regions'));
    }

    // create new shift
    public function createShiftData(Request $request)
    {
        $request->validate([
            'region' => 'required',
            'physician' => 'required',
            'shiftDate' => 'required',
            'shiftStartTime' => 'required',
            'shiftEndTime' => 'required|after:shiftStartTime'
        ]);
        // Check whether the shift created for provider is already having shift for that time period
        $shifts = Shift::with('shiftDetail')->get();
        $currentShifts = $shifts->whereIn("start_date", $request->shiftDate);
        // check for each shifts, whether it have the same time period or in-between time period
        foreach ($currentShifts as $currentShift) {
            if ($currentShift->physician_id == $request->physician) {
                // for the currentShift if the physician_id matches requested physician check for the time period
                $shiftStartTimeCurrent = $currentShift->shiftDetail->start_time;
                $shiftEndTimeCurrent = $currentShift->shiftDetail->end_time;

                if (
                    $shiftStartTimeCurrent <= $request->shiftStartTime && $shiftEndTimeCurrent > $request->shiftStartTime ||
                    $shiftStartTimeCurrent <= $request->shiftEndTime && $shiftEndTimeCurrent > $request->shiftEndTime
                ) {
                    return redirect()->back()->with('shiftOverlap', "Provider you selected have an shift during the time period you provided");
                }
            }
        };
        if ($request->checkbox) {
            $weekDays = implode(',', $request->checkbox);
        } else {
            $weekDays = null;
        }
        $request->validate(['region' => 'required|in:1,2,3,4,5']);
        if ($request['is_repeat'] == true) {
            $is_repeat = 1;
        } else {
            $is_repeat = 0;
        }
        $shift =  Shift::create([
            'physician_id' => $request['physician'],
            'start_date' => $request['shiftDate'],
            'is_repeat' => $is_repeat,
            'week_days' => $weekDays,
            'repeat_upto' => $request['repeatEnd'],
            'created_by' => Auth::user()->id
        ]);
        $shiftDetail = ShiftDetail::create([
            'shift_id' => $shift->id,
            'shift_date' => $request['shiftDate'],
            'start_time' => $request['shiftStartTime'],
            'end_time' => $request['shiftEndTime'],
            'status' => 2
        ]);
        $shiftDetailRegion = ShiftDetailRegion::create([
            'shift_detail_id' => $shiftDetail->id,
            'region_id' => $request['region']
        ]);
        ShiftDetail::where('shift_id', $shift->id)->update(['region_id' => $shiftDetailRegion->id]);
        return redirect()->back()->with('shiftAdded', "Shift Added Successfully");
    }


    // Get all the shifts from database and convert it into json format to be used by FullCalendar
    public function eventsData()
    {
        $shifts = Shift::with('shiftDetail')->get();

        $formattedShift = $shifts->map(function ($event) {
            return [
                'shiftId' => $event->id,
                'title' => $event->provider->first_name . " " . $event->provider->last_name,
                'shiftDate' => $event->shiftDetail->shift_date,
                'startTime' => $event->shiftDetail->start_time,
                'endTime' => $event->shiftDetail->end_time,
                'resourceId' => $event->physician_id,
                'physician_id' => $event->physician_id,
                'region_id' => $event->shiftDetail->shiftDetailRegion->region_id,
                'region_name' => $event->shiftDetail->shiftDetailRegion->region->region_name,
                'is_repeat' => $event->is_repeat,
                'week_days' => explode(',', $event->week_days),
                'repeat_upto' => $event->repeat_upto,
                'status' => $event->shiftDetail->status
            ];
        });

        return response()->json($formattedShift->toArray());
    }

    // Edit already existing shift
    public function editShift(Request $request)
    {
        if ($request['action'] == 'return') {
            $status = ShiftDetail::where('shift_id', $request->shiftId)->first();
            if ($status->status == 'approved') {
                ShiftDetail::where('shift_id', $request->shiftId)->update(['status' => 1]);
                return redirect()->back()->with('shiftPending', 'Shift Status changed from Approved to Pending');
            } else {
                ShiftDetail::where('shift_id', $request->shiftId)->update(['status' => 2]);
                return redirect()->back()->with('shiftApproved', 'Shift Status changed from Pending to Approved');
            }
        } else if ($request['action'] == 'save') {
            // Check whether the shift created for provider is already having shift for that time period
            $shifts = Shift::with('shiftDetail')->get();
            $currentShifts = $shifts->whereIn("start_date", $request->shiftDate);
            // check for each shifts, whether it have the same time period or in-between time period
            foreach ($currentShifts as $currentShift) {
                if ($currentShift->physician_id == $request->physician) {
                    // for the currentShift if the physician_id matches requested physician check for the time period
                    $shiftStartTimeCurrent = $currentShift->shiftDetail->start_time;
                    $shiftEndTimeCurrent = $currentShift->shiftDetail->end_time;

                    if (
                        $shiftStartTimeCurrent <= $request->shiftStartTime && $shiftEndTimeCurrent > $request->shiftStartTime ||
                        $shiftStartTimeCurrent <= $request->shiftEndTime && $shiftEndTimeCurrent > $request->shiftEndTime
                    ) {
                        return redirect()->back()->with('shiftOverlap', "You have an shift during the time period you provided");
                    }
                }
            };
            Shift::where('id', $request->shiftId)->update([
                'start_date' => $request->shiftDate,
            ]);

            ShiftDetail::where('shift_id', $request->shiftId)->update([
                'shift_date' => $request->shiftDate,
                'start_time' => $request->shiftTimeStart,
                'end_time' => $request->shiftTimeEnd,
                // 'modified_by' => Auth::user()->id
            ]);

            return redirect()->back()->with('shiftEdited', 'Shift Edited Successfully!');
        } else {
            Shift::where('id', $request->shiftId)->delete();

            return redirect()->back()->with("shiftDeleted", "Shift Deleted Successfully!");
        }
    }
    // Change status of shifts (Approved or Pending)
    public function shiftAction(Request $request)
    {
        if (empty($request->selected)) {
            return redirect()->back()->with('selectOption', "Select Atleast one shift for performing operation!");
        }
        if ($request->action == 'approve') {
            ShiftDetail::whereIn('id', $request->selected)->update(['status' => 2]);
            return redirect()->back();
        } else {
            $shifts = ShiftDetail::whereIn('id', $request->selected)->get();
            foreach ($shifts as $shift) {
                $shift->getShiftData->delete();
            }
            return redirect()->back();
        }
    }
    // filter shifts in shiftsForReview page based on region selected
    public function filterRegions(Request $request)
    {
        $allShifts = ShiftDetailRegion::where('region_id', $request->regionId)->pluck('shift_detail_id')->toArray();
        $shiftDetails = ShiftDetail::whereHas('getShiftData')->whereIn('id', $allShifts)->where('status', 'pending')->paginate(10);
        if ($request->regionId == 0) {
            $shiftDetails = ShiftDetail::whereHas('getShiftData')->where('status', 'pending')->paginate(10);
            $data = view('adminPage.scheduling.filteredShifts')->with('shiftDetails', $shiftDetails)->render();
        } else {
            $data = view('adminPage.scheduling.filteredShifts')->with('shiftDetails', $shiftDetails)->render();
        }

        return response()->json(['html' => $data]);
    }
}
