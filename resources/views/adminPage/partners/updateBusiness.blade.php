@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/partners.css') }}">
@endsection

@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
@endsection


@section('nav-links')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('provider.location') }}">Provider Location</a>
    <a href="{{ route('admin.profile.editing') }}">My Profile</a>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Providers
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('admin.providers.list') }}">Provider</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.scheduling') }}">Scheduling</a></li>
            <li><a class="dropdown-item" href="#">Invoicing</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.partners') }}" class="active-link">Partners</a>
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
            <h2 class="heading">Update Business</h2>
            <a href="{{ route('admin.partners') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        <div class="section">
            <h4>Submit Information</h4>

            <form action="{{ route('update.business') }}" method="POST" id="updateBusinessForm">
                @csrf
                <input type="text" value="{{ $vendor->id }}" name="vendor_id" hidden>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="business_name"
                            value="{{ $vendor->vendor_name ? $vendor->vendor_name : old('buisness_name') }}"
                            class="form-control @error('business_name') is-invalid @enderror" id="floatingInput1"
                            placeholder="Business Name">
                        <label for="floatingInput1">Business Name</label>
                        @error('business_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <select id="floatingSelect" name="profession"
                            class="form-select @error('profession') is-invalid @enderror"">
                            <option selected disabled>Select Profession</option>
                            @foreach ($professions as $profession)
                                <option value="{{ $profession->id }}"
                                    {{ $vendor->profession == $profession->id ? 'selected="selected"' : '' }}>
                                    {{ $profession->profession_name }}</option>
                            @endforeach
                        </select>
                        <label for="floatingSelect">Profession</label>
                        @error('profession')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="number" name="fax_number" value="{{ $vendor->fax_number }}"
                            class="form-control @error('fax_number') is-invalid @enderror" id="floatingInput2"
                            placeholder="Fax Number">
                        <label for="floatingInput2">Fax Number</label>
                        @error('fax_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <div class="form-floating">
                            <input type="tel" name="mobile"
                                class="form-control phone @error('mobile') is-invalid @enderror"
                                value="{{ $vendor->phone_number }}" id="telephone" placeholder="mobile">
                        </div>
                        @error('mobile')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            id="floatingInput3" value="{{ $vendor->email }}" placeholder="name@example.com">
                        <label for="floatingInput3">Email</label>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="number" name="business_contact" value="{{ $vendor->business_contact }}"
                            class="form-control @error('business_contact') is-invalid @enderror" id="floatingInput4"
                            placeholder="Business Contact">
                        <label for="floatingInput4">Business Contact</label>
                        @error('business_contact')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="street" class="form-control @error('street') is-invalid @enderror"
                            value="{{ $vendor->address }}" id="floatingInput5" placeholder="Street">
                        <label for="floatingInput5">Street</label>
                        @error('street')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                            value="{{ $vendor->city }}" id="floatingInput6" placeholder="City">
                        <label for="floatingInput6">City</label>
                        @error('city')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                            value="{{ $vendor->state }}" id="floatingInput7" placeholder="State">
                        <label for="floatingInput7">State</label>
                        @error('state')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="number" name="zip" class="form-control @error('zip') is-invalid @enderror"
                            value="{{ $vendor->zip }}" id="floatingInput8" placeholder="Zip/postal">
                        <label for="floatingInput8">Zip/postal</label>
                        @error('zip')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="text-end">
                    <input type="submit" value="Save" class="primary-fill" id="updateBusinessSaveBtn">
                    <a href="{{ route('admin.partners') }}" class="primary-empty">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/validation.js') }}"></script>
@endsection
