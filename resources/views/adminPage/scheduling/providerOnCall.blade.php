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
    <div class="m-5 spacing">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3>MDs On Call</h3>
            <a href="{{ route('admin.scheduling') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        {{-- d-flex align-items-center justify-content-between gap-3 --}}
        <div class="section">
            <div class="d-flex align-items-center justify-content-between">
                <div class="region-dropdown">
                    <select name="role_id" class="form-select providerOnCallRegionFilter" id="floatingSelect"
                        aria-label="Floating label select example">
                        <option value="0" selected>All Regions</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                        @endforeach
                    </select>
                    {{-- <label for="floatingSelect">All Regions</label> --}}
                </div>
                <div>
                    <a href="{{ route('admin.scheduling') }}" class="primary-fill">Calendar View</a>
                    <a href="{{ route('shifts.review') }}" class="primary-fill">Shifts For Review</a>
                </div>
            </div>
            <div class="m-3">
                <h5>MD's On Call</h5>
                <div class="grid-3 onDuty-provider-grid">
                    @foreach ($onCallPhysicians as $onCallPhysician)
                        <div class="provider">
                            <img src="{{ asset('storage/' . $onCallPhysician->photo) }}" class="provider-profile-photo"
                                alt="provider profile photo">
                            <span>
                                Dr. {{ $onCallPhysician->first_name }}
                                {{ $onCallPhysician->last_name }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="m-3">
                <h5>Physicians Off Duty</h5>
                <div class="grid-3 offDuty-provider-grid">
                    @foreach ($offDutyPhysicians as $offDutyPhysician)
                        <div class="provider">
                            <img src="{{ asset('storage/' . $offDutyPhysician->photo) }}" class="provider-profile-photo"
                                alt="provider profile photo">
                            <span>
                                Dr. {{ $offDutyPhysician->first_name }}
                                {{ $offDutyPhysician->last_name }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var imageUrl = "{{ asset('storage/') }}";
    </script>
    <script src="{{ asset('assets/adminPage/providerOnCall.js') }}"></script>
@endsection
