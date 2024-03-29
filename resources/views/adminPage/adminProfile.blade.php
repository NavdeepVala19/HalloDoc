@extends('index')
@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/providerPage/providerProfile.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
@endsection

@section('nav-links')
<a href="{{route('admin.dashboard')}}">Dashboard</a>
<a href="{{route('providerLocation')}}">Provider Location</a>
<a href="" class="active-link">My Profile</a>
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
</div>
<a href="{{ route('admin.partners') }}">Partners</a>
<a href="{{ route('admin.access.view') }}">Access</a>
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
        <h1 class="heading">My Profile</h1>
        <a href="" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
    </div>

    <div class="section">

        <form action="{{route('adminProfileEdit',$adminProfileData->user_id)}}" method="POST">
            @csrf
            <h3>Account Information</h3>
            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="user_name" class="form-control" id="floatingInput" placeholder="User Name" value="{{ $adminProfileData->users->username}}">
                    <label for="floatingInput">User Name</label>
                </div>

                <div class="form-floating ">
                    <input type="password" name="password" class="form-control" id="floatingInput" placeholder="password" value="{{ $adminProfileData->users->password}}">
                    <label for="floatingInput">Password</label>
                </div>


                <div class="form-floating status-select">
                    <select class="form-select">
                        <option selected>Status</option>
                        <option value="1">Pending</option>
                        <option value="2">Active</option>
                        <option value="3">Not Active</option>
                    </select>
                    </input>
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
            <div class="text-end">
                <button class="primary-empty">Reset Password</button>
            </div>
            <h3>Administrator Information</h3>
            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="first_name" class="form-control" id="floatingInput" placeholder="First Name" value="{{ $adminProfileData->first_name}}">

                    <label for="floatingInput">First Name</label>
                    @error('first_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating ">
                    <input type="text" name="last_name" class="form-control" id="floatingInput" placeholder="Last Name" value="{{ $adminProfileData->last_name}}">
                    <label for="floatingInput">Last Name</label>
                    @error('last_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" value="{{ $adminProfileData->email}}">
                    <label for="floatingInput">Email</label>
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control" id="floatingInput" name="confirm-email" placeholder="name@example.com" value="{{ $adminProfileData->email}}">
                    <label for="floatingInput">Confirm Email</label>
                </div>

                <input type="tel" name="phone_number" class="form-control phone" id="telephone" placeholder="Phone Number" value="{{ $adminProfileData->mobile}}">
                @error('phone_number')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="d-flex gap-4 ">
                    <div class="form-check">
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
                    </div>
                </div>
            </div>
            <div class="text-end">
                <button class="primary-fill">Edit</button>
            </div>
            <h3>Mailing & Billing Information</h3>
            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="address1" class="form-control" id="floatingInput" placeholder="Address 1" value="{{ $adminProfileData->address1}}">
                    <label for="floatingInput">Address 1</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="address2" class="form-control" id="floatingInput" placeholder="Address 2" value="{{ $adminProfileData->address2}}">
                    <label for="floatingInput">Address 2</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="city" class="form-control" id="floatingInput" placeholder="city" value="{{ $adminProfileData->city}}">
                    <label for="floatingInput">City</label>
                </div>
                <div>
                    {{-- Dropdown State Selection --}}
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example" disabled>
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <label for="floatingSelect">State</label>
                    </div>
                </div>
                <div class="form-floating ">
                    <input type="text" name="zip" class="form-control" id="floatingInput" placeholder="zip" value="{{ $adminProfileData->zip}}">
                    <label for="floatingInput">Zip</label>
                </div>
                <input type="tel" name="alt_mobile" class="form-control phone" id="telephone" placeholder="mobile" value="{{ $adminProfileData->alt_phone}}">
                @error('mobile')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="text-end">
                <button class="primary-fill">Edit</button>
            </div>

            <div class="d-flex flex-row justify-content-end gap-3 mt-3">
                <button class="primary-fill" type="submit">Save</button>
                <a href="" class="btn btn-danger">Delete Account</a>
            </div>
        </form>
        <hr>

    </div>

</div>
@endsection