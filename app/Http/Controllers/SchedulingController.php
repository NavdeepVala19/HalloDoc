<?php

namespace App\Http\Controllers;

use App\Models\PhysicianRegion;
use App\Models\Provider;
use App\Models\Regions;
use App\Models\Shift;
use App\Models\ShiftDetail;
use App\Models\ShiftDetailRegion;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DatePeriod;
use Illuminate\Http\Request;

class SchedulingController extends Controller
{
    /**
     * Display the Admin Scheduling page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function schedulingCalendarView()
    {
        $regions = Regions::get();
        return view('adminPage.scheduling.scheduling', compact('regions'));
    }

    /**
     * Fetch provider data to display in the calendar as resources.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function providerData()
    {
        $providers = Provider::get();
        $formattedData = [];
        foreach ($providers as $provider) {
            $formattedData[] = [
                'id' => $provider->id,
                'physician' => $provider->first_name.' '.$provider->last_name,
                'photo' => $provider->photo,
            ];
        }
        return response()->json($formattedData);
    }

    /**
     * Filter shifts based on the region selected.
     *
     * @param int $id The ID of the region to filter shifts by.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function shiftFilter($id)
    {
        // If no region selected, return all the shifts
        if ($id === 0) {
            $shiftDetails = ShiftDetail::with(['getShiftData', 'shiftDetailRegion'])->get();

            $formattedShift = $shiftDetails->map(function ($event) {
                return [
                    'shiftId' => $event->getShiftData->id,
                    'shiftDetailId' => $event->id,
                    'title' => $event->getShiftData->provider->first_name.' '.$event->getShiftData->provider->last_name,
                    'shiftDate' => $event->shift_date,
                    'startTime' => $event->start_time,
                    'endTime' => $event->end_time,
                    'resourceId' => $event->getShiftData->physician_id,
                    'physician_id' => $event->getShiftData->physician_id,
                    'region_id' => $event->shiftDetailRegion->region_id,
                    'region_name' => $event->shiftDetailRegion->region->region_name,
                    'is_repeat' => $event->getShiftData->is_repeat,
                    'week_days' => explode(',', $event->getShiftData->week_days),
                    'repeat_upto' => $event->getShiftData->repeat_upto,
                    'status' => $event->status,
                ];
            });

            return response()->json($formattedShift->toArray());
        }

        $shiftDetailIds = ShiftDetailRegion::where('region_id', $id)->pluck('shift_detail_id')->toArray();
        $shiftDetails = ShiftDetail::whereIn('id', $shiftDetailIds)->get();

        $formattedShift = $shiftDetails->map(function ($event) {
            return [
                'shiftId' => $event->getShiftData->id,
                'shiftDetailId' => $event->id,
                'title' => $event->getShiftData->provider->first_name.' '.$event->getShiftData->provider->last_name,
                'shiftDate' => $event->shift_date,
                'startTime' => $event->start_time,
                'endTime' => $event->end_time,
                'resourceId' => $event->getShiftData->physician_id,
                'physician_id' => $event->getShiftData->physician_id,
                'region_id' => $event->shiftDetailRegion->region_id,
                'region_name' => $event->shiftDetailRegion->region->region_name,
                'is_repeat' => $event->getShiftData->is_repeat,
                'week_days' => explode(',', $event->getShiftData->week_days),
                'repeat_upto' => $event->getShiftData->repeat_upto,
                'status' => $event->status,
            ];
        });

        return response()->json($formattedShift->toArray());
    }

    /**
     * Display the ProvidersOnCall page with shift details.
     *
     * @return \Illuminate\View\View
     */
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

