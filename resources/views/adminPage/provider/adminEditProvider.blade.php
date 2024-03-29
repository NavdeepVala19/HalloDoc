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
        <h2 class="heading">Edit Physician Account</h2>
        <a href="{{route('adminProvidersInfo')}}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
    </div>

    <div class="section">

        <form action="{{route('adminUpdatedProvider', $getProviderData->id)}}" method="POST">
            @csrf

            <h3>Account Information</h3>

            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="user_name" class="form-control provider-username-field" id="floatingInput" placeholder="User Name" disabled value="{{ $getProviderData->users->username}}">
                    <label for="floatingInput">User Name</label>
                    @error('first_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating ">
                    <input type="password" name="password" class="form-control provider-password-field" id="floatingInput" value="{{$getProviderData->users->password}}" placeholder="password" disabled>
                    <label for="floatingInput">Password</label>
                    @error('first_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating status-select">
                    <select class="form-select" id="provider-status" disabled name="status_type" value="{{$getProviderData->status}}">
                        <option selected>Status</option>
                        <option value="pending" {{$getProviderData->status == 'pending'? "selected" :""}}>Pending</option>
                        <option value="active" {{$getProviderData->status == 'active'?  "selected":""}}>Active</option>
                        <option value="inactive" {{$getProviderData->status == 'inactive'? "selected" :""}}>Not Active</option>
                    </select>
                    </input>
                </div>


                <div class="form-floating role-select">
                    <select class="form-select" id="provider-role" disabled name="role">
                        <option selected>Role</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                    </input>
                </div>

                <div>
                </div>

                <div class="d-flex flex-row justify-content-end gap-3">
                    <button type="button" class="primary-fill" id="provider-credentials-edit-btn">Edit</button>
                    <button class="primary-empty" type="button" id="provider-reset-password-btn">Reset Password</button>
                </div>
            </div>



            <h3>Physician Information</h3>

            <div class="grid-2">

                <div class="form-floating ">
                    <input type="text" name="first_name" class="form-control provider-firstname" id="floatingInput" value="{{ $getProviderData->first_name}}" placeholder="First Name" disabled>
                    <label for="floatingInput">First Name</label>
                    @error('first_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating ">
                    <input type="text" name="last_name" class="form-control provider-lastname" id="floatingInput" placeholder="Last Name" value="{{ $getProviderData->last_name}}" disabled>
                    <label for="floatingInput">Last Name</label>
                    @error('last_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control provider-email" id="floatingInput" placeholder="name@example.com" value="{{ $getProviderData->email}}" name="email" disabled>
                    <label for="floatingInput">Email</label>
                </div>

                <input type="tel" name="phone_number" class="form-control phone" id="telephone" value="{{ $getProviderData->mobile}}" placeholder="Phone Number" disabled>
                @error('phone_number')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="form-floating ">
                    <input type="text" name="medical_license" class="form-control provider-license" id="floatingInput" value="{{ $getProviderData->medical_license}}" placeholder="Medical License" disabled>
                    <label for="floatingInput">Medical license # </label>
                </div>

                <div class="form-floating ">
                    <input type="text" name="npi_number" class="form-control provider-npi" id="floatingInput" value="{{ $getProviderData->npi_number}}" placeholder="NPI Number" disabled>
                    <label for="floatingInput">NPI Number</label>
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control provider-alt-email" id="floatingInput" placeholder="name@example.com" value="{{ $getProviderData->syncEmailAddress}}" name="alt_email" disabled>
                    <label for="floatingInput">Email</label>
                </div>

                <div class="d-flex gap-4 ">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault-somnath">
                        <label class="form-check-label" for="flexCheckDefault">
                            Somanth
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault-dwarka">
                        <label class="form-check-label" for="flexCheckDefault">
                            Dwarka
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault-rajkot">
                        <label class="form-check-label" for="flexCheckDefault">
                            Rajkot
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault-bhavnagar">
                        <label class="form-check-label" for="flexCheckDefault">
                            Bhavnagar
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault-ahmedabad">
                        <label class="form-check-label" for="flexCheckDefault">
                            Ahmedabad
                        </label>
                    </div>
                </div>

                <div>

                </div>

                <div class="d-flex flex-row justify-content-end">
                    <button class="primary-fill" type="button" id="provider-info-btn">Edit</button>
                </div>

            </div>
            <h3>Mailing & Billing Information</h3>
            <div class="grid-2">

                <div class="form-floating ">
                    <input type="text" name="address1" class="form-control provider-bill-add1" id="floatingInput" placeholder="Address 1" value="{{ $getProviderData->address1}}" disabled>
                    <label for="floatingInput">Address 1</label>
                </div>

                <div class="form-floating ">
                    <input type="text" name="address2" class="form-control provider-bill-add2" id="floatingInput" placeholder="Address 2" value="{{ $getProviderData->address2}}" disabled>
                    <label for="floatingInput">Address 2</label>
                </div>

                <div class="form-floating ">
                    <input type="text" name="city" class="form-control provider-bill-city" id="floatingInput" placeholder="city" value="{{ $getProviderData->city}}" disabled>
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
                    <input type="text" name="zip" class="form-control provider-bill-zip" id="floatingInput" placeholder="zip" value="{{ $getProviderData->zip}}" disabled>
                    <label for="floatingInput">Zip</label>
                </div>

                <input type="tel" name="alt_phone_number" class="form-control phone alt-phone-provider" id="telephone" value="{{ $getProviderData->alt_phone}}" placeholder="Phone Number" disabled>
                @error('phone_number')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div>

                </div>

                <div class="d-flex flex-row justify-content-end">
                    <button class="primary-fill" type="button" id="provider-bill-edit-btn">Edit</button>
                </div>


            </div>
            <h3>Provider Profile</h3>
            <div class="grid-2">

                <div class="form-floating ">
                    <input type="text" name="business_name" class="form-control" id="floatingInput" value="{{ $getProviderData->business_name}}" placeholder="Business Name">
                    <label for="floatingInput">Business Name</label>
                </div>

                <div class="form-floating ">
                    <input type="text" name="business_website" class="form-control" id="floatingInput" value="{{ $getProviderData->business_website}}" placeholder="Business Website">
                    <label for="floatingInput">Business Website</label>
                </div>

                <div>
                    {{-- Select Photo --}}
                    <div class="custom-file-input">
                        <input type="text" placeholder="Select Photo" readonly>
                        <label for="file-input"><i class="bi bi-cloud-arrow-up me-2 "></i> <span class="upload-txt">Upload</span> </label>
                        <input type="file" id="file-input" hidden>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-1 ">
                    {{-- Select Signature --}}
                    <div class="custom-file-input">
                        <input type="text" placeholder="Select Signature" readonly>
                        <label for="signature-input"><i class="bi bi-cloud-arrow-up me-2"></i><span class="upload-txt">Upload</span></label>
                        <input type="file" id="signature-input" hidden>
                    </div>

                    <button class="create-signature-btn"><i class="bi bi-pencil me-2 "></i>Create</button>
                    {{-- <canvas id="signatureCanvas" width="300" height="150"></canvas> --}}
                </div>


            </div>


            <div class="form-floating">
                <textarea class="form-control" placeholder="Admin_Notes" id="floatingTextarea2" name="admin_notes" style="height: 120px"> {{$getProviderData->admin_notes}} </textarea>
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
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="independent_contract_check" {{$getProviderData->IsAgreementDoc ? 'checked' : 'unchecked'}}>
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
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="background_check" {{$getProviderData->IsBackgroundDoc ? 'checked' : 'unchecked'}}>
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
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="HIPAA_check" {{$getProviderData->IsTrainingDoc ? 'checked' : 'unchecked'}}>
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
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="non_disclosure_doc" {{$getProviderData->IsNonDisclosureDoc ? 'checked' : 'unchecked'}}>
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
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="license_check" {{$getProviderData->IsLicenseDoc ? 'checked' : 'unchecked'}}>
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
                <a href="{{route('deleteProviderAccount', $getProviderData->id)}}" class="btn btn-danger">Delete Account</a>
            </div>
    </div>
    </form>

</div>
</div>

@endsection



@section('script')
<script defer src="{{ URL::asset('assets/adminProvider/adminEditProvider.js') }}"></script>
@endsection