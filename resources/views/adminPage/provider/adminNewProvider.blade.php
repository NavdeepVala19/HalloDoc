@extends('index')
@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/adminProvider/adminEditProvider.css') }}">

@endsection

@section('nav-links')
<a href="{{route('admin.dashboard')}}">Dashboard</a>
<a href="{{route('providerLocation')}}">Provider Location</a>
<a href="">My Profile</a>
<div class="dropdown record-navigation">
    <button class="record-btn active-link" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Providers
    </button>
    <ul class="dropdown-menu records-menu">
        <li><a class="dropdown-item active-link" href="">Provider</a></li>
        <li><a class="dropdown-item" href="">Scheduling</a></li>
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

<div class="container form-container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="heading">Create New Physician Account</h2>
        <a href="{{route('adminProvidersInfo')}}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
    </div>

    <div class="section">

        <form action="{{route('adminCreateNewProvider')}}" method="POST" enctype="multipart/form-data">
            @csrf

            <h3>Account Information</h3>

            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="user_name" class="form-control" id="floatingInput" placeholder="User Name">
                    <label for="floatingInput">User Name</label>
                    @error('first_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating ">
                    <input type="password" name="password" class="form-control" id="floatingInput" placeholder="password">
                    <label for="floatingInput">Password</label>
                    @error('first_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>


            </div>



            <h3>Physician Information</h3>

            <div class="grid-2">

                <div class="form-floating ">
                    <input type="text" name="first_name" class="form-control" id="floatingInput" placeholder="First Name">
                    <label for="floatingInput">First Name</label>
                    @error('first_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating ">
                    <input type="text" name="last_name" class="form-control" id="floatingInput" placeholder="Last Name">
                    <label for="floatingInput">Last Name</label>
                    @error('last_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email">
                    <label for="floatingInput">Email</label>
                </div>

                <input type="tel" name="phone_number" class="form-control phone" id="telephone" placeholder="Phone Number">
                @error('phone_number')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="form-floating ">
                    <input type="number" name="medical_license" class="form-control" id="floatingInput" placeholder="Medical License">
                    <label for="floatingInput">Medical license # </label>
                </div>

                <div class="form-floating ">
                    <input type="number" name="npi_number" class="form-control" id="floatingInput" placeholder="NPI Number">
                    <label for="floatingInput">NPI Number</label>
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email_alt">
                    <label for="floatingInput">Alternate Email</label>
                </div>

                <div class="d-flex gap-4">
                    @foreach ($regions as $region)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="region_id[]" id="region_{{ $region->id }}" value="{{ $region->id }}" @if (in_array($region->id, $selectedRegionIds ?? []))
                        checked
                        @endif
                        >
                        <label class="form-check-label" for="region_{{ $region->id }}">
                            {{ $region->region_name }}
                        </label>
                    </div>
                    @endforeach
                </div>

                <!-- 
                <div class="d-flex gap-4 ">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            District of Columbia
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            New York
                        </label>
                    </div>
                </div> -->



            </div>
            <h3>Mailing & Billing Information</h3>
            <div class="grid-2">

                <div class="form-floating ">
                    <input type="text" name="address1" class="form-control" id="floatingInput" placeholder="Address 1">
                    <label for="floatingInput">Address 1</label>
                </div>

                <div class="form-floating ">
                    <input type="text" name="address2" class="form-control" id="floatingInput" placeholder="Address 2">
                    <label for="floatingInput">Address 2</label>
                </div>

                <div class="form-floating ">
                    <input type="text" name="city" class="form-control" id="floatingInput" placeholder="city">
                    <label for="floatingInput">City</label>
                </div>

                <div>
                    {{-- Dropdown State Selection --}}
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                            <option selected>State</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <!-- <label for="floatingSelect">State</label> -->
                    </div>
                </div>

                <div class="form-floating ">
                    <input type="number" name="zip" class="form-control" id="floatingInput" placeholder="zip">
                    <label for="floatingInput">Zip</label>
                </div>

                <input type="tel" name="phone_number_alt" class="form-control phone" id="telephone" placeholder="Phone Number">
                @error('phone_number')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror




            </div>
            <h3>Provider Profile</h3>
            <div class="grid-2">

                <div class="form-floating ">
                    <input type="text" name="business_name" class="form-control" id="floatingInput" placeholder="Business Name">
                    <label for="floatingInput">Business Name</label>
                </div>

                <div class="form-floating ">
                    <input type="text" name="business_website" class="form-control" id="floatingInput" placeholder="Business Website">
                    <label for="floatingInput">Business Website</label>
                </div>

                <div>
                    {{-- Select Photo --}}
                    <div class="custom-file-input" onclick="openFileSelection()">
                        <input type="text" placeholder="Select Photo" readonly>
                        <label for="file-input"><i class="bi bi-cloud-arrow-up me-2 "></i> <span class="upload-txt">Upload</span> </label>
                        <input type="file" id="file-input" class="file-input-provider_photo" hidden name="provider_photo">
                        <p id="provider_photo"></p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-1 ">
                    {{-- Select Signature --}}
                    <div class="custom-file-input" onclick="openFileSelection()">
                        <input type="text" placeholder="Select Signature" readonly>
                        <label for="signature-input"><i class="bi bi-cloud-arrow-up me-2"></i><span class="upload-txt">Upload</span></label>
                        <input type="file" id="signature-input" class="file-input-provider_signature" name="provider_signature" hidden>
                        <p id="provider_signature"></p>
                    </div>

                    <button class="create-signature-btn"><i class="bi bi-pencil me-2 "></i>Create</button>
                    {{-- <canvas id="signatureCanvas" width="300" height="150"></canvas> --}}
                </div>


            </div>

            <div class="form-floating">
                <textarea class="form-control" placeholder="Admin_Notes" id="floatingTextarea2" name="admin_notes" style="height: 120px"></textarea>
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
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="independent_contract_check">
                                        <span class="ms-2">
                                            Independent Contractor Agreement
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="ms-4 btns" onclick="openFileSelection()">
                                        <label for="independent_contractor" class="upload primary-fill"> <span class="upload-txt">Upload</span> </label>

                                        <input type="file" id="independent_contractor" class="independent-contractor-input" name="independent_contractor" hidden>

                                        <button class="primary-fill ms-4">View</button>
                                        <p id="Contractor"></p>
                                    </div>

                                    <div class="ms-4 responsive-btns">
                                        <label for="independent_contractor" class="upload primary-fill"> <i class="bi bi-cloud-arrow-up"></i> </label>
                                        <input type="file" id="fileInput-independent_contractor-agreement" class="independent-contractor-input" name="independent_contractor-btn" hidden>

                                        <button class="primary-fill mb-2"><i class="bi bi-eye"></i></button>
                                        <p id="Contractor"></p>
                                    </div>
                                </td>
                            </tr>

                            <tr class="border-bottom-table">
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="background_check">
                                        <span class="ms-2">
                                            Background Check
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="ms-4 btns">
                                        <label for="background-input" class="upload primary-fill"> <span class="upload-txt">Upload</span> </label>
                                        <input type="file" id="background-input" name="background_doc" hidden>
                                        <button class="primary-fill ms-4">View</button>
                                        <p id="Background"></p>
                                    </div>

                                    <div class="ms-4 responsive-btns">
                                        <button class="primary-fill mt-2 mb-3" name="background_doc-btn"><i class="bi bi-cloud-arrow-up"></i></button>
                                        <button class="primary-fill mb-2"><i class="bi bi-eye"></i></button>
                                        <p id="Background"></p>
                                    </div>
                                </td>
                            </tr>

                            <tr class="border-bottom-table">
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="HIPAA_check">
                                        <span class="ms-2">
                                            HIPAA Compliance
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="ms-4 btns">
                                        <label for="hipaa-input" class="upload primary-fill"> <span class="upload-txt">Upload</span> </label>
                                        <input type="file" id="hipaa-input" hidden name="hipaa_docs">
                                        <button class="primary-fill ms-4">View</button>
                                        <p id="HIPAA"></p>
                                    </div>
                                    <div class="ms-4 responsive-btns">
                                        <button class="primary-fill mt-2 mb-3" name="hipaa_docs-btn"><i class="bi bi-cloud-arrow-up"></i></button>
                                        <button class="primary-fill mb-2"><i class="bi bi-eye"></i></button>
                                        <p id="HIPAA"></p>
                                    </div>
                                </td>
                            </tr>

                            <tr class="border-bottom-table">
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="non_disclosure_doc">
                                        <span class="ms-2">
                                            Non-disclosure Agreement
                                        </span>
                                    </div>

                                </td>
                                <td>
                                    <div class="ms-4 btns">
                                        <label for="non-disclosure-input" class="upload primary-fill"> <span class="upload-txt">Upload</span> </label>
                                        <input type="file" id="non-disclosure-input" hidden name="non_disclosure_doc">
                                        <p class="non-disclosure"></p>
                                    </div>
                                    <div class="ms-4 responsive-btns">
                                        <button class="primary-fill mb-2 mt-2" name="non_disclosure_doc-btn"><i class="bi bi-cloud-arrow-up"></i></button>
                                        <p class="non-disclosure"></p>
                                    </div>
                                </td>
                            </tr>

                            <tr class="border-bottom-table">
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="license_check">
                                        <span class="ms-2">
                                            License Agreement
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="ms-4 btns">
                                        <label for="license-input" class="upload primary-fill"> <span class="upload-txt">Upload</span> </label>
                                        <input type="file" id="license-input" hidden name="license_doc">
                                        <p class="license"></p>
                                    </div>
                                    <div class="ms-4 responsive-btns">
                                        <button class="primary-fill mt-2 mb-2" name="license_doc-btn"><i class="bi bi-cloud-arrow-up"></i></button>
                                        <p class="license"></p>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <hr>

            <div class="d-flex flex-row justify-content-end gap-3">
                <button class="primary-fill" type="submit">Save</button>
                <button class="btn btn-danger" type="cancel">Cancel</button>
            </div>
    </div>
    </form>
</div>
</div>

@endsection