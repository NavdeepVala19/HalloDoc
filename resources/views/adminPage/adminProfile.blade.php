@extends('index')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/providerPage/providerProfile.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
@endsection
@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('providerLocation') }}">Provider Location</a>
    <a href="{{ route('admin.profile.editing') }}" class="active-link">My Profile</a>
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
        <h1 class="heading">My Profile</h1>
        <a href="{{route('admin.dashboard')}}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
    </div>
    <div class="section">
        @if (Session::has('message'))
        <div class="alert alert-success popup-message" role="alert">
            {{ Session::get('message') }}
        </div>
        @endif

        <form action="{{ route('adminChangePassword', $adminProfileData->user_id) }}" method="POST" id="adminEditProfileForm1">
            @csrf
            <h3>Account Information</h3>
            <div class="grid-2">
                <div class="form-floating">
                    <input type="text" name="user_name" class="form-control admin_user_name" id="floatingInput1" placeholder="User Name" autocomplete="off" value="{{ $adminProfileData->users->username }}" disabled>
                    <label for="floatingInput1">User Name</label>
                </div>

                <div class="form-floating errorMsg">
                    <input type="password" name="password" class="form-control admin-password" id="floatingInput2" placeholder="password" autocomplete="off">
                    <label for="floatingInput2">Password</label>
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


                <div class="form-floating status-select">
                    <select class="form-select" disabled id="status-select">
                        <option selected>Status</option>
                        <option value="1" {{$adminProfileData->status == 'pending'?'selected':''}}>Pending</option>
                        <option value="2" {{$adminProfileData->status == 'active'?'selected':''}}>Active</option>
                        <option value="3" {{$adminProfileData->status == 'inactive'?'selected':''}}>Not Active</option>
                    </select>
                </div>

                <div class="form-floating role-select">
                    <select class="form-select" id="listing_role_admin_Account" disabled>
                        <option selected>{{$adminProfileData->name}}</option>
                    </select>
                </div>
            </div>
            <div class="text-end">
                <button class="primary-empty" type="submit" id="adminResetPassword">Reset Password</button>
            </div>

        </form>

        <h3 class="mt-2">Administrator Information</h3>

        <form action="{{route('adminInfoUpdate', $adminProfileData->user_id)}}" method="post" id="adminEditProfileForm2">
            @csrf
            <div class="grid-2">
                <div class="form-floating errorMsg">
                    <input type="text" name="first_name" class="form-control admin_first_name" id="floatingInput3" placeholder="First Name"  autocomplete="off" value="{{ $adminProfileData->first_name }}" disabled>
                    <label for="floatingInput3">First Name</label>
                    @error('first_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating errorMsg">
                    <input type="text" name="last_name" class="form-control admin_last_name" id="floatingInput4" placeholder="Last Name" autocomplete="off" value="{{ $adminProfileData->last_name }}" disabled>
                    <label for="floatingInput4">Last Name</label>
                    @error('last_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating errorMsg">
                    <input type="email" class="form-control admin_email" id="floatingInput5" name="email" placeholder="name@example.com" autocomplete="off" value="{{ $adminProfileData->email }}" disabled>
                    <label for="floatingInput5">Email</label>
                         @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating errorMsg">
                    <input type="email" class="form-control admin_confirm_email" id="floatingInput6" name="confirm_email" placeholder="name@example.com"  autocomplete="off" value="{{ $adminProfileData->email }}" disabled>
                    <label for="floatingInput6">Confirm Email</label>
                    @error('confirm_email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating errorMsg" style="height: 58px;">
                    <input type="tel" name="phone_number" class="form-control phone" id="telephone" value="{{ $adminProfileData->mobile }}" disabled autocomplete="off">
                    @error('phone_number')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

            </div>
            <div class="text-end">
                <button class="primary-fill" type="button" id="adminEditBtn1">Edit</button>

                <button class="primary-fill admin-info-btns" type="submit">Save</button>
                <button class="btn btn-danger admin-info-btns" type="button" id="admin-info-cancel-btn">Cancel</button>

            </div>
        </form>

        <h3 class="mt-2">Mailing & Billing Information</h3>

        <form action="{{route('adminMailInfoUpdate', $adminProfileData->user_id)}}" method="post" id="adminEditProfileForm3">
            @csrf
            <div class="grid-2">
                <div class="form-floating errorMsg">
                    <input type="text" name="address1" class="form-control admin_add1" id="floatingInput7" placeholder="Address 1" autocomplete="off" value="{{ $adminProfileData->address1 }}" disabled>
                    <label for="floatingInput7">Address 1</label>
                         @error('address1')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating errorMsg">
                    <input type="text" name="address2" class="form-control admin_add2" id="floatingInput8" placeholder="Address 2" autocomplete="off" value="{{ $adminProfileData->address2 }}" disabled>
                    <label for="floatingInput8">Address 2</label>
                         @error('address2')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating errorMsg">
                    <input type="text" name="city" class="form-control city" id="floatingInput9" placeholder="city" autocomplete="off" value="{{ $adminProfileData->city }}" disabled>
                    <label for="floatingInput9">City</label>
                    @error('city')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    {{-- Dropdown State Selection --}}
                    <div class="form-floating errorMsg">
                        <select class="form-select admin_state" id="listing_state_admin_account" aria-label="Floating label select example" disabled name="select_state" autocomplete="off">
                            <option value="">Select State</option>
                            <option selected value="{{$adminProfileData->id }}">{{ $adminProfileData->region_name }}</option>
                        </select>
                        <label for="floatingSelect">State</label>
                    </div>
                </div>
                <div class="form-floating errorMsg">
                    <input type="number" name="zip" class="form-control admin_zipcode" id="floatingInput10" placeholder="zip" value="{{ $adminProfileData->zip }}" disabled min="0" autocomplete="off">
                    <label for="floatingInput10">Zip</label>
                    @error('zip')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div style="height: 58px;" class="errorMsg">
                    <input type="number" name="alt_mobile" class="form-control admin_alt_phone" id="telephone" placeholder="mobile" value="{{ $adminProfileData->alt_phone }}"  autocomplete="off"disabled min="10">
                    @error('alt_mobile')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="text-end">
                <button class="primary-fill" id="adminEditBtn2" type="button">Edit</button>
                <button class="primary-fill admin-mail-info-btns" type="submit">Save</button>
                <button class="btn btn-danger admin-mail-info-btns" type="button" id="admin-mail-cancel-btn">Cancel</button>
            </div>
        </form>
        <hr>
    </div>
</div>
@endsection

@section('script')
<script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
<script defer src="{{ asset('assets/adminPage/adminProfileEdit.js') }}"></script>
@endsection