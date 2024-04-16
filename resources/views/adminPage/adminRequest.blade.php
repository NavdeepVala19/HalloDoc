@extends('index')
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/adminRequest.css') }}">
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

@include('loading')
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">Submit Information</h1>
            <a href="{{ route('admin.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        <div class="section">
            <form action="{{ route('adminCreatedPatientRequest') }}" method="POST" id="adminCreateRequestForm">
                @csrf
                <h3>Patient</h3>
                <div class="mb-4 form-grid">
                    <input type="text" name="request_type" value="1" hidden>
                    <div class="form-floating">
                        <input type="text" name="first_name"
                            class="form-control @error('first_name') is-invalid @enderror" id="floatingInput1"
                            placeholder="First Name" value="{{ old('first_name') }}">
                        <label for="floatingInput1">First Name</label>
                        @error('first_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                            id="floatingInput2" placeholder="Last Name" value="{{ old('last_name') }}">
                        <label for="floatingInput2">Last Name</label>
                        @error('last_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <div class="form-floating" style="height: 58px;">
                            <input type="tel" name="phone_number"
                                class="form-control phone @error('phone_number') is-invalid @enderror" id="telephone"
                                placeholder="Phone Number" value="{{ old('phone_number') }}">
                        </div>
                        @error('phone_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            id="floatingInput3" placeholder="name@example.com" value="{{ old('email') }}">
                        <label for="floatingInput3">Email address</label>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="date" class="form-control" id="floatingInput4" placeholder="date of birth"
                            name="date_of_birth" value="{{ old('date_of_birth') }}">
                        <label for="floatingInput4">Date Of Birth(Optional)</label>
                    </div>
                </div>
                <h3>Location</h3>
                <div class="mb-4 form-grid">
                    <div class="form-floating">
                        <input type="text" name="street" class="form-control @error('street') is-invalid @enderror"
                            id="floatingInput5" placeholder="Street" value="{{ old('street') }}">
                        <label for="floatingInput5">Street</label>
                        @error('street')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                            id="floatingInput6" placeholder="City" value="{{ old('city') }}">
                        <label for="floatingInput6">City</label>
                        @error('city')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                            id="floatingInput7" placeholder="State" value="{{ old('state') }}">
                        <label for="floatingInput7">State</label>
                        @error('state')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="number" name="zip" class="form-control  @error('zip') is-invalid @enderror"
                            id="floatingInput" placeholder="zip code" value="{{ old('zip') }}">
                        <label for="floatingInput">Zip Code (Optional)</label>
                        @error('zip')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="number" name="room" class="form-control  @error('room') is-invalid @enderror"
                            id="floatingInput" placeholder="Room" value="{{ old('room') }}">
                        <label for="floatingInput">Room # (Optional)</label>
                        @error('room')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <h3>Notes</h3>
                <div class="mb-4">
                    <div class="form-floating">
                        <textarea class="form-control note  @error('adminNote') is-invalid @enderror" name='adminNote' placeholder="notes"
                            id="floatingTextarea2">{{ old('adminNote') }}</textarea>
                        <label for="floatingTextarea2">Admin Notes (optional)</label>
                        @error('adminNote')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-4 d-flex justify-content-end gap-3 ">
                        <input type="submit" value='Save' class="primary-fill">
                        <a href="{{ route('admin.dashboard') }}" class="primary-empty">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/admin/adminCreateRequest.js') }}"></script>
@endsection
