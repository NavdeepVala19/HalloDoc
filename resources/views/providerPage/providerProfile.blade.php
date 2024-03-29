@extends('index')
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/providerProfile.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('provider.dashboard') }}">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="">My Schedule</a>
    <a href="" class="active-link">My Profile</a>
@endsection

@section('content')
    <div class="overlay"></div>

    @if (session('success'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('success') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif

    {{-- Request To Admin --}}
    <div class="pop-up request-to-admin">
        <form action="{{ route('provider.edit.profile') }}" method="POST">
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
                <button type="submit" class="primary-fill">Send</button>
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
            <form action="{{ route('provider.reset.password') }}" method="POST">
                @csrf
                <input type="text" name="providerId" value="{{ $provider->id }}" hidden />
                <h3>Account Information</h3>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="user_name" value="{{ $userData->username }}" class="form-control"
                            id="floatingInput" placeholder="User Name" disabled>
                        <label for="floatingInput">User Name</label>
                    </div>
                    <div class="form-floating ">
                        <input type="password" name="password" value="{{ $userData->password }}"
                            class="form-control @error('password')
                            is-invalid
                        @enderror"
                            id="floatingInput" placeholder="password">
                        <label for="floatingInput">Password</label>
                        @error('password')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="primary-empty">Reset Password</button>
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

                <input type="tel" name="phone_number" value="{{ $provider->mobile }}" class="form-control phone"
                    id="telephone" placeholder="Phone Number" disabled>
                @error('phone_number')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

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
                <div class="d-flex gap-4">
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
                </div>
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
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
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
                <div>
                    {{-- Select Photo --}}
                    <div class="custom-file-input">
                        <input type="text" placeholder="Select Photo" readonly disabled>
                        <label for="file-input"><i class="bi bi-cloud-arrow-up me-2 "></i> <span
                                class="upload-txt">Upload</span>
                        </label>
                        <input type="file" id="file-input" hidden>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-1 ">
                    {{-- Select Signature --}}
                    <div class="custom-file-input">
                        <input type="text" placeholder="Select Signature" readonly disabled>
                        <label for="signature-input"><i class="bi bi-cloud-arrow-up me-2"></i><span
                                class="upload-txt">Upload</span></label>
                        <input type="file" id="signature-input" hidden>
                    </div>
                    <button class="create-signature-btn"><i class="bi bi-pencil me-2 "></i>Create</button>
                    {{-- <canvas id="signatureCanvas" width="300" height="150"></canvas> --}}
                </div>
            </div>
            </form>
            <hr>
            <div>
                <div class="d-flex gap-2 align-items-center mb-3 ">
                    <span>
                        Provider Agreement
                    </span>
                    <button class="primary-fill">View</button>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <span>
                        HIPPA Compliance
                    </span>
                    <button class="primary-fill">View</button>
                </div>
            </div>
        </div>

    </div>
@endsection
