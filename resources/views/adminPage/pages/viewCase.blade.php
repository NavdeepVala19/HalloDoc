@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/dashboard.css') }}">
@endsection

@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
@endsection


@section('nav-links')
    <a href="{{ route('admin.dashboard') }}" class="active-link">Dashboard</a>
    <a href="{{ route('providerLocation') }}">Provider Location</a>
    <a href="{{ route('admin.profile.editing') }}">My Profile</a>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Providers
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('adminProvidersInfo') }}">Provider</a></li>
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
    <div class="overlay"></div>
    {{-- Case Assigned Successfully --}}
    @if (session('assigned'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('assigned') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    {{-- Case Edited Successfully --}}
    @if (session('caseEdited'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('caseEdited') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    {{-- Wrong Request on url (case doesn't exists) --}}
    @include('alertMessages.wrongCaseRequestError')

    {{-- Assign Case Pop-up --}}
    {{-- This pop-up will open when admin clicks on “Assign case” link from Actions menu. Admin can assign the case
    to providers based on patient’s region using this pop-up. --}}
    @include('popup.adminAssignCase')

    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center justify-content-center gap-2 title-container">
                <div>
                    <h1>New Request</h1>
                </div>
                {{-- Show the name as per request_type_id and with proper color --}}
                <p class="request-type-{{ $data->request_type_id }} mt-2">{{ $data->requestType->name }}</p>
            </div>
            <a href="{{ route(
                'admin.status',
                $data->status == 1
                    ? 'new'
                    : ($data->status == 3
                        ? 'pending'
                        : ($data->status == 4 || $data->status == 5
                            ? 'active'
                            : ($data->status == 6
                                ? 'conclude'
                                : ($data->status == 2 || $data->status == 7 || $data->status == 11
                                    ? 'toclose'
                                    : 'unpaid')))),
            ) }}"
                class="primary-empty back-btn"><i class="bi bi-chevron-left"></i> Back</a>
        </div>


        <div class="section">
            <form action="{{ route('admin.edit.case') }}" method="POST" id="adminEditCaseForm">
                @csrf
                <h3>Patient Information</h3>
                <div>
                    <p>Confirmation Number</p>
                    <h3 class="confirmationNumber">{{ $data->confirmation_no }}</h3>
                </div>

                <input type="hidden" name="requestId" value="{{ $data->id }}">

                <div class="form-floating h-25">
                    <textarea name="patient_notes" class="form-control patientNotes" placeholder="injury" id="floatingTextarea2" disabled>{{ $data->requestClient->notes }}</textarea>
                    <label for="floatingTextarea2">Patient Notes</label>
                </div>

                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="first_name" class="form-control firstName" id="floatingInput1"
                            placeholder="First Name" value="{{ $data->requestClient->first_name }}" disabled>
                        <label for="floatingInput1">First Name</label>
                        @error('first_name')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="last_name" value="{{ $data->requestClient->last_name }}"
                            class="form-control lastName" id="floatingInput2" placeholder="Last Name" disabled>
                        <label for="floatingInput2">Last Name</label>
                        @error('last_name')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="date" name="dob" class="form-control dob"
                            value="{{ $data->requestClient->date_of_birth }}" id="floatingInput3"
                            placeholder="date of birth" disabled>
                        <label for="floatingInput3">Date Of Birth</label>
                    </div>
                    <div class="form-floating">
                        <div class="d-flex align-items-center gap-2">
                            <input type="tel" name="phone_number" value="{{ $data->requestClient->phone_number }}"
                                class="form-control phone phoneNumber" id="telephone" disabled>
                            @error('phone_number')
                                <div class="alert text-danger">{{ $message }}</div>
                            @enderror
                            <button type="button" class="primary-empty"><i class="bi bi-telephone"></i></button>
                        </div>
                    </div>
                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control email"
                            value="{{ $data->requestClient->email }}" id="floatingInput4" placeholder="name@example.com"
                            disabled>
                        <label for="floatingInput4">Email</label>
                    </div>
                    <div>
                        <button type="button" class="primary-empty edit-case-btn">Edit</button>
                        <button type="submit" class="primary-fill save-case-btn">Save</button>
                    </div>
                </div>
                <h3>Location Information</h3>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="region" value="{{ $data->region_id }}" class="form-control"
                            id="floatingInput" placeholder="region" disabled>
                        <label for="floatingInput">Region</label>
                        @error('region')
                            <div class="alert text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex align-items-center gap-2 ">
                        <div class="form-floating w-100">
                            <input type="text" name="business_name" class="form-control" id="floatingInput"
                                placeholder="business"
                                value="{{ $data->requestClient->street }}, {{ $data->requestClient->city }}, {{ $data->requestClient->state }}"
                                disabled>
                            <label for="floatingInput">Business Name/Address</label>
                        </div>
                        <button type="button" class="primary-empty"><i class="bi bi-geo-alt"></i></button>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="room" class="form-control" id="floatingInput" placeholder="room"
                            value="{{ $data->requestClient->room }}" disabled>
                        <label for="floatingInput">Room #</label>
                    </div>
                </div>

                <div class="text-end button-section">
                    @if ($data->status == 1)
                        <button type="button" class="assign-case-btn primary-fill"
                            data-id="{{ $data->id }}">Assign</button>
                    @endif
                    <a href="{{ route('admin.view.note', $data->id) }}" class="primary-fill">View Notes</a>
                    <a href="{{ route(
                        'admin.status',
                        $data->status == 1
                            ? 'new'
                            : ($data->status == 3
                                ? 'pending'
                                : ($data->status == 4 || $data->status == 5
                                    ? 'active'
                                    : ($data->status == 6
                                        ? 'conclude'
                                        : ($data->status == 2 || $data->status == 7 || $data->status == 11
                                            ? 'toclose'
                                            : 'unpaid')))),
                    ) }}"
                        class="primary-red">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/validation.js') }}"></script>
@endsection
