@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/scheduling.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/providerScheduling.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('provider.dashboard') }}">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="{{ route('provider.scheduling') }}" class="active-link">My Schedule</a>
    <a href="{{ route('provider.profile') }}">My Profile</a>
@endsection

@section('content')
    {{-- Shift Timing overlap (Error) --}}
    @if (session('shiftOverlap'))
        <div class="alert alert-danger popup-message ">
            <span>
                {{ session('shiftOverlap') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    
    {{-- Shift Added/Create Successfully --}}
    @if (session('shiftAdded'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('shiftAdded') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    {{-- Shift Deleted Successfully --}}
    @if (session('shiftDeleted'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('shiftDeleted') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    {{-- Shift Edited Successfully --}}
    @if (session('shiftEdited'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('shiftEdited') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    <div class="overlay"></div>
    {{-- Create Shift pop-up  --}}
    <div class="pop-up create-shift ">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>Create Shift</span>
            <button class="hide-popup-btn providerAddShiftCancel"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('physician.scheduling.data') }}" method="POST" id="providerAddShiftForm" class="m-4">
            @csrf
            <input type="text" name="providerId" id="providerId" hidden>
            <div class="">
                <div class="form-floating">
                    <select name="region" class="form-select region physicianRegions" id="floatingSelect1"
                        aria-label="Floating label select example">
                        <option selected disabled>Select Region</option>
                    </select>
                    <label for="floatingSelect1">Region</label>
                </div>
                <div class="form-floating ">
                    <input type="date" name="shiftDate"
                        class="form-control shiftDate @error('shiftDate') is-invalid @enderror" id="floatingInput4"
                        placeholder="Created Date" value="{{ old('shiftDate') }}">
                    <label for="floatingInput4">Shift Date</label>
                    @error('shiftDate')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="time" name="shiftStartTime"
                            class="form-control shiftStartTime @error('shiftStartTime') is-invalid @enderror"
                            id="floatingInput2" placeholder="Created Date" value="{{ old('shiftStartTime') }}">
                        <label for="floatingInput2">Start</label>
                        @error('shiftStartTime')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="time" name="shiftEndTime"
                            class="form-control @error('shiftEndTime') is-invalid @enderror" id="floatingInput3"
                            placeholder="Created Date" value="{{ old('shiftEndTime') }}">
                        <label for="floatingInput3">End</label>
                        @error('shiftEndTime')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="flexSwitchCheckChecked">Repeat</label>
                    <input name="is_repeat" class="form-check-input repeat-switch" type="checkbox" role="switch"
                        id="flexSwitchCheckChecked">
                </div>
                <div class="repeat-section">
                    <div class="checkboxes-section">
                        <p>Repeat Days</p>
                        <div class="form-check">
                            <input class="form-check-input" name="checkbox[]" type="checkbox" value="0"
                                id="defaultCheck1" disabled>
                            <label class="form-check-label" for="defaultCheck1">
                                Every Sunday
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="checkbox[]" type="checkbox" value="1"
                                id="defaultCheck2" disabled>
                            <label class="form-check-label" for="defaultCheck2">
                                Every Monday
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="checkbox[]" type="checkbox" value="2"
                                id="defaultCheck3" disabled>
                            <label class="form-check-label" for="defaultCheck3">
                                Every Tuesday
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="checkbox[]" type="checkbox" value="3"
                                id="defaultCheck4" disabled>
                            <label class="form-check-label" for="defaultCheck4">
                                Every Wednesday
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="checkbox[]" type="checkbox" value="4"
                                id="defaultCheck5" disabled>
                            <label class="form-check-label" for="defaultCheck5">
                                Every Thursday
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="checkbox[]" type="checkbox" value="5"
                                id="defaultCheck6" disabled>
                            <label class="form-check-label" for="defaultCheck6">
                                Every Friday
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" name="checkbox[]" type="checkbox" value="6"
                                id="defaultCheck7" disabled>
                            <label class="form-check-label" for="defaultCheck7">
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
            </div>
            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                <button type="submit" class="primary-fill save-shift-btn" id="providerAddShiftBtn">Save</button>
                <button type="button" class="primary-empty hide-popup-btn providerAddShiftCancel">Cancel</button>
            </div>
        </form>
    </div>

    {{-- View/Delete Shift --}}
    <div class="pop-up view-shift">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>View Shift</span>
            <button class="hide-popup-btn view-shift-close"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('provider.edit.shift') }}" method="POST" id="providerEditShiftForm" class="m-4">
            @csrf
            <input type="text" name="shiftId" value="" class="shiftId" hidden>
            <div>
                <select name="region" class="form-select region-view-shift" id="floatingSelect"
                    aria-label="Floating label select example" disabled>
                </select>
                <div class="form-floating ">
                    <input type="date" name="shiftDate"
                        class="form-control shiftDate shiftDateInput @error('shiftDate') is-invalid @enderror"
                        id="floatingInput1" placeholder="Created Date" disabled value="{{ old('shiftDate') }}">
                    <label for="floatingInput1">Shift Date</label>
                    @error('shiftDate')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="time" name="shiftTimeStart"
                            class="form-control shiftStartTime shiftStartTimeInput @error('shiftTimeStart') is-invalid @enderror"
                            id="startTime" placeholder="Created Date" disabled value="{{ old('shiftTimeStart') }}">
                        <label for="startTime">Start</label>
                        @error('shiftTimeStart')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="time" name="shiftTimeEnd"
                            class="form-control shiftEndTime shiftEndTimeInput @error('shiftTimeEnd') is-invalid @enderror"
                            id="endTime" placeholder="Created Date" disabled value="{{ old('shiftTimeEnd') }}">
                        <label for="endTime">End</label>
                        @error('shiftTimeEnd')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                <button type="button" class="primary-fill edit-btn">Edit</button>
                <button type="submit" name="action" value="save" class="primary-fill save-btn"
                    id="saveProviderEditShiftBtn">Save</button>
                <button type="submit" name="action" value="delete" class="delete-selected-btn">Delete</button>
            </div>
        </form>
    </div>

    {{-- Main Content --}}
    <div class="m-5 spacing">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2>My Schedule</h2>
            <a href="{{ route('provider.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i>
                Back</a>
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
            <div id="calendar"></div>
        </div>
    </div>
@endsection

@section('script')
    {{-- FullCalendar JQuery Plugin --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.11/index.global.min.js'></script>
    {{-- Custom Script for Implementation of Scheduling --}}
    <script defer src="{{ URL::asset('assets/providerPage/providerScheduling.js') }}"></script>
    {{-- Validation JQuery Files --}}
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/validation.js') }}"></script>
@endsection
