@extends('index')
@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/adminProvider/adminEditProvider.css') }}">
@endsection
@section('username')
{{ !empty(Auth::user()) ? Auth::user()->username : '' }}
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
        <h2 class="heading">Edit Physician Account</h2>
        <a href="{{ route('adminProvidersInfo') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
    </div>

    <div class="section">

        @if (Session::has('message'))
        <div class="alert alert-success popup-message" role="alert">
            {{ Session::get('message') }}
        </div>
        @endif

        <div class="section">

            <form action="{{ route('updateProviderAccountInfo', $getProviderData->id) }}" method="POST" id="adminEditProviderForm1">
                @csrf

                <h3>Account Information</h3>

                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="user_name" class="form-control provider-username-field @error('user_name') is-invalid @enderror" id="floatingInput" placeholder="User Name" disabled value="{{ $getProviderData->users->username }}">
                        <label for="floatingInput">User Name</label>
                        @error('user_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="password" name="password" class="form-control provider-password-field @error('password') is-invalid @enderror" id="floatingInput" placeholder="password" disabled>
                        <label for="floatingInput">Password</label>
                        @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating status-select">
                        <select class="form-select @error('status_type') is-invalid @enderror" id="provider-status" disabled name="status_type" value="{{ $getProviderData->status }}">
                            <option selected>Status</option>
                            <option value="pending" {{ $getProviderData->status == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="active" {{ $getProviderData->status == 'active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="inactive" {{ $getProviderData->status == 'inactive' ? 'selected' : '' }}>Not Active</option>
                        </select>
                        @error('status_type')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-floating role-select">
                        <!-- <select class="form-select" id="provider-role" disabled name="role">
                                <option selected>{{$getProviderData->role->name}}</option>
                            </select> -->

                        <select class="form-select @error('role') is-invalid @enderror" id="provider-role" name="role" disabled>
                            <option selected value="{{$getProviderData->role->id}}">{{$getProviderData->role->name}}</option>
                        </select>
                        @error('role')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                    </div>

                    <div class="d-flex flex-row justify-content-end gap-3">
                        <button type="button" class="primary-fill" id="provider-credentials-edit-btn">Edit</button>

                        <button class="primary-empty" type="button" id="provider-reset-password-btn">Reset
                            Password</button>

                        <button class="primary-fill" type="submit" id="providerAccSaveBtn">Save</button>
                        <a href="" class="btn btn-danger" id="providerAccCancelBtn" type="button">Cancel</a>

                    </div>
                </div>
            </form>


            <h3>Physician Information</h3>

            <form action="{{ route('providerInfoUpdate', $getProviderData->id) }}" method="post" id="adminEditProviderForm2">
                @csrf
                <div class="grid-2">

                    <div class="form-floating ">
                        <input type="text" name="first_name" class="form-control provider-firstname @error('first_name') is-invalid @enderror" id="floatingInput" value="{{ $getProviderData->first_name }}" placeholder="First Name" disabled>
                        <label for="floatingInput">First Name</label>
                        @error('first_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <span id="errorMsg"></span>
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="last_name" class="form-control provider-lastname @error('last_name') is-invalid @enderror" id="floatingInput" placeholder="Last Name" value="{{ $getProviderData->last_name }}" disabled>
                        <label for="floatingInput">Last Name</label>
                        @error('last_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <span id="errorMsg"></span>
                    </div>

                    <div class="form-floating ">
                        <input type="email" class="form-control provider-email @error('email') is-invalid @enderror" id="floatingInput" placeholder="name@example.com" value="{{ $getProviderData->email }}" name="email" disabled>
                        <label for="floatingInput">Email</label>
                        @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <span id="errorMsg"></span>
                    </div>

                    <input type="tel" name="phone_number" class="form-control phone @error('phone_number') is-invalid @enderror" id="telephone" value="{{ $getProviderData->mobile }}" placeholder="Phone Number" disabled>
                    @error('phone_number')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="form-floating ">
                        <input type="text" name="medical_license" class="form-control provider-license @error('medical_license') is-invalid @enderror" id="floatingInput" value="{{ $getProviderData->medical_license }}" placeholder="Medical License" disabled>
                        <label for="floatingInput">Medical license # </label>
                        @error('medical_license')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="npi_number" class="form-control provider-npi @error('npi_number') is-invalid @enderror" id="floatingInput" value="{{ $getProviderData->npi_number }}" placeholder="NPI Number" disabled>
                        <label for="floatingInput">NPI Number</label>
                        @error('npi_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div></div>

                    <div class="d-flex flex-row justify-content-end gap-2">
                        <button class="primary-fill" type="button" id="provider-info-btn" type="button">Edit</button>

                        <button class="primary-fill" type="submit" id="providerInfoSaveBtn">Save</button>
                        <a href="" class="btn btn-danger" id="providerInfoCancelBtn" type="button">Cancel</a>
                    </div>

                </div>

            </form>


            <h3>Mailing & Billing Information</h3>

            <form action="{{ route('providerMailInfoUpdate', $getProviderData->id) }}" method="post" id="adminEditProviderForm3">
                @csrf
                <div class="grid-2">

                    <div class="form-floating ">
                        <input type="text" name="address1" class="form-control provider-bill-add1 @error('address1') is-invalid @enderror" id="floatingInput" placeholder="Address 1" value="{{ $getProviderData->address1 }}" disabled>
                        <label for="floatingInput">Address 1</label>
                        @error('address1')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="address2" class="form-control provider-bill-add2 @error('address2') is-invalid @enderror" id="floatingInput" placeholder="Address 2" value="{{ $getProviderData->address2 }}" disabled>
                        <label for="floatingInput">Address 2</label>
                        @error('address2')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="city" class="form-control provider-bill-city @error('city') is-invalid @enderror" id="floatingInput" placeholder="city" value="{{ $getProviderData->city }}" disabled>
                        <label for="floatingInput">City</label>
                        @error('city')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        {{-- Dropdown State Selection --}}
                        <div class="form-floating">
                            <select class="form-select listing-region @error('regions') is-invalid @enderror" id="floatingSelect" aria-label="Floating label select example" disabled name="regions">
                                <option name="regions" selected value="{{$getProviderData->Regions->id}}">{{$getProviderData->Regions->region_name}}</option>
                            </select>
                            @error('regions')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="zip" class="form-control provider-bill-zip @error('zip') is-invalid @enderror" id="floatingInput" placeholder="zip" value="{{ $getProviderData->zip }}" disabled>
                        <label for="floatingInput">Zip</label>
                        @error('user_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="tel" name="alt_phone_number" class="form-control phone alt-phone-provider @error('alt_phone_number') is-invalid @enderror" id="telephone" value="{{ $getProviderData->alt_phone }}" placeholder="Phone Number" disabled>
                    @error('alt_phone_number')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div></div>
                    <div></div>

                    <div class="d-flex flex-row justify-content-end gap-2">
                        <button type="button" class="primary-fill" type="button" id="provider-bill-edit-btn">Edit</button>

                        <button class="primary-fill" type="submit" id="providerMailSaveBtn">Save</button>
                        <a href="" class="btn btn-danger" id="providerMailCancelBtn" type="button">Cancel</a>

                    </div>

                    <input type="file" id="independent_contractor" class="independent-contractor-input" name="independent_contractor" hidden>

                </div>

            </form>

            <h3>Provider Profile</h3>

            <form action="{{ route('providerProfileUpdate', $getProviderData->id) }}" method="post" enctype="multipart/form-data" id="adminEditProviderForm4">
                @csrf
                <div class="grid-2">
                    <div class="form-floating">
                        <input type="text" name="business_name" class="form-control business-name @error('business_name') is-invalid @enderror" id="floatingInput" disabled value="{{ $getProviderData->business_name }}" placeholder="Business Name">
                        <label for="floatingInput">Business Name</label>
                        @error('business_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating">
                        <input type="text" name="business_website" class="form-control business-web @error('business_website') is-invalid @enderror" id="floatingInput" disabled value="{{ $getProviderData->business_website }}" placeholder="Business Website">
                        <label for="floatingInput">Business Website</label>
                        @error('business_website')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="custom-file-input mb-4">
                    <input type="file" name="provider_photo" id="file-upload-request" hidden class="@error('provider_photo') is-invalid @enderror">
                    <label for="file-upload-request" class="upload-label">
                        <div class="p-2 file-label">
                            Select File
                        </div>
                        <span class="primary-fill upload-btn">
                            <i class="bi bi-cloud-arrow-up me-2"></i>
                            <span class="upload-txt">Upload</span>
                        </span>
                        @error('provider_photo')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </label>
                </div>
                <div class="form-floating">
                    <textarea class="form-control admin-notes @error('admin_notes') is-invalid @enderror" placeholder="Admin_Notes" id="floatingTextarea2" disabled name="admin_notes" style="height: 120px"> {{ $getProviderData->admin_notes }} </textarea>
                    <label for="floatingTextarea2">Admin Notes</label>
                    @error('admin_notes')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex flex-row justify-content-end mt-3 gap-2">
                    <button type="button" class="primary-fill" type="button" id="provider-profile-edit-btn">Edit</button>
                    <button class="primary-fill" type="submit" id="providerProfileSaveBtn">Save</button>
                    <a href="" class="btn btn-danger" id="providerProfileCancelBtn" type="button">Cancel</a>
                </div>

            </form>

            <hr>
            <div>
                <h3>Onboarding</h3>

                <form action="{{ route('providerDocumentsUpdate', $getProviderData->id) }}" method="post" enctype="multipart/form-data" id="adminEditProviderForm5">
                    @csrf
                    <div class="table mt-4">
                        <table>
                            <tbody>
                                <tr class="border-bottom-table">
                                    <td>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input class="form-check-input @error('independent_contract_check') is-invalid @enderror" type="checkbox" id="flexCheckDefault" @checked($getProviderData->IsAgreementDoc === 1) name="independent_contract_check"
                                            value="1">
                                            <span class="ms-2">
                                                Independent Contractor Agreement
                                            </span>
                                        </div>

                                    </td>
                                    <td>
                                        <div class="ms-4 btns">
                                            <label for="independent_contractor" class="upload primary-fill"> <span class="upload-txt">Upload</span> <span class="mobile-icons"> <i class="bi bi-cloud-arrow-up"></i></span> </label>
                                            <input type="file" name="independent_contractor" id="independent_contractor" hidden class="@error('independent_contractor') is-invalid @enderror">
                                            <a href="{{ asset('storage/provider/' . $getProviderData->id . '_ICA.pdf') }}" class="primary-fill ms-4" type="button" download>View</a>
                                            <p id="Contractor"></p>
                                            @error('independent_contractor')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </td>
                                </tr>

                                <tr class="border-bottom-table">
                                    <td>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input class="form-check-input @error('background_check') is-invalid @enderror" type="checkbox" value="1" id="flexCheckDefault" name="background_check" @checked($getProviderData->IsBackgroundDoc === 1)>
                                            <span class="ms-2">
                                                Background Check
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="ms-4 btns">
                                            <label for="background-input" class="upload primary-fill"> <span class="upload-txt">Upload</span> <span class="mobile-icons"> <i class="bi bi-cloud-arrow-up"></i></span> </label>
                                            <input type="file" id="background-input" name="background_doc" hidden class="@error('background_doc') is-invalid @enderror">
                                            <a href="{{ asset('storage/provider/' . $getProviderData->id . '_BC.pdf') }}" class="primary-fill ms-4" type="button" download>View</a>
                                            <p id="Background"></p>
                                            @error('background_doc')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </td>
                                </tr>

                                <tr class="border-bottom-table">
                                    <td>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input class="form-check-input @error('hipaa_docs') is-invalid @enderror" type="checkbox" name="hipaa_docs" value="1" id="flexCheckDefault" name="HIPAA_check" @checked($getProviderData->IsTrainingDoc === 1)>
                                            <span class="ms-2">
                                                HIPAA Compliance
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="ms-4 btns">
                                            <label for="hipaa-input" class="upload primary-fill"> <span class="upload-txt">Upload</span> <span class="mobile-icons"> <i class="bi bi-cloud-arrow-up"></i></span> </label>
                                            <input type="file" id="hipaa-input" hidden name="hipaa_docs" class="@error('hipaa_docs') is-invalid @enderror">
                                            <a href="{{ asset('storage/provider/' . $getProviderData->id . '_HCA.pdf') }}" class="primary-fill ms-4" type="button" download>View</a>
                                            <p id="HIPAA"></p>
                                            @error('hipaa_docs')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </td>
                                </tr>

                                <tr class="border-bottom-table">
                                    <td>
                                        <div class="d-flex gap-2 align-items-center">
                                            <input class="form-check-input @error('non_disclosure_doc') is-invalid @enderror" type="checkbox" value="1" id="flexCheckDefault" name="non_disclosure_doc" @checked($getProviderData->IsNonDisclosureDoc === 1)>
                                            <span class="ms-2">
                                                Non-disclosure Agreement
                                            </span>
                                        </div>

                                    </td>
                                    <td>
                                        <div class="ms-4 btns">
                                            <label for="non-disclosure-input" class="upload primary-fill"> <span class="upload-txt">Upload</span> <span class="mobile-icons"> <i class="bi bi-cloud-arrow-up"></i></span> </label>
                                            <input type="file" id="non-disclosure-input" hidden name="non_disclosure_doc" class="@error('non_disclosure_doc') is-invalid @enderror">
                                            <p class="non-disclosure"></p>
                                            @error('non_disclosure_doc')
                                            <div class="alert alert-danger">{{ $message }}</div>
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
                <button class="primary-fill" type="submit">Save</button>
                <a href="{{ route('deleteProviderAccount', $getProviderData->id) }}" class="btn btn-danger">Delete
                    Account</a>
            </div>
            </form>
        </div>


    </div>
</div>
@endsection



@section('script')
<script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
<script defer src="{{ URL::asset('assets/adminProvider/adminEditProvider.js') }}"></script>
@endsection