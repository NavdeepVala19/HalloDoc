@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/encounterFormProvider.css') }}">
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
    {{-- Encounter Form Changes Saved --}}
    @if (session('encounterChangesSaved'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('encounterChangesSaved') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    <div class="container form-container">
        <div class="heading-container d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">Encounter Form</h1>
            <a href="{{ route(
                'admin.status',
                $requestData->status == 4 || $requestData->status == 5
                    ? 'active'
                    : ($requestData->status == 6
                        ? 'conclude'
                        : 'toclose'),
            ) }}"
                class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        {{-- Form Starts From Here --}}
        <form action="{{ route('admin.medical.data') }}" method="POST" id="adminEncounterForm">
            @csrf
            <div class="section">
                @include('adminPage.encounter')

                {{-- Three buttons at last --}}
                <div class="button-section">
                    <input type="submit" value="Save Changes" class="primary-fill" id="adminEncounterFormBtn">
                    <a href="{{ route(
                        'admin.status',
                        $requestData->status == 4 || $requestData->status == 5
                            ? 'active'
                            : ($requestData->status == 6
                                ? 'conclude'
                                : 'toclose'),
                    ) }}"
                        class="primary-empty">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/validation.js') }}"></script>
@endsection
