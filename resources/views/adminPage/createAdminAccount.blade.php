@extends('index')
@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/providerPage/providerProfile.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/admin/adminForm.css') }}">
@endsection

@section('nav-links')
<a href="{{route('admin.dashboard')}}">Dashboard</a>
<a href="{{route('providerLocation')}}">Provider Location</a>
<a href="">My Profile</a>
<div class="dropdown record-navigation">
    <button class="record-btn " type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Providers
    </button>
    <ul class="dropdown-menu records-menu">
        <li><a class="dropdown-item " href="">Provider</a></li>
        <li><a class="dropdown-item" href="">Scheduling</a></li>
        <li><a class="dropdown-item" href="">Invoicing</a></li>
    </ul>
</div>
<a href="{{ route('admin.partners') }}">Partners</a>
<a href="" class="active-link">Access</a>
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
        <a href="" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
    </div>

    <div class="section">

        <form action="" method="POST">
            @csrf
            <h3>Account Information</h3>
            <div class="grid-3">
                <div class="form-floating ">
                    <input type="text" name="user_name" class="form-control" id="floatingInput" placeholder="User Name" value="">
                    <label for="floatingInput">User Name</label>
                </div>

                <div class="form-floating ">
                    <input type="password" name="password" class="form-control" id="floatingInput" placeholder="password" value="">
                    <label for="floatingInput">Password</label>
                </div>

                <div class="form-floating role-select">
                    <select class="form-select">
                        <option selected>Role</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                    </input>
                </div>


            </div>

            <h3>Administrator Information</h3>
            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="first_name" class="form-control" id="floatingInput" placeholder="First Name" value="">

                    <label for="floatingInput">First Name</label>
                    @error('first_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating ">
                    <input type="text" name="last_name" class="form-control" id="floatingInput" placeholder="Last Name" value="">
                    <label for="floatingInput">Last Name</label>
                    @error('last_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" value="">
                    <label for="floatingInput">Email</label>
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control" id="floatingInput" name="confirm-email" placeholder="name@example.com" value="">
                    <label for="floatingInput">Confirm Email</label>
                </div>

                <input type="tel" name="phone_number" class="form-control phone" id="telephone" placeholder="Phone Number" value="">
                @error('phone_number')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="d-flex gap-4 checkboxes">
                    @foreach ($regions as $region)
                    <div class="form-check region-no-{{ $region->id }}">
                        <input class="form-check-input" type="checkbox" name="region_id[]" id="region_{{ $region->id }}" value="{{ $region->id }}" @if (in_array($region->id, $selectedRegionIds ?? []))
                        checked
                        @endif
                        >
                        <label class="form-check-label" for="region_{{ $region->id }}">
                            {{ $region->region_name }}
                        </label>
                    </div>
                    @endforeach

                    <!-- <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            Default checkbox
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            Default checkbox
                        </label>
                    </div> -->

                </div>
            </div>

            <h3>Mailing & Billing Information</h3>
            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="address1" class="form-control" id="floatingInput" placeholder="Address 1" value="">
                    <label for="floatingInput">Address 1</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="address2" class="form-control" id="floatingInput" placeholder="Address 2" value="">
                    <label for="floatingInput">Address 2</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="city" class="form-control" id="floatingInput" placeholder="city" value="">
                    <label for="floatingInput">City</label>
                </div>
                <div>
                    {{-- Dropdown State Selection --}}
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <label for="floatingSelect">State</label>
                    </div>
                </div>
                <div class="form-floating ">
                    <input type="text" name="zip" class="form-control" id="floatingInput" placeholder="zip" value="">
                    <label for="floatingInput">Zip</label>
                </div>
                <input type="tel" name="alt_mobile" class="form-control phone" id="telephone" placeholder="mobile" value="">
                @error('mobile')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>


            <div class="d-flex flex-row justify-content-end gap-3 mt-3">
                <button class="primary-fill" type="submit">Create Account</button>

            </div>
        </form>
        <hr>

    </div>

</div>
@endsection