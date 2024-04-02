@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/partners.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}" class="active-link">Dashboard</a>
    <a href="{{ route('providerLocation') }}">Provider Location</a>
    <a href="">My Profile</a>
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
    {{-- Changes in The Details of business are saved successfully --}}
    @if (session('changesSaved'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('changesSaved') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="heading">Update Business</h2>
            <a href="{{ route('admin.partners') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        <div class="section">
            <h4>Submit Information</h4>

            <form action="{{ route('update.business') }}" method="POST">
                @csrf
                <input type="text" value="{{ $vendor->id }}" name="vendor_id" hidden>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="buisness_name" value="{{ $vendor->vendor_name }}" class="form-control"
                            id="floatingInput" placeholder="Business Name">
                        <label for="floatingInput">Business Name</label>
                    </div>
                    <div class="form-floating">
                        <select id="floatingSelect" name="profession" class="form-select">
                            <option selected>Select Profession</option>
                            @foreach ($professions as $profession)
                                <option value="{{ $profession->id }}"
                                    {{ $vendor->profession == $profession->id ? 'selected="selected"' : '' }}>
                                    {{ $profession->profession_name }}</option>
                            @endforeach
                        </select>
                        <label for="floatingSelect">Profession</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="fax_number" value="{{ $vendor->fax_number }}" class="form-control"
                            id="floatingInput" placeholder="Fax Number">
                        <label for="floatingInput">Fax Number</label>
                    </div>
                    <input type="tel" name="mobile" class="form-control phone" value="{{ $vendor->phone_number }}"
                        id="telephone" placeholder="mobile">

                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control" id="floatingInput"
                            value="{{ $vendor->email }}" placeholder="name@example.com">
                        <label for="floatingInput">Email</label>
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="business_contact" value="{{ $vendor->business_contact }}"
                            class="form-control" id="floatingInput" placeholder="Business Contact">
                        <label for="floatingInput">Business Contact</label>
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="street" class="form-control" value="{{ $vendor->address }}"
                            id="floatingInput" placeholder="Street">
                        <label for="floatingInput">Street</label>
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="city" class="form-control" value="{{ $vendor->city }}"
                            id="floatingInput" placeholder="City">
                        <label for="floatingInput">City</label>
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="state" class="form-control" value="{{ $vendor->state }}"
                            id="floatingInput" placeholder="State">
                        <label for="floatingInput">State</label>
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="zip" class="form-control" value="{{ $vendor->zip }}"
                            id="floatingInput" placeholder="Zip/postal">
                        <label for="floatingInput">Zip/postal</label>
                    </div>
                </div>
                <div class="text-end">
                    <input type="submit" value="Save" class="primary-fill">
                    <a href="{{ route('admin.partners') }}" class="primary-empty">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
