@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/dashboard.css') }}">
@endsection

@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
@endsection


@section('nav-links')
    <a href="{{ route('provider.dashboard') }}" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="{{ route('provider.scheduling') }}">My Schedule</a>
    <a href="{{ route('provider.profile') }}">My Profile</a>
@endsection

@section('content')
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
                'provider.status',
                $data->status == 1
                    ? 'new'
                    : ($data->status == 3
                        ? 'pending'
                        : ($data->status == 4 || $data->status == 5
                            ? 'active'
                            : 'conclude')),
            ) }}"
                class="primary-empty back-btn"><i class="bi bi-chevron-left"></i> Back</a>
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
                            class="form-control phone" id="telephone" disabled>
                        @error('phone_number')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        {{-- <button type="button" class="primary-empty"><i class="bi bi-telephone"></i></button> --}}
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
                                value="{{ $data->requestClient->street }}, {{ $data->requestClient->city }}, {{ $data->requestClient->state }}"
                                placeholder="business" disabled>
                            <label for="floatingInput">Business Name/Address</label>
                        </div>
                        {{-- <button type="button" class="primary-empty"><i class="bi bi-geo-alt"></i></button> --}}
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="room" value="{{ $data->requestClient->room }}" class="form-control"
                            id="floatingInput" placeholder="room" disabled>
                        <label for="floatingInput">Room #</label>
                    </div>
                </div>

                <div class="text-end button-section">
                    {{-- <button class="primary-fill">Assign</button> --}}
                    <a href="{{ route('provider.view.notes', Crypt::encrypt($data->id)) }}" class="primary-fill">View Notes</a>
                    <a href="{{ route(
                        'provider.status',
                        $data->status == 1
                            ? 'new'
                            : ($data->status == 3
                                ? 'pending'
                                : ($data->status == 4 || $data->status == 5
                                    ? 'active'
                                    : 'conclude')),
                    ) }}"
                        class="primary-red">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
