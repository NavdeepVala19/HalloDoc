@extends('index')
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/providerProfile.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/provider.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('provider-dashboard') }}">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="">My Schedule</a>
    <a href="" class="active-link">My Profile</a>
@endsection

@section('content')
    {{-- Request To Admin --}}
    <div class="pop-up request-to-admin">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>Request To Administrator</span>
            <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
        </div>
        <p class="m-3">Need to send message to edit</p>
        <div class="p-3 d-flex align-items-center justify-content-center ">
            <div class="form-floating">
                <textarea class="form-control request-message" placeholder="injury" id="floatingTextarea2"></textarea>
                <label for="floatingTextarea2">Message</label>
            </div>
        </div>
        <div class="p-2 d-flex align-items-center justify-content-end gap-2">
            <button class="primary-fill">Send</button>
            <button class="primary-empty hide-popup-btn">Cancel</button>
        </div>
    </div>
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">My Profile</h1>
            <a href="{{ route('provider-dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        <div class="section">
            <div class="text-end">
                <button class="primary-empty request-admin-btn">Request To Admin</button>
            </div>
            <form action="" method="POST">
                @csrf
                <h3>Account Information</h3>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="user_name" class="form-control" id="floatingInput"
                            placeholder="User Name">
                        <label for="floatingInput">First Name</label>
                        @error('first_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="password" name="password" class="form-control" id="floatingInput"
                            placeholder="password">
                        <label for="floatingInput">Password</label>
                        @error('first_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="text-end">
                    <button class="primary-empty">Reset Password</button>
                </div>
                <h3>Physician Information</h3>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="first_name" class="form-control" id="floatingInput"
                            placeholder="First Name">
                        <label for="floatingInput">First Name</label>
                        @error('first_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="last_name" class="form-control" id="floatingInput"
                            placeholder="Last Name">
                        <label for="floatingInput">Last Name</label>
                        @error('last_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Email</label>
                    </div>

                    <input type="tel" name="phone_number" class="form-control phone" id="telephone"
                        placeholder="Phone Number">
                    @error('phone_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="form-floating ">
                        <input type="text" name="medical_license" class="form-control" id="floatingInput"
                            placeholder="Medical License">
                        <label for="floatingInput">Medical license # </label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="npi_number" class="form-control" id="floatingInput"
                            placeholder="NPI Number">
                        <label for="floatingInput">NPI Number</label>
                    </div>
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
                <h3>Mailing & Billing Information</h3>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="address1" class="form-control" id="floatingInput"
                            placeholder="Address 1">
                        <label for="floatingInput">Address 1</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="address2" class="form-control" id="floatingInput"
                            placeholder="Address 2">
                        <label for="floatingInput">Address 2</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="city" class="form-control" id="floatingInput"
                            placeholder="city">
                        <label for="floatingInput">City</label>
                    </div>
                    <div>
                        {{-- Dropdown State Selection --}}
                        <div class="form-floating">
                            <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                <option selected>Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <label for="floatingSelect">State</label>
                        </div>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="zip" class="form-control" id="floatingInput" placeholder="zip">
                        <label for="floatingInput">Zip</label>
                    </div>
                    <input type="tel" name="mobile" class="form-control phone" id="telephone"
                        placeholder="mobile">
                    @error('mobile')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <h3>Provider Profile</h3>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="business_name" class="form-control" id="floatingInput"
                            placeholder="Business Name">
                        <label for="floatingInput">Business Name</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="business_website" class="form-control" id="floatingInput"
                            placeholder="Business Website">
                        <label for="floatingInput">Business Website</label>
                    </div>
                    <div>
                        {{-- Select Photo --}}
                        <div class="custom-file-input">
                            <input type="text" placeholder="Select Photo" readonly>
                            <label for="file "><i class="bi bi-cloud-arrow-up me-2 "></i> <span
                                    class="upload-txt">Upload</span> </label>
                            <input type="file" id="file" hidden>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-1 ">
                        {{-- Select Signature --}}
                        <div class="custom-file-input">
                            <input type="text" placeholder="Select Signature" readonly>
                            <label for="signature"><i class="bi bi-cloud-arrow-up me-2"></i><span
                                    class="upload-txt">Upload</span></label>
                            <input type="file" id="signature" hidden>
                        </div>
                        <button class="create-signature-btn"><i class="bi bi-pencil me-2 "></i>Create</button>
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
