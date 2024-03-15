@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/scheduling.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/providerScheduling.css') }}">
@endsection

@section('nav-links')
    <a href="">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="{{ route('provider.scheduling') }}" class="active-link">My Schedule</a>
    <a href="{{ route('provider.profile') }}">My Profile</a>
@endsection

@section('content')
    <div class="overlay"></div>
    {{-- Create Shift pop-up  --}}
    <div class="pop-up create-shift ">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>Create Shift</span>
            <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('physician.scheduling.data') }}" method="POST" class="m-4">
            @csrf
            <input type="text" name="providerId" id="providerId" hidden>
            <div class="">
                <select name="region" class="form-select region physicianRegions" id="floatingSelect"
                    aria-label="Floating label select example">
                    <option selected>Region</option>
                    {{-- @foreach ($regions as $region)
                        <option value="{{ $region->id }}" id="region_{{ $region->id }}">{{ $region->region_name }}
                        </option>
                    @endforeach --}}
                </select>

                {{-- @error('region')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror --}}
                <div class="form-floating ">
                    <input type="date" name="shiftDate" class="form-control shiftDate" id="floatingInput"
                        placeholder="Created Date">
                    <label for="floatingInput">Shift Date</label>
                </div>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="time" name="shiftStartTime" class="form-control shiftStartTime" id="floatingInput"
                            placeholder="Created Date">
                        <label for="floatingInput">Start</label>
                    </div>
                    <div class="form-floating ">
                        <input type="time" name="shiftEndTime" class="form-control shiftEndTime" id="floatingInput"
                            placeholder="Created Date">
                        <label for="floatingInput">End</label>
                    </div>
                </div>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="flexSwitchCheckChecked">Repeat</label>
                    <input name="is_repeat" class="form-check-input repeat-switch" type="checkbox" role="switch"
                        id="flexSwitchCheckChecked">
                </div>
                <div class="checkboxes-section">
                    <p>Repeat Days</p>
                    <div class="form-check">
                        <input class="form-check-input" name="checkbox[]" type="checkbox" value="0" id="defaultCheck1"
                            disabled>
                        <label class="form-check-label" for="defaultCheck1">
                            Every Sunday
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="checkbox[]" type="checkbox" value="1" id="defaultCheck1"
                            disabled>
                        <label class="form-check-label" for="defaultCheck1">
                            Every Monday
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="checkbox[]" type="checkbox" value="2" id="defaultCheck1"
                            disabled>
                        <label class="form-check-label" for="defaultCheck1">
                            Every Tuesday
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="checkbox[]" type="checkbox" value="3" id="defaultCheck1"
                            disabled>
                        <label class="form-check-label" for="defaultCheck1">
                            Every Wednesday
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="checkbox[]" type="checkbox" value="4" id="defaultCheck1"
                            disabled>
                        <label class="form-check-label" for="defaultCheck1">
                            Every Thursday
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="checkbox[]" type="checkbox" value="5"
                            id="defaultCheck1" disabled>
                        <label class="form-check-label" for="defaultCheck1">
                            Every Friday
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="checkbox[]" type="checkbox" value="6"
                            id="defaultCheck1" disabled>
                        <label class="form-check-label" for="defaultCheck1">
                            Every Saturday
                        </label>
                    </div>
                </div>
                <div class="form-floating">
                    <select class="form-select repeat-end-selection" name="repeatEnd" class="cancel-options"
                        id="floatingSelect" aria-label="Floating label select example" disabled>
                        <option selected value="2">2-times</option>
                        <option value="3">3-times</option>
                        <option value="4">4-times</option>
                    </select>
                    <label for="floatingSelect">Repeat End</label>
                </div>
            </div>
            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                {{-- <button type="submit" class="primary-fill save-shift-btn">Save</button> --}}
                <button type="submit" class="primary-fill save-shift-btn">Save</button>
                <button type="button" class="primary-empty hide-popup-btn">Cancel</button>
            </div>
        </form>
    </div>

    {{-- View/Delete Shift --}}
    <div class="pop-up view-shift">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>View Shift</span>
            <button class="hide-popup-btn view-shift-close"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('provider.edit.shift') }}" method="POST" class="m-4">
            @csrf
            <input type="text" name="shiftId" class="shiftId" hidden>
            <div>
                <select name="region" class="form-select region-view-shift" id="floatingSelect"
                    aria-label="Floating label select example" disabled>
                </select>
                <div class="form-floating ">
                    <input type="date" name="shiftDate" class="form-control shiftDate shiftDateInput"
                        id="floatingInput" placeholder="Created Date" disabled>
                    <label for="floatingInput">Shift Date</label>
                </div>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="time" name="shiftTimeStart"
                            class="form-control shiftStartTime shiftStartTimeInput" id="floatingInput"
                            placeholder="Created Date" disabled>
                        <label for="floatingInput">Start</label>
                    </div>
                    <div class="form-floating ">
                        <input type="time" name="shiftTimeEnd" class="form-control shiftEndTime shiftEndTimeInput"
                            id="floatingInput" placeholder="Created Date" disabled>
                        <label for="floatingInput">End</label>
                    </div>
                </div>
            </div>
            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                <button type="button" class="primary-fill edit-btn">Edit</button>
                <button type="submit" name="action" value="save" class="primary-fill save-btn">Save</button>
                <button type="submit" name="action" value="delete" class="delete-selected-btn">Delete</button>
            </div>
        </form>
    </div>

    {{-- Main Content --}}
    <div class="m-5 spacing">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2>My Schedule</h2>
            <a href="{{ route('admin.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        <div class="section">
            <div class="d-flex align-items-center justify-content-between">
                <h4>Schedule For: <span class="calendar-date"></span></h4>
            </div>
            <div class="d-flex justify-content-end">
                <span class="d-flex align-items-center">
                    <div class="pending-shift m-2"></div>Pending Shifts
                </span>
                <span class="d-flex align-items-center">
                    <div class="approved-shift m-2"></div>Approved Shifts
                </span>
            </div>
            {{-- <div class="text-end mt-3">
                <button class="add-new-shift-btn">Add New Shift</button>
            </div> --}}
            <div id="calendar"></div>
        </div>
    </div>
@endsection

@section('script')
    <script defer src="{{ URL::asset('assets/providerPage/providerScheduling.js') }}"></script>
@endsection
