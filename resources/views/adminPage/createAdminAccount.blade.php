@extends('index')
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/providerProfile.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/adminForm.css') }}">
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
    <a href="{{ route('admin.partners') }}">Partners</a>
    <div class="dropdown record-navigation">
        <button class="record-btn active-link" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
            <h1 class="heading">Create Admin Account</h1>
            <a href="{{ route('admin.user.access') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        <div class="section">
            <form action="{{ route('new.admin.created') }}" method="POST" id="createAdminAccountForm">
                @csrf
                <h3>Account Information</h3>
                <div class="grid-3">
                    <div class="form-floating errorMsg">
                        <input type="text" name="user_name" class="form-control @error('user_name') is-invalid @enderror" id="floatingInput1"
                            placeholder="User Name" value="{{ old('user_name') }}" autocomplete="off">
                        <label for="floatingInput1">User Name</label>
                        @error('user_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating errorMsg">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="floatingInput2"
                            placeholder="password" autocomplete="off" value="{{ old('password') }}">
                        <label for="floatingInput2">Password</label>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating role-select errorMsg">
                        <select class="form-select @error('role') is-invalid @enderror" id="listing_role_admin_Account" name="role">
                            <option value="" selected>Role</option>
                        </select>
                        @error('role')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <h3>Administrator Information</h3>
                <div class="grid-2">
                    <div class="form-floating errorMsg">
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" id="floatingInput3"
                            placeholder="First Name" autocomplete="off" value="{{ old('first_name') }}">
                        <label for="floatingInput3">First Name</label>
                        @error('first_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating errorMsg">
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" id="floatingInput4"
                            placeholder="Last Name" autocomplete="off" value="{{ old('last_name') }}">
                        <label for="floatingInput4">Last Name</label>
                        @error('last_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating errorMsg">
                        <input type="email" class="form-control email @error('email') is-invalid @enderror" id="floatingInput5" name="email"
                            placeholder="name@example.com" autocomplete="off" value="{{ old('email') }}">
                        <label for="floatingInput5">Email</label>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating errorMsg">
                        <input type="email" class="form-control @error('confirm_email') is-invalid @enderror" id="floatingInput6" name="confirm_email"
                            placeholder="name@example.com" autocomplete="off" value="{{ old('confirm_email') }}">
                        <label for="floatingInput6">Confirm Email</label>
                        @error('confirm_email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating errorMsg @error('phone_number') is-invalid @enderror" style="height: 58px;">
                        <input type="tel" name="phone_number" class="form-control phone" id="telephone"
                            value="{{ old('phone_number') }}" autocomplete="off">
                        @error('phone_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex gap-4 checkboxes flex-wrap errorMsg">
                        @foreach ($regions as $region)
                            <div class="form-check region-no-{{ $region->id }} ">
                                <input class="form-check-input @error('region_id') is-invalid @enderror" type="checkbox" name="region_id[]"
                                    id="region_{{ $region->id }}" value="{{ $region->id }}"
                                    @if (in_array($region->id, $selectedRegionIds ?? [])) checked @endif>
                                <label class="form-check-label" for="region_{{ $region->id }}">
                                    {{ $region->region_name }}
                                </label>
                            </div>
                        @endforeach
                        @error('region_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <h3>Mailing & Billing Information</h3>
                <div class="grid-2">
                    <div class="form-floating errorMsg">
                        <input type="text" name="address1" class="form-control @error('address1') is-invalid @enderror" id="floatingInput7"
                            placeholder="Address 1" value="{{ old('address1') }}" autocomplete="off">
                        <label for="floatingInput7">Address 1</label>
                        @error('address1')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating errorMsg">
                        <input type="text" name="address2" class="form-control @error('address2') is-invalid @enderror" id="floatingInput8"
                            placeholder="Address 2" value="{{ old('address2') }}" autocomplete="off">
                        <label for="floatingInput8">Address 2</label>
                        @error('address2')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating errorMsg">
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" id="floatingInput9"
                            placeholder="city" value="{{ old('city') }}" autocomplete="off">
                        <label for="floatingInput9">City</label>
                        @error('city')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <div class="form-floating errorMsg">
                            <select class="form-select @error('state') is-invalid @enderror" id="listing_state_admin_account" name="state">
                                <option selected value="">Select State</option>
                            </select>
                        @error('state')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>
                    </div>
                    <div class="form-floating errorMsg">
                        <input type="text" name="zip" class="form-control @error('zip') is-invalid @enderror" id="floatingInput10"
                            placeholder="zip" value="{{ old('zip') }}" min="1" autocomplete="off">
                        <label for="floatingInput10">Zip</label>
                        @error('zip')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div style="height: 58px;" class="errorMsg">
                        <input type="number" name="alt_mobile" class="form-control phone @error('alt_mobile') is-invalid @enderror" id="telephone"
                            placeholder="mobile" value="{{ old('alt_mobile') }}" min="10" autocomplete="off">
                        @error('alt_mobile')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex flex-row justify-content-end gap-3 mt-3">
                    <button class="primary-fill-1" type="submit">Create Account</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    {{-- <script defer src="{{ URL::asset('assets/adminPage/adminAccount.js') }}"></script> --}}
@endsection