    /**
     * Filter providers based on region selected for Providers on Call page (AJAX Call).
     *
     * @param int $id The ID of the selected region.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function filterProviderByRegion($id)
    {
        $currentDate = now()->toDateString();
        $currentTime = now()->format('H:i');

        $onCallShifts = ShiftDetail::with('getShiftData')->where('shift_date', $currentDate)
            ->where('start_time', '<=', $currentTime)->where('end_time', '>=', $currentTime)->get();

        $onCallPhysicianIds = $onCallShifts->whereNotNull('getShiftData.physician_id')->pluck('getShiftData.physician_id')->unique()->toArray();
        // $offDutyPhysicianIds = Shift::whereNotIn('physician_id', $onCallPhysicianIds)->pluck('physician_id')->unique()->toArray();

        // If all regions selected display all the physicians
        if ($id === 0) {
            $onDutyFilterPhysicianIds = PhysicianRegion::whereIn('provider_id', $onCallPhysicianIds)->pluck('provider_id')->unique()->toArray();
            $onCallPhysicians = Provider::whereIn('id', $onDutyFilterPhysicianIds)->get();

            $offDutyPhysicians = Provider::whereNotIn('id', $onCallPhysicianIds)->get();

            $physicians = ['onDutyPhysicians' => $onCallPhysicians, 'offDutyPhysicians' => $offDutyPhysicians];
            return response()->json($physicians);
        }
        $onDutyFilterPhysicianIds = PhysicianRegion::whereIn('provider_id', $onCallPhysicianIds)->where('region_id', $id)->pluck('provider_id')->unique()->toArray();
        $onCallPhysicians = Provider::whereIn('id', $onDutyFilterPhysicianIds)->get();

        $offDutyFilterPhysicianIds = PhysicianRegion::whereNotIn('provider_id', $onCallPhysicianIds)->where('region_id', $id)->pluck('provider_id')->unique()->toArray();
        $offDutyPhysicians = Provider::whereIn('id', $offDutyFilterPhysicianIds)->get();

        $physicians = ['onDutyPhysicians' => $onCallPhysicians, 'offDutyPhysicians' => $offDutyPhysicians];
        return response()->json($physicians);
    }

    /**
     * Display the ShiftsForReview Page.
     *
     * @return \Illuminate\View\View
     */
    public function shiftsReviewView()
    {
        $shiftDetails = ShiftDetail::whereHas('getShiftData')->where('status', 'pending')->paginate(10);
        $regions = Regions::get();

        return view('adminPage.scheduling.shiftsForReview', compact('shiftDetails', 'regions'));
    }

