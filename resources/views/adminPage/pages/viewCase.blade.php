@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/dashboard.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}" class="active-link">Dashboard</a>
    <a href="">Provider Location</a>
    <a href="">My Profile</a>
    <a href="">Providers</a>
    <a href="">Partners</a>
    <a href="">Access</a>
    <a href="">Records</a>
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
    {{-- Assign Case Pop-up --}}
    {{-- This pop-up will open when admin clicks on “Assign case” link from Actions menu. Admin can assign the case
    to providers based on patient’s region using this pop-up. --}}
    @include('popup.adminAssignCase')

    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center justify-content-center gap-2">
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
                class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>


        <div class="section">
            <form action="" method="POST">
                @csrf
                <h3>Patient Information</h3>
                <div>
                    <p>Confirmation Number</p>
                    <h3 class="confirmationNumber">{{ $data->confirmation_no }}</h3>
                </div>

                <div class="form-floating h-25">
                    <textarea class="form-control " placeholder="injury" id="floatingTextarea2" disabled>{{ $data->requestClient->notes }}</textarea>
                    <label for="floatingTextarea2">Patient Notes</label>
                </div>

                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="first_name" class="form-control" id="floatingInput"
                            placeholder="First Name" value="{{ $data->requestClient->first_name }}" disabled>
                        <label for="floatingInput">First Name</label>
                        @error('first_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="last_name" value="{{ $data->requestClient->last_name }}"
                            class="form-control" id="floatingInput" placeholder="Last Name" disabled>
                        <label for="floatingInput">Last Name</label>
                        @error('last_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="date" class="form-control" value="{{ $data->requestClient->date_of_birth }}"
                            id="floatingInput" placeholder="date of birth" disabled>
                        <label for="floatingInput">Date Of Birth</label>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <input type="tel" name="phone_number" value="{{ $data->requestClient->phone_number }}"
                            class="form-control phone" id="telephone" placeholder="Phone Number" disabled>
                        @error('phone_number')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <button type="button" class="primary-empty"><i class="bi bi-telephone"></i></button>
                    </div>
                    <div class="form-floating ">
                        <input type="email" class="form-control" value="{{ $data->requestClient->email }}"
                            id="floatingInput" placeholder="name@example.com" disabled>
                        <label for="floatingInput">Email</label>
                    </div>
                </div>
                <h3>Location Information</h3>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="region" value="{{ $data->region_id }}" class="form-control"
                            id="floatingInput" placeholder="region" disabled>
                        <label for="floatingInput">Region</label>
                        @error('region')
                            <div class="alert alert-danger">{{ $message }}</div>
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

                <div class="text-end">
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
