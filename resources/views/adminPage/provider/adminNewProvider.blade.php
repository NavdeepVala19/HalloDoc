@extends('index')
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminProvider/adminEditProvider.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('providerLocation') }}">Provider Location</a>
    <a href="{{route('admin.profile.editing')}}">My Profile</a>
    <div class="dropdown record-navigation">
        <button class="record-btn active-link" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
            <h2 class="heading">Create New Physician Account</h2>
            <a href="{{ route('adminProvidersInfo') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        <div class="section">

            <form action="{{ route('adminCreateNewProvider') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h3>Account Information</h3>

                <h3>Account Information</h3>

                <div class="grid-3">
                    <div class="form-floating ">
                        <input type="text" name="user_name" class="form-control" id="floatingInput"
                            placeholder="User Name" value="{{ old('user_name') }}">
                        <label for="floatingInput">User Name</label>
                        @error('first_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="password" name="password" class="form-control" id="floatingInput"
                            placeholder="password" value="{{ old('password') }}">
                        <label for="floatingInput">Password</label>
                        @error('first_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating role-select">
                        <select class="form-select" id="provider-role" name="role">
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
                        <input type="text" name="first_name" class="form-control" id="floatingInput"
                            placeholder="First Name" value="{{ old('first_name') }}">
                        <label for="floatingInput">First Name</label>
                        @error('first_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="last_name" class="form-control" id="floatingInput"
                            placeholder="Last Name" value="{{ old('last_name') }}">
                        <label for="floatingInput">Last Name</label>
                        @error('last_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com"
                            name="email" value="{{ old('email') }}">
                        <label for="floatingInput">Email</label>
                        @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating" style="height: 58px;">
                        <input type="tel" name="phone_number" class="form-control phone" id="telephone"
                            placeholder="Phone Number" value="{{ old('phone_number') }}">
                        @error('phone_number')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="number" name="medical_license" class="form-control" id="floatingInput"
                            placeholder="Medical License" value="{{ old('medical_license') }}">
                        <label for="floatingInput">Medical license # </label>
                        @error('medical_license')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating">
                        <input type="number" name="npi_number" class="form-control" id="floatingInput"
                            placeholder="NPI Number" value="{{ old('npi_number') }}">
                        <label for="floatingInput">NPI Number</label>
                        @error('npi_number')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="d-flex gap-4 flex-wrap">
                        @foreach ($regions as $region)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="region_id[]"
                                    id="region_{{ $region->id }}" value="{{ $region->id }}"
                                    @if (in_array($region->id, $selectedRegionIds ?? [])) checked @endif>
                                <label class="form-check-label" for="region_{{ $region->id }}">
                                    {{ $region->region_name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                </div>
                <h3>Mailing & Billing Information</h3>
                <div class="grid-2">

                    <div class="form-floating ">
                        <input type="text" name="address1" class="form-control" id="floatingInput"
                            placeholder="Address 1" value="{{ old('address1') }}">
                        <label for="floatingInput">Address 1</label>
                        @error('address1')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="address2" class="form-control" id="floatingInput"
                            placeholder="Address 2" value="{{ old('address2') }}">
                        <label for="floatingInput">Address 2</label>
                        @error('address2')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="city" class="form-control" id="floatingInput" placeholder="city"
                            value="{{ old('city') }}">
                        <label for="floatingInput">City</label>
                        @error('city')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        {{-- Dropdown State Selection --}}
                        <div class="form-floating">
                            <select class="form-select" id="floatingSelect" aria-label="Floating label select example"
                                name="select-state" value="{{ old('select-state') }}">
                                <option selected>State</option>
                                <option value="1">Somnath</option>
                                <option value="2">Dwarka</option>
                                <option value="3">Rajkot</option>
                                <option value="3">Bhavnagar</option>
                                <option value="3">Ahmedabad</option>
                            </select>
                            </input>
                        </div>

                    </div>

                    <div class="form-floating ">
                        <input type="number" name="zip" class="form-control" id="floatingInput" placeholder="zip"
                            value="{{ old('zip') }}">
                        <label for="floatingInput">Zip</label>
                        @error('zip')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="height: 58px;">
                        <input type="tel" name="phone_number_alt" class="form-control phone" id="telephone"
                            placeholder="Phone Number" value="{{ old('phone_number_alt') }}">
                    </div>


                </div>
                <h3>Provider Profile</h3>
                <div class="grid-2">

                    <div class="form-floating ">
                        <input type="text" name="business_name" class="form-control" id="floatingInput"
                            placeholder="Business Name" value="{{ old('business_name') }}">
                        <label for="floatingInput">Business Name</label>
                        @error('business_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="business_website" class="form-control" id="floatingInput"
                            placeholder="Business Website" value="{{ old('business_website') }}">
                        <label for="floatingInput">Business Website</label>

                    </div>

                    <hr>
                    <div>
                        {{-- Select Photo --}}
                        <div class="custom-file-input" onclick="openFileSelection()">
                            <input type="text" placeholder="Select Photo" readonly name="provider_photo">
                            <label for="file-input"><i class="bi bi-cloud-arrow-up me-2 "></i> <span
                                    class="upload-txt">Upload</span> </label>
                            <input type="file" id="file-input" class="file-input-provider_photo" hidden
                                name="provider_photo">
                            <p id="provider_photo"></p>
                        </div>
                    </div>
                </div>

                <div class="form-floating">
                    <textarea class="form-control" placeholder="Admin_Notes" id="floatingTextarea2" name="admin_notes"
                        style="height: 120px" value="{{ old('admin_notes') }}"></textarea>
                    <label for="floatingTextarea2">Admin Notes</label>
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
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="flexCheckDefault" name="independent_contract_check">
                                            <span class="ms-2">
                                                Independent Contractor Agreement
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="ms-4 btns" onclick="openFileSelection()">
                                            <label for="independent_contractor" class="upload primary-fill"> <span
                                                    class="upload-txt">Upload</span> </label>

                                            <input type="file" id="independent_contractor"
                                                class="independent-contractor-input" name="independent_contractor" hidden>

                                            <p id="Contractor"></p>
                                        </div>

                                        <div class="ms-4 responsive-btns">
                                            <label for="independent_contractor" class="upload primary-fill"> <i
                                                    class="bi bi-cloud-arrow-up"></i> </label>
                                            <input type="file" id="fileInput-independent_contractor-agreement"
                                                class="independent-contractor-input" name="independent_contractor-btn"
                                                hidden>


                                            <p id="Contractor"></p>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="border-bottom-table">
                                    <td>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="flexCheckDefault" name="background_check">
                                            <span class="ms-2">
                                                Background Check
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="ms-4 btns">
                                            <label for="background-input" class="upload primary-fill"> <span
                                                    class="upload-txt">Upload</span> </label>
                                            <input type="file" id="background-input" name="background_doc" hidden>

                                            <p id="Background"></p>
                                        </div>

                                        <div class="ms-4 responsive-btns">
                                            <button class="primary-fill mt-2 mb-3" name="background_doc-btn"><i
                                                    class="bi bi-cloud-arrow-up"></i></button>

                                            <p id="Background"></p>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="border-bottom-table">
                                    <td>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="flexCheckDefault" name="HIPAA_check">
                                            <span class="ms-2">
                                                HIPAA Compliance
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="ms-4 btns">
                                            <label for="hipaa-input" class="upload primary-fill"> <span
                                                    class="upload-txt">Upload</span> </label>
                                            <input type="file" id="hipaa-input" hidden name="hipaa_docs">

                                            <p id="HIPAA"></p>
                                        </div>
                                        <div class="ms-4 responsive-btns">
                                            <button class="primary-fill mt-2 mb-3" name="hipaa_docs-btn"><i
                                                    class="bi bi-cloud-arrow-up"></i></button>

                                            <p id="HIPAA"></p>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="border-bottom-table">
                                    <td>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="flexCheckDefault" name="non_disclosure_doc">
                                            <span class="ms-2">
                                                Non-disclosure Agreement
                                            </span>
                                        </div>

                                    </td>
                                    <td>
                                        <div class="ms-4 btns">
                                            <label for="non-disclosure-input" class="upload primary-fill"> <span
                                                    class="upload-txt">Upload</span> </label>
                                            <input type="file" id="non-disclosure-input" hidden
                                                name="non_disclosure_doc">
                                            <p class="non-disclosure"></p>
                                        </div>
                                        <div class="ms-4 responsive-btns">
                                            <button class="primary-fill mb-2 mt-2" name="non_disclosure_doc-btn"><i
                                                    class="bi bi-cloud-arrow-up"></i></button>
                                            <p class="non-disclosure"></p>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="d-flex flex-row justify-content-end gap-3">
                    <button class="primary-fill-1" type="submit">Create Account</button>

                </div>
        </div>
    @endsection



    @section('script')
        <script defer src="{{ URL::asset('assets/adminProvider/adminEditProvider.js') }}"></script>
    @endsection
