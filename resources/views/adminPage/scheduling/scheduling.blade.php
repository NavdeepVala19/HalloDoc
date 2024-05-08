@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/scheduling.css') }}">
@endsection

@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('providerLocation') }}">Provider Location</a>
    <a href="{{ route('admin.profile.editing') }}">My Profile</a>
    <div class="dropdown record-navigation">
        <button class="record-btn active-link" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Providers
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('admin.providers.list') }}">Provider</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.scheduling') }}">Scheduling</a></li>
            <li><a class="dropdown-item" href="#">Invoicing</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.partners') }}">Partners</a>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Access
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('admin.user.access') }}">User Access</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.access.view') }}">Account Access</a></li>
        </ul>
    </div>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Records
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('admin.search.records.view') }}">Search Records</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.email.records.view') }}">Email Logs</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.sms.records.view') }}">SMS Logs</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.patient.records.view') }}">Patient Records</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.block.history.view') }}">Blocked History</a></li>
        </ul>
    </div>
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
    {{-- Shift Status Changed from Pending to Approved --}}
    @if (session('shiftApproved'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('shiftApproved') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    {{-- Shift Status Changed from Approved to Pending --}}
    @if (session('shiftPending'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('shiftPending') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    <div class="overlay"></div>
    {{-- Create Shift pop-up  --}}
    <div class="pop-up create-shift ">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>Create Shift</span>
            <button class="hide-popup-btn addShiftCancel"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('admin.scheduling.data') }}" method="POST" id="adminAddShiftForm" class="m-4">
            @csrf
            <div class="">
                <div class="form-floating">
                    <select name="region" class="form-select region physicianRegions @error('region') is-invalid @enderror"
                        id="floatingSelect1">
                        <option selected disabled>Select Region</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" id="region_{{ $region->id }}"
                                @if (old('region') == $region->id) selected @endif>{{ $region->region_name }}
                            </option>
                        @endforeach
                    </select>
                    <label for="floatingSelect1">Region</label>
                </div>
                @error('region')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                <div class="form-floating">
                    <select name="physician" class="form-select physicianSelection @error('physician') is-invalid @enderror"
                        id="floatingSelect2">
                        <option selected disabled>Select</option>
                    </select>
                    <label for="floatingSelect2">Physician</label>
                    @error('physician')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating ">
                    <input type="date" name="shiftDate" class="form-control shiftDate @error('shiftDate') is-invalid @enderror"
                        id="floatingInput5" placeholder="Created Date" value="{{ old('shiftDate') }}">
                    <label for="floatingInput5">Shift Date</label>
                    @error('shiftDate')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="time" name="shiftStartTime"
                            class="form-control shiftStartTime @error('shiftStartTime') is-invalid @enderror" id="floatingInput2"
                            placeholder="Created Date" value="{{ old('shiftStartTime') }}">
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
                            id="floatingSelect8" aria-label="Floating label select example" disabled>
                            <option selected value="2">2-times</option>
                            <option value="3">3-times</option>
                            <option value="4">4-times</option>
                        </select>
                        <label for="floatingSelect8">Repeat End</label>
                    </div>
                </div>
            </div>
            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                <button type="submit" class="primary-fill save-shift-btn" id="adminAddShiftBtn">Save</button>
                <button type="button" class="primary-empty hide-popup-btn addShiftCancel">Cancel</button>
            </div>
        </form>
    </div>
    {{-- View/Delete Shift --}}
    <div class="pop-up view-shift">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>View Shift</span>
            <button class="hide-popup-btn view-shift-close"><i class="bi bi-x-lg"></i></button>
        </div>
        <form action="{{ route('admin.edit.shift') }}" method="POST" id="adminEditShiftForm" class="m-4">
            @csrf
            <input type="text" name="shiftId" class="shiftId" hidden>
            <div>
                <select name="region" class="form-select region-view-shift" id="floatingSelect"
                    aria-label="Floating label select example" disabled>
                </select>
                <div class="form-floating">
                    <select class="form-select physician-view-shift" name="physician" id="floatingSelect"
                        aria-label="Floating label select example" disabled> </select>
                    <label for="floatingSelect">Physician</label>
                </div>
                <div class="form-floating ">
                    <input type="date" name="shiftDate" class="form-control shiftDate shiftDateInput"
                        id="floatingInput1" placeholder="Created Date" disabled>
                    <label for="floatingInput1">Shift Date</label>
                </div>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="time" name="shiftTimeStart"
                            class="form-control shiftStartTime shiftStartTimeInput" id="startTime"
                            placeholder="Created Date" disabled>
                        <label for="startTime">Start</label>
                    </div>
                    <div class="form-floating ">
                        <input type="time" name="shiftTimeEnd" class="form-control shiftEndTime shiftEndTimeInput"
                            id="endTime" placeholder="Created Date" disabled>
                        <label for="endTime">End</label>
                    </div>
                </div>
            </div>
            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                <button type="submit" name="action" value="return" class="primary-fill">Return</button>
                {{-- Change status from pending to approved and vice-versa --}}
                <button type="button" class="primary-fill edit-btn">Edit</button>
                <button type="submit" name="action" value="save" class="primary-fill save-btn" id="saveAdminEditShiftBtn">Save</button>
                <button type="submit" name="action" value="delete" class="delete-selected-btn">Delete</button>
            </div>
        </form>
    </div>

    {{-- Main Content --}}
    <div class="m-5 spacing">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3>Scheduling</h3>
            <a href="{{ route('admin.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        <div class="section">
            <div class="d-flex align-items-center justify-content-between filter-section">
                <div class="region-dropdown">
                    <select name="role_id" class="form-select region-filter" id="floatingSelect">
                        <option value="0" selected>All Regions</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="link-container">
                    <a href="{{ route('providers.on.call') }}" class="primary-fill">Providers On Call</a>
                    <a href="{{ route('shifts.review') }}" class="primary-fill">Shifts For Review</a>
                    <button class="primary-fill new-shift-btn">Add New Shift</button>
                </div>
            </div>
            <h2 class="date-title m-3">Date</h2>
            <div class="d-flex justify-content-end status-container">
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
    <script src="{{ URL::asset('assets/adminPage/scheduling.js') }}"></script>
    {{-- Validation JQuery Files --}}
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/validation.js') }}"></script>
@endsection
