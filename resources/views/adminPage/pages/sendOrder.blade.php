@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/commonpage/viewUploads.css') }}">
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
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">
                Send Order
            </h1>
            <a href="{{ route(
                'admin.status',
                $data->status == 4 || $data->status == 5 ? 'active' : ($data->status == 6 ? 'conclude' : 'toclose'),
            ) }}"
                class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        <form action="{{ route('admin.send.order') }}" method="POST" id="adminSendOrderForm">
            @csrf
            <input type="text" name="requestId" value="{{ $requestId }}" hidden>
            <div class="section">
                <div class="grid-2">
                    <div class="form-floating">
                        <select name="profession"
                            class="form-select profession-menu @error('profession') is-invalid @enderror"
                            id="floatingSelect1" aria-label="Floating label select example">
                            <option selected disabled>Open this select menu</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" @if ($type->id == old('profession')) selected @endif>
                                    {{ $type->profession_name }}</option>
                            @endforeach
                        </select>
                        <label for="floatingSelect1">Select Profession</label>
                        @error('profession')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <select name="vendor_id"
                            class="form-select business-menu @error('vendor_id')
                            is-invalid
                        @enderror"
                            id="floatingSelect2" aria-label="Floating label select example">
                            <option selected disabled>Buisness</option>
                        </select>
                        <label for="floatingSelect2">Select Business</label>
                        @error('vendor_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="business_contact"
                            class="form-control business_contact @error('business_contact') is-invalid @enderror"
                            id="floatingInput3" placeholder="Business Contact" value="{{ old('business_contact') }}"
                            disabled>
                        <label for="floatingInput3">Business Contact</label>
                        @error('business_contact')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control email @error('email') is-invalid @enderror"
                            id="floatingInput4" placeholder="email" value="{{ old('email') }}" disabled>
                        <label for="floatingInput4">Email</label>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="number" name="fax_number"
                            class="form-control fax_number @error('fax_number') is-invalid @enderror" id="floatingInput5"
                            placeholder="Fax Number" value="{{ old('fax_number') }}" min="0" disabled>
                        <label for="floatingInput5">Fax Number</label>
                        @error('fax_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" name="prescription" placeholder="injury" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Prescription or Order details</label>
                </div>

                <div class="grid-2">
                    <div class="form-floating">
                        <select class="form-select" name="refills" id="floatingSelect"
                            aria-label="Floating label select example">
                            <option selected disabled>Not Required</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                            <option value="4">Four</option>
                            <option value="5">Five</option>
                        </select>
                        <label for="floatingSelect">Number of Refil</label>
                    </div>
                </div>

                <div class="text-end">
                    <input type="submit" value="Submit" class="primary-fill" id="adminSendOrderSubmit">
                    <a href="{{ route(
                        'admin.status',
                        $data->status == 4 || $data->status == 5 ? 'active' : ($data->status == 6 ? 'conclude' : 'toclose'),
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
