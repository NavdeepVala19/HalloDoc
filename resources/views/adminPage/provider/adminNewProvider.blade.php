@extends('index')
@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/adminProvider/adminEditProvider.css') }}">
@endsection
@section('username')
{{ !empty(Auth::user()) ? Auth::user()->username : '' }}
@endsection
@section('nav-links')
<a href="{{ route('admin.dashboard') }}">Dashboard</a>
<a href="{{ route('provider.location') }}">Provider Location</a>
<a href="{{ route('admin.profile.editing') }}">My Profile</a>
<div class="dropdown record-navigation">
    <button class="record-btn active-link" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
        <h2 class="heading">Create New Physician Account</h2>
        <a href="{{ route('admin.providers.list') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
    </div>

    <div class="section">
        <form action="{{ route('admin.created.provider') }}" method="POST" enctype="multipart/form-data" id="createAdminProvider">
            @csrf
            <h3>Account Information</h3>
            <div class="grid-3">
                <div class="form-floating provider-form">
                    <input type="text" name="user_name" class="form-control @error('user_name') is-invalid @enderror" id="floatingInput1" autocomplete="off" placeholder="User Name" value="{{ old('user_name') }}">
                    <label for="floatingInput1">User Name</label>
                    @error('user_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating provider-form">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="floatingInput2"  autocomplete="off" placeholder="password" value="{{ old('password') }}" autocomplete="on">
                    <label for="floatingInput2">Password</label>
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating role-select provider-form" >
                    <select class="form-select @error('role') is-invalid @enderror" id="provider_role" name="role" value="{{ old('role') }}">
                        <option selected value="">Role</option>
                    </select>
                    @error('role')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <h3>Administrator Information</h3>
            <div class="grid-2">
                <div class="form-floating provider-form" >
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" id="floatingInput3" autocomplete="off" placeholder="First Name" value="{{ old('first_name') }}">
                    <label for="floatingInput3">First Name</label>
                    @error('first_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating provider-form" >
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" id="floatingInput4"  autocomplete="off" placeholder="Last Name" value="{{ old('last_name') }}">
                    <label for="floatingInput4">Last Name</label>
                    @error('last_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating provider-form" >
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="floatingInput5" placeholder="name@example.com"  autocomplete="off" name="email" value="{{ old('email') }}">
                    <label for="floatingInput5">Email</label>
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating provider-form" style="height: 58px;">
                    <input type="tel" name="phone_number" class="form-control phone @error('phone_number') is-invalid @enderror" id="telephone" autocomplete="off" placeholder="Phone Number" value="{{ old('phone_number') }}">
                    @error('phone_number')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating provider-form">
                    <input type="number" name="medical_license" class="form-control @error('medical_license') is-invalid @enderror" autocomplete="off" id="floatingInput6" placeholder="Medical License" value="{{ old('medical_license') }}" min="0">
                    <label for="floatingInput6">Medical license # </label>
                    @error('medical_license')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating provider-form" >
                    <input type="number" name="npi_number" class="form-control @error('npi_number') is-invalid @enderror" id="floatingInput7" autocomplete="off" placeholder="NPI Number" value="{{ old('npi_number') }}" min="0">
                    <label for="floatingInput7">NPI Number</label>
                    @error('npi_number')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex gap-4 flex-wrap provider-form" >
                    @foreach ($regions as $region)
                    <div class="form-check">
                        <input class="form-check-input region-checkbox" type="checkbox" name="region_id[]" id="region_{{ $region->id }}" autocomplete="off" value="{{ $region->id }}" @if (in_array($region->id, $selectedRegionIds ?? [])) checked @endif>
                        <label class="form-check-label" for="region_{{ $region->id }}">
                            {{ $region->region_name }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            <h3>Mailing & Billing Information</h3>
            <div class="grid-2">
                <div class="form-floating provider-form" >
                    <input type="text" name="address1" class="form-control @error('address1') is-invalid @enderror" id="floatingInput8" autocomplete="off" placeholder="Address 1" value="{{ old('address1') }}">
                    <label for="floatingInput8">Address 1</label>
                    @error('address1')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating provider-form" >
                    <input type="text" name="address2" class="form-control @error('address2') is-invalid @enderror" id="floatingInput9"  autocomplete="off" placeholder="Address 2" value="{{ old('address2') }}">
                    <label for="floatingInput9">Address 2</label>
                    @error('address2')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating provider-form" >
                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" id="floatingInput10" autocomplete="off" placeholder="city" value="{{ old('city') }}">
                    <label for="floatingInput10">City</label>
                    @error('city')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <div class="form-floating provider-form" >
                        <select class="form-select @error('select_state') is-invalid @enderror" id="floatingSelect" autocomplete="off" aria-label="Floating label select example" name="select_state" value="{{ old('select_state') }}">
                            <option value="">State</option>
                            <option value="1">Somnath</option>
                            <option value="2">Dwarka</option>
                            <option value="3">Rajkot</option>
                            <option value="4">Bhavnagar</option>
                            <option value="5">Ahmedabad</option>
                        </select>
                        </input>
                    </div>
                </div>
                <div class="form-floating provider-form" >
                    <input type="number" name="zip" class="form-control @error('zip') is-invalid @enderror" autocomplete="off" id="floatingInput11" placeholder="zip" value="{{ old('zip') }}" min="0">
                    <label for="floatingInput11">Zip</label>
                    @error('zip')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="provider-form" style="height: 58px;">
                    <input type="number" name="phone_number_alt" class="form-control phone @error('phone_number_alt') is-invalid @enderror" id="" placeholder="Phone Number" autocomplete="off" value="{{ old('phone_number_alt') }}" min="0">
                    @error('phone_number_alt')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <h3>Provider Profile</h3>
            <div class="grid-2">
                <div class="form-floating provider-form" >
                    <input type="text" name="business_name" class="form-control @error('business_name') is-invalid @enderror" autocomplete="off" id="floatingInput12" placeholder="Business Name" value="{{ old('business_name') }}">
                    <label for="floatingInput12">Business Name</label>
                    @error('business_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating provider-form" >
                    <input type="url" name="business_website" class="form-control @error('business_website') is-invalid @enderror" autocomplete="off" id="floatingInput13" placeholder="Business Website" value="{{ old('business_website') }}">
                    <label for="floatingInput13">Business Website</label>
                    @error('business_website')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="custom-file-input mb-4 provider-form" >
                <input type="file" name="provider_photo" id="file-upload-request" hidden>
                <label for="file-upload-request" class="upload-label">
                    <div class="p-2 file-label">
                        Select File
                    </div>
                    <span class="primary-fill upload-btn">
                        <i class="bi bi-cloud-arrow-up me-2"></i>
                        <span class="upload-txt">Upload</span>
                    </span>
                </label>
                @error('provider_photo')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating provider-form" >
                <textarea class="form-control @error('admin_notes') is-invalid @enderror" autocomplete="off" placeholder="Admin_Notes" id="floatingTextarea2" name="admin_notes" style="height: 120px">{{ old('admin_notes') }}</textarea>
                <label for="floatingTextarea2">Admin Notes</label>
                @error('admin_notes')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <hr>
            <div>
                <h3>Onboarding</h3>
                <div class="table mt-4">
                    <table>
                        <tbody>
                            <tr class="border-bottom-table">
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="ms-2">
                                            Independent Contractor Agreement
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="ms-4 btns provider-form">
                                        <label for="independent_contractor" class="upload primary-fill">  <i class="bi bi-cloud-arrow-up"></i> <span class="upload-txt">Upload</span> </label>
                                        <input type="file" id="independent_contractor" class="independent-contractor-input" name="independent_contractor" hidden>
                                        <div id="Contractor" class="p-2"></div>
                                        @error('independent_contractor')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </td>
                            </tr>

                            <tr class="border-bottom-table">
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="ms-2">
                                            Background Check
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="ms-4 btns provider-form" >
                                        <label for="background-input" class="upload primary-fill">  <i class="bi bi-cloud-arrow-up"></i> <span class="upload-txt">Upload</span> </label>
                                        <input type="file" id="background-input" name="background_doc" hidden>
                                        <div id="Background" class="p-2"></div>
                                        @error('background_doc')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </td>
                            </tr>

                            <tr class="border-bottom-table">
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="ms-2">
                                            HIPAA Compliance
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="ms-4 btns provider-form" >
                                        <label for="hipaa-input" class="upload primary-fill"> <i class="bi bi-cloud-arrow-up"></i> <span class="upload-txt">Upload</span>  </label>
                                        <input type="file" id="hipaa-input" hidden name="hipaa_docs">
                                        
                                        <div id="HIPAA" class="p-2"></div>
                                        @error('hipaa_docs')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </td>
                            </tr>

                            <tr class="border-bottom-table">
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="ms-2">
                                            Non-disclosure Agreement
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="ms-4 btns provider-form" >
                                        <label for="non-disclosure-input" class="upload primary-fill">  <i class="bi bi-cloud-arrow-up"></i> <span class="upload-txt">Upload</span> </label>
                                        <input type="file" id="non-disclosure-input" hidden name="non_disclosure_doc">
                                
                                        <div class="non-disclosure" class="p-2"></div>
                                        @error('non_disclosure_doc')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <hr>

            <div class="d-flex flex-row justify-content-end gap-3">
                <button class="primary-fill-1" type="submit" id="createProvider">Create Account</button>

            </div>
    </div>
    @endsection



    @section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ URL::asset('assets/adminProvider/adminEditProvider.js') }}"></script>
    @endsection