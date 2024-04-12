@extends('index')
@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/providerPage/providerProfile.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/admin/adminForm.css') }}">
@endsection

@section('nav-links')
<a href="{{ route('admin.dashboard') }}">Dashboard</a>
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
        <a href="{{route('admin.user.access')}}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
    </div>
    <div class="section">
        <form action="{{route('adminAccountCreated')}}" method="POST" id="createAdminAccountForm">
            @csrf
            <h3>Account Information</h3>
            <div class="grid-3">
                <div class="form-floating">
                    <input type="text" name="user_name" class="form-control" id="floatingInput1" placeholder="User Name" value="{{ old('user_name') }}">
                    <label for="floatingInput1">User Name</label>
                    @error('user_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="password" name="password" class="form-control" id="floatingInput2" placeholder="password" value="{{ old('password') }}">
                    <label for="floatingInput2">Password</label>
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating role-select">
                    <select class="form-select" id="listing_role_admin_Account" name="role">
                        <option selected>All</option>
                    </select>
                </div>
            </div>
            <h3>Administrator Information</h3>
            <div class="grid-2">
                <div class="form-floating">
                    <input type="text" name="first_name" class="form-control" id="floatingInput3" placeholder="First Name" value="{{ old('first_name') }}">
                    <label for="floatingInput3">First Name</label>
                    @error('first_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="text" name="last_name" class="form-control" id="floatingInput4" placeholder="Last Name" value="{{ old('last_name') }}">
                    <label for="floatingInput4">Last Name</label>
                    @error('last_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInput5" name="email" placeholder="name@example.com" value="{{ old('email') }}">
                    <label for="floatingInput5">Email</label>
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInput6" name="confirm_email" placeholder="name@example.com" value="{{ old('confirm_email') }}">
                    <label for="floatingInput6">Confirm Email</label>
                    @error('confirm_email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating" style="height: 58px;">
                    <input type="tel" name="phone_number" class="form-control phone" id="telephone" value="{{ old('phone_number') }}">
                    @error('phone_number')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex gap-4 checkboxes">
                    @foreach ($regions as $region)
                    <div class="form-check region-no-{{ $region->id }}">
                        <input class="form-check-input" type="checkbox" name="region_id[]" id="region_{{ $region->id }}" value="{{ $region->id }}" @if (in_array($region->id, $selectedRegionIds ?? [])) checked @endif>
                        <label class="form-check-label" for="region_{{ $region->id }}"> {{ $region->region_name }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            <h3>Mailing & Billing Information</h3>
            <div class="grid-2">
                <div class="form-floating">
                    <input type="text" name="address1" class="form-control" id="floatingInput6" placeholder="Address 1" value="{{ old('address1') }}">
                    <label for="floatingInput6">Address 1</label>
                    @error('address1')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="text" name="address2" class="form-control" id="floatingInput7" placeholder="Address 2" value="{{ old('address2') }}">
                    <label for="floatingInput7">Address 2</label>
                    @error('address2')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="text" name="city" class="form-control" id="floatingInput8" placeholder="city" value="{{ old('city') }}">
                    <label for="floatingInput8">City</label>
                    @error('city')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <div class="form-floating">
                        <select class="form-select" id="listing_state_admin_account" name="state">
                            <option selected>All</option>
                        </select>
                    </div>
                </div>
                <div class="form-floating">
                    <input type="text" name="zip" class="form-control" id="floatingInput9" placeholder="zip" value="{{ old('zip') }}">
                    <label for="floatingInput9">Zip</label>
                    @error('zip')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div style="height: 58px;">
                    <input type="tel" name="alt_mobile" class="form-control phone" id="telephone" placeholder="mobile" value="{{ old('alt_mobile') }}">
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
<script defer src="{{ asset('assets/validation/jquery.validate.min.js')}}"></script>
<script defer src="{{ URL::asset('assets/adminPage/adminAccount.js') }}"></script>
@endsection