    /**
     * Create a new shift.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createShiftData(Request $request)
    {
        $request->validate([
            'region' => 'required|in:1,2,3,4,5',
            'physician' => 'required',
            'shiftDate' => 'required|after:yesterday',
            'shiftStartTime' => 'required',
            'shiftEndTime' => 'required|after:shiftStartTime',
        ]);
        // Check whether the shift created for provider is already having shift for that time period
        $shifts = Shift::with('shiftDetail')->get();
        $currentShifts = $shifts->whereIn('start_date', $request->shiftDate);
        // check for each shifts, whether it have the same time period or in-between time period
        foreach ($currentShifts as $currentShift) {
            if ($currentShift->physician_id === $request->physician) {
                // for the currentShift if the physician_id matches requested physician check for the time period
                $shiftStartTimeCurrent = $currentShift->shiftDetail->start_time;
                $shiftEndTimeCurrent = $currentShift->shiftDetail->end_time;

                if (
                    $shiftStartTimeCurrent <= $request->shiftStartTime && $shiftEndTimeCurrent > $request->shiftStartTime ||
                    $shiftStartTimeCurrent <= $request->shiftEndTime && $shiftEndTimeCurrent > $request->shiftEndTime
                ) {
                    return redirect()->back()->with('shiftOverlap', 'Provider you selected have an shift during the time period you provided');
                }
            }
        }

        if ($request->checkbox) {
            $weekDays = implode(',', $request->checkbox);
        } else {
            $weekDays = null;
        }

        if ($request['is_repeat']) {
            $is_repeat = 1;
        } else {
            $is_repeat = 0;
        }

        $shift = Shift::create([
            'physician_id' => $request['physician'],
            'start_date' => $request['shiftDate'],
            'is_repeat' => $is_repeat,
            'week_days' => $weekDays,
            'repeat_upto' => $request['repeatEnd'],
            'created_by' => 1,
        ]);
        $shiftDetail = ShiftDetail::create([
            'shift_id' => $shift->id,
            'shift_date' => $request['shiftDate'],
            'start_time' => $request['shiftStartTime'],
            'end_time' => $request['shiftEndTime'],
            'status' => 2,
        ]);

        $shiftDetailRegion = ShiftDetailRegion::create([
            'shift_detail_id' => $shiftDetail->id,
            'region_id' => $request['region'],
        ]);

        ShiftDetail::where('shift_id', $shift->id)->update(['region_id' => $shiftDetailRegion->id]);

        if ($is_repeat === 1) {
            $startDate = Carbon::parse($request['shiftDate']);
            $endDate = Carbon::parse($request['shiftDate']);

            // Set the end date based on the value of repeatEnd
            switch ($request['repeatEnd']) {
                case 2:
                    $endDate->addDays(14);
                    break;
                case 3:
                    $endDate->addDays(21);
                    break;
                case 4:
                    $endDate->addDays(28);
                    break;
                default:
                    // Set the end date to the start date if repeatEnd is not 2, 3, or 4
                    $endDate = $startDate;
                    break;
            }

            // Create a DatePeriod object to generate a range of dates between the start and end dates
            $interval = CarbonInterval::day();
            $dateRange = new DatePeriod($startDate, $interval, $endDate);

            // Loop through the range of dates and create a ShiftDetail record for each date that is selected
            foreach ($dateRange as $date) {
                if (in_array($date->format('w'), $request->checkbox)) {
                    $shiftDetail = ShiftDetail::create([
                        'shift_id' => $shift->id,
                        'shift_date' => $date->format('Y-m-d'),
                        'start_time' => $request['shiftStartTime'],
                        'end_time' => $request['shiftEndTime'],
                        'status' => 2,
                    ]);

                    $shiftDetailRegion = ShiftDetailRegion::create([
                        'shift_detail_id' => $shiftDetail->id,
                        'region_id' => $request['region'],
                    ]);

                    ShiftDetail::where('id', $shiftDetail->id)->update(['region_id' => $shiftDetailRegion->id]);
                }
            }
        }

        return redirect()->back()->with('shiftAdded', 'Shift Added Successfully');
    }

    /**
     * Get all shifts from the database and convert them into JSON format for FullCalendar.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function eventsData()
    {
        $shiftDetails = ShiftDetail::with(['getShiftData', 'shiftDetailRegion'])->get();

        $formattedShift = $shiftDetails->map(function ($event) {
            return [
                'shiftId' => $event->getShiftData->id,
                'shiftDetailId' => $event->id,
                'title' => $event->getShiftData->provider->first_name.' '.$event->getShiftData->provider->last_name,
                'shiftDate' => $event->shift_date,
                'startTime' => $event->start_time,
                'endTime' => $event->end_time,
                'resourceId' => $event->getShiftData->physician_id,
                'physician_id' => $event->getShiftData->physician_id,
                'region_id' => $event->shiftDetailRegion->region_id,
                'region_name' => $event->shiftDetailRegion->region->region_name,
                'is_repeat' => $event->getShiftData->is_repeat,
                'week_days' => explode(',', $event->getShiftData->week_days),
                'repeat_upto' => $event->getShiftData->repeat_upto,
                'status' => $event->status,
            ];
        });

        return response()->json($formattedShift->toArray());
    }

    /**
     * Edit an existing shift.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editShift(Request $request)
    {
        if ($request['action'] === 'return') {
            $status = ShiftDetail::where('id', $request->shiftDetailId)->first();
            if ($status->status === 'approved') {
                ShiftDetail::where('id', $request->shiftDetailId)->update(['status' => 1]);
                return redirect()->back()->with('shiftPending', 'Shift Status changed from Approved to Pending');
            }
            ShiftDetail::where('id', $request->shiftDetailId)->update(['status' => 2]);
            return redirect()->back()->with('shiftApproved', 'Shift Status changed from Pending to Approved');
        }
        if ($request['action'] === 'save') {
            // Check whether the shift created for provider is already having shift for that time period
            $shifts = Shift::with('shiftDetail')->get();
            $currentShifts = $shifts->whereIn('start_date', $request->shiftDate);

            // check for each shifts, whether it have the same time period or in-between time period
            foreach ($currentShifts as $currentShift) {
                if ($currentShift->physician_id === $request->physician) {
                    // for the currentShift if the physician_id matches requested physician check for the time period
                    $shiftStartTimeCurrent = $currentShift->shiftDetail->start_time;
                    $shiftEndTimeCurrent = $currentShift->shiftDetail->end_time;

                    if (
                        $shiftStartTimeCurrent <= $request->shiftStartTime && $shiftEndTimeCurrent > $request->shiftStartTime ||
                        $shiftStartTimeCurrent <= $request->shiftEndTime && $shiftEndTimeCurrent > $request->shiftEndTime
                    ) {
                        return redirect()->back()->with('shiftOverlap', 'You have an shift during the time period you provided');
                    }
                }
            }

            ShiftDetail::where('id', $request->shiftDetailId)->update([
                'shift_date' => $request->shiftDate,
                'start_time' => $request->shiftTimeStart,
                'end_time' => $request->shiftTimeEnd,
                'modified_by' => 'Admin',
            ]);

            return redirect()->back()->with('shiftEdited', 'Shift Edited Successfully!');
        }
        if ($request['action'] === 'delete') {
            ShiftDetail::where('id', $request->shiftDetailId)->delete();
            ShiftDetailRegion::where('shift_detail_id', $request->shiftDetailId)->delete();

            $data = ShiftDetail::where('shift_id', $request->shiftId)->get();

            if ($data->isEmpty()) {
                Shift::where('id', $request->shiftId)->delete();
            }

            return redirect()->back()->with('shiftDeleted', 'Shift Deleted Successfully!');
        }
    }

    /**
     * Change the status of shifts (Approved or Pending).
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function shiftAction(Request $request)
    {
        // if (empty($request->selected)) {
        if (! $request->selected) {
            return redirect()->back()->with('selectOption', 'Select Atleast one shift for performing operation!');
        }
        if ($request->action === 'approve') {
            ShiftDetail::whereIn('id', $request->selected)->update(['status' => 2]);
            return redirect()->back();
        }
        if ($request->action === 'delete') {
            $shiftDetails = ShiftDetail::whereIn('id', $request->selected)->get();

            foreach ($shiftDetails as $shiftDetail) {
                $shiftId = $shiftDetail->shift_id;

                $shiftDetail->delete();
                $shiftDetail->shiftDetailRegion->delete();

                $data = ShiftDetail::where('shift_id', $shiftId)->get();
                if ($data->isEmpty()) {
                    Shift::where('id', $shiftId)->delete();
                }
            }

            return redirect()->back();
        }
    }

    /**
     * Filter shifts in shiftsForReview page based on region selected.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function filterRegions(Request $request)
    {
        $allShifts = ShiftDetailRegion::where('region_id', $request->regionId)->pluck('shift_detail_id')->toArray();
        $shiftDetails = ShiftDetail::whereHas('getShiftData')->whereIn('id', $allShifts)->where('status', 'pending')->paginate(10);
        if ($request->regionId === 0) {
            $shiftDetails = ShiftDetail::whereHas('getShiftData')->where('status', 'pending')->paginate(10);
            $data = view('adminPage.scheduling.filteredShifts')->with('shiftDetails', $shiftDetails)->render();
        } else {
            $data = view('adminPage.scheduling.filteredShifts')->with('shiftDetails', $shiftDetails)->render();
        }

        return response()->json(['html' => $data]);
    }
}
