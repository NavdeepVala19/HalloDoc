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
        <li><a class="dropdown-item " href="{{ route('adminProvidersInfo') }}">Provider</a></li>
        <li><a class=" dropdown-item" href="">Scheduling</a></li>
        <li><a class="dropdown-item" href="">Invoicing</a></li>
    </ul>
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


@if (Session::has('message'))
<div class="alert alert-success popup-message" role="alert">
    {{ Session::get('message') }}
</div>
@endif

<div class="container form-container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="heading">My Profile</h1>
        <a href="{{route('admin.dashboard')}}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
    </div>

    <div class="section">

        <form action="{{route('adminChangePassword',$adminProfileData->user_id)}}" method="POST" id="patientProfileEditForm">
            @csrf
            <h3>Account Information</h3>
            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="user_name" class="form-control" id="floatingInput" placeholder="User Name" value="{{ $adminProfileData->users->username}}" disabled>
                    <label for="floatingInput">User Name</label>
                </div>

                <div class="form-floating ">
                    <input type="password" name="password" class="form-control admin-password" id="floatingInput" placeholder="password">
                    <label for="floatingInput">Password</label>
                </div>


                <div class="form-floating status-select">
                    <select class="form-select" name="status" disabled>
                        <option value="{{ $adminProfileData->status }}" selected> {{ $adminProfileData->status }} </option>
                        <option value="pending">pending</option>
                        <option value="active">active</option>
                        <option value="inactive">inactive</option>
                    </select>
                </div>

                <div class="form-floating role-select">
                    <select class="form-select" name="role" disabled>
                        <option selected value="{{$adminProfileData->name}}">{{$adminProfileData->name}}</option>
                    </select>
                    </input>
                </div>


            </div>
            <div class="text-end">
                <button class="primary-empty" type="submit" id="adminResetPassword">Reset Password</button>
            </div>

        </form>


        <h3>Administrator Information</h3>

        <form action="{{route('adminInfoUpdate',$adminProfileData->user_id)}}" method="POST" id="patientProfileEditForm">
            @csrf
            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="first_name" class="form-control admin_first_name" id="floatingInput" placeholder="First Name" value="{{ $adminProfileData->first_name}}" disabled>

                    <label for="floatingInput">First Name</label>
                    @error('first_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating ">
                    <input type="text" name="last_name" class="form-control admin_last_name" id="floatingInput" placeholder="Last Name" value="{{ $adminProfileData->last_name}}" disabled>
                    <label for="floatingInput">Last Name</label>
                    @error('last_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control admin_email" id="floatingInput" name="email" placeholder="name@example.com" value="{{ $adminProfileData->email}}" disabled>
                    <label for="floatingInput">Email</label>
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control admin_confirm_email" id="floatingInput" name="confirm-email" placeholder="name@example.com" value="{{ $adminProfileData->email}}" disabled>
                    <label for="floatingInput">Confirm Email</label>
                </div>

                <div class="form-floating " style="height: 58px;">
                    <input type="tel" name="phone_number" class="form-control phone" id="telephone" placeholder="Phone Number" value="{{ $adminProfileData->mobile}}" disabled>
                    @error('phone_number')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>


            </div>
            <div class="text-end">
                <button class="primary-fill" type="button" id="adminEditBtn1">Edit</button>
            </div>

            <div class="d-flex flex-row justify-content-end gap-3 mt-3">
                <button class="primary-fill admin-info-btns" type="submit" id="admin-info-save-btn">Save</button>
                <a href="" class="btn btn-danger admin-info-btns" id="admin-info-cancel-btn">Cancel</a>
            </div>

        </form>


        <h3>Mailing & Billing Information</h3>

        <form action="{{route('adminMailInfoUpdate',$adminProfileData->user_id)}}" method="POST" id="patientProfileEditForm">
            @csrf
            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="address1" class="form-control admin_add1" id="floatingInput" placeholder="Address 1" value="{{ $adminProfileData->address1}}" disabled>
                    <label for="floatingInput">Address 1</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="address2" class="form-control admin_add2" id="floatingInput" placeholder="Address 2" value="{{ $adminProfileData->address2}}" disabled>
                    <label for="floatingInput">Address 2</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="city" class="form-control city" id="floatingInput" placeholder="city" value="{{ $adminProfileData->city}}" disabled>
                    <label for="floatingInput">City</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="zip" class="form-control admin_zipcode" id="floatingInput" placeholder="zip" value="{{ $adminProfileData->zip}}" disabled>
                    <label for="floatingInput">Zip</label>
                </div>
                <input type="tel" name="alt_mobile" class="form-control phone admin_alt_phone" id="telephone" placeholder="mobile" value="{{ $adminProfileData->alt_phone}}" disabled>
                @error('mobile')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="text-end">
                <button class="primary-fill" type="button" id="adminEditBtn2">Edit</button>
            </div>

            <div class="d-flex flex-row justify-content-end gap-3 mt-3">
                <button class="primary-fill admin-mail-info-btns" type="submit">Save</button>
                <a href="" class="btn btn-danger admin-mail-info-btns" id="admin-mail-cancel-btn">Cancel</a>
            </div>

        </form>

        <hr>

    </div>

</div>
@endsection



@section('script')
<script defer src="{{ asset('assets/validation/jquery.validate.min.js')}}"></script>
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>

<script defer src="{{ URL::asset('assets/adminPage/admin.js') }}"></script>
@endsection