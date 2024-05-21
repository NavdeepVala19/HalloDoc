@extends('index')
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/providerProfile.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
@endsection

@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
@endsection


@section('nav-links')
    <a href="{{ route('provider.dashboard') }}">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="{{ route('provider.scheduling') }}">My Schedule</a>
    <a href="{{ route('provider.profile') }}" class="active-link">My Profile</a>
@endsection

@section('content')
    <div class="overlay"></div>
    @include('loading')

    {{-- Password Reset Successfull alert message --}}
    @if (session('success'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('success') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    {{-- Mail sent to admin for required changes --}}
    @if (session('mailSentToAdmin'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('mailSentToAdmin') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    {{-- Request To Admin --}}
    <div class="pop-up request-to-admin">
        <form action="{{ route('provider.edit.profile') }}" method="POST" id="profileEditMailForm">
            @csrf
            <input type="text" name="providerId" value="{{ $provider->id }}" hidden>
            <div class="popup-heading-section d-flex align-items-center justify-content-between">
                <span>Request To Administrator</span>
                <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
            </div>
            <p class="ms-3 mt-3">Need to send message to edit</p>
            <div class="ps-3 pe-3  d-flex align-items-center justify-content-center ">
                <div class="form-floating">
                    <textarea class="form-control request-message" name="message" placeholder="editProfile" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Message</label>
                </div>
            </div>
            <div class="p-2 d-flex align-items-center justify-content-end gap-2">
                <button type="submit" class="primary-fill" id='profileEditMailFormBtn'>Send</button>
                <button type="button" class="primary-empty hide-popup-btn">Cancel</button>
            </div>
        </form>
    </div>

    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">My Profile</h1>
            <a href="{{ route('provider.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i>
                Back</a>
        </div>

        <div class="section">
            <div class="text-end">
                <button class="primary-empty request-admin-btn">Request To Admin</button>
            </div>
            <form action="{{ route('provider.reset.password') }}" method="POST" id="providerProfileForm">
                @csrf
                <input type="text" name="providerId" value="{{ $provider->id }}" hidden />
                <h3>Account Information</h3>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="user_name" value="{{ $userData->username }}" class="form-control"
                            id="floatingInput1" placeholder="User Name" disabled>
                        <label for="floatingInput1">User Name</label>
                    </div>
                    <div class="form-floating ">
                        <input type="password" name="password" value="{{ $userData->password }}" disabled
                            class="form-control password-field @error('password')
                            is-invalid
                        @enderror"
                            id="floatingInput2" placeholder="password">
                        <label for="floatingInput2">Password</label>
                        @error('password')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="reset-password-container text-end">
                    <button type="button" class="primary-empty reset-password-btn">Reset Password</button>
                </div>
                <div class="password-btn-container text-end">
                    <button type="submit" class="primary-fill">Save</button>
                    <button type="button" class="primary-empty cancel-password-reset">Cancel</button>
                </div>
            </form>
            <h3>Physician Information</h3>
            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="first_name" value="{{ $provider->first_name }}" class="form-control"
                        id="floatingInput" placeholder="First Name" disabled>
                    <label for="floatingInput">First Name</label>
                    @error('first_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating ">
                    <input type="text" name="last_name" value="{{ $provider->last_name }}" class="form-control"
                        id="floatingInput" placeholder="Last Name" disabled>
                    <label for="floatingInput">Last Name</label>
                    @error('last_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control" value="{{ $provider->email }}" id="floatingInput"
                        placeholder="name@example.com" disabled>
                    <label for="floatingInput">Email</label>
                </div>

                <div>
                    <input type="tel" name="phone_number" value="{{ $provider->mobile }}"
                        class="form-control phone" id="telephone" disabled>
                    @error('phone_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating ">
                    <input type="text" name="medical_license" value="{{ $provider->medical_license }}"
                        class="form-control" id="floatingInput" placeholder="Medical License" disabled>
                    <label for="floatingInput">Medical license # </label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="npi_number" value="{{ $provider->npi_number }}" class="form-control"
                        id="floatingInput" placeholder="NPI Number" disabled>
                    <label for="floatingInput">NPI Number</label>
                </div>
                <span class="d-flex gap-2 flex-wrap">
                    @foreach ($regions as $region)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $region->id }}"
                                id="flexCheckDefault"
                                @foreach ($physicianRegions as $physicianRegion)
                                    {{ $physicianRegion->region_id == $region->id ? 'checked' : '' }} @endforeach
                                disabled>
                            <label class="form-check-label" for="flexCheckDefault">
                                {{ $region->region_name }}
                            </label>
                        </div>
                    @endforeach
                </span>
            </div>
            <h3>Mailing & Billing Information</h3>
            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="address1" value="{{ $provider->address1 }}" class="form-control"
                        id="floatingInput" placeholder="Address 1" disabled>
                    <label for="floatingInput">Address 1</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="address2" value="{{ $provider->address2 }}" class="form-control"
                        id="floatingInput" placeholder="Address 2" disabled>
                    <label for="floatingInput">Address 2</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="city" value="{{ $provider->city }}" class="form-control"
                        id="floatingInput" placeholder="city" disabled>
                    <label for="floatingInput">City</label>
                </div>
                <div>
                    {{-- Dropdown State Selection --}}
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example"
                            disabled>
                            {{-- <option selected>Dwarka</option> --}}
                            <option value="">{{ $provider->regions->region_name }}</option>
                        </select>
                        <label for="floatingSelect">State</label>
                    </div>
                </div>
                <div class="form-floating ">
                    <input type="text" name="zip" value="{{ $provider->zip }}" class="form-control"
                        id="floatingInput" placeholder="zip" disabled>
                    <label for="floatingInput">Zip</label>
                </div>
                <input type="tel" name="mobile" value="{{ $provider->alt_phone }}" class="form-control phone"
                    id="telephone" placeholder="mobile" disabled>
                @error('mobile')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <h3>Provider Profile</h3>
            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="business_name" value="{{ $provider->business_name }}"
                        class="form-control" id="floatingInput" placeholder="Business Name" disabled>
                    <label for="floatingInput">Business Name</label>
                </div>
                <div class="form-floating ">
                    <input type="text" name="business_website" value="{{ $provider->business_website }}"
                        class="form-control" id="floatingInput" placeholder="Business Website" disabled>
                    <label for="floatingInput">Business Website</label>
                </div>
                {{-- <div> --}}
                {{-- Select Photo --}}
                {{-- <div class="custom-file-input">
                        <input type="text" placeholder="Select Photo" readonly disabled>
                        <label for="file-input"><i class="bi bi-cloud-arrow-up me-2 "></i> <span
                                class="upload-txt">Upload</span>
                        </label>
                        <input type="file" id="file-input" hidden disabled>
                    </div>
                </div> --}}
            </div>
            </form>
            {{-- <hr> --}}
            {{-- <div>
                <div class="d-flex gap-2 align-items-center mb-3 ">
                    <span>
                        Provider Agreement
                    </span>
                    <a href="" type="button" class="primary-fill">View</a>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <span>
                        HIPPA Compliance
                    </span>
                    <a href="" type="button" class="primary-fill">View</a>
                </div>
            </div> --}}
        </div>

    </div>
@endsection

@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/validation.js') }}"></script>
@endsection
