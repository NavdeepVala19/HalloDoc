@extends('index')
@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/adminProvider/adminEditProvider.css') }}">

@endsection

@section('nav-links')
<a href="">Dashboard</a>
<a href="">Provider Location</a>
<a href="">My Profile</a>
<a href="" class="active-link">Providers</a>
<a href="">Partners</a>
<a href="">Access</a>
<a href="">Records</a>
@endsection

@section('content')

<div class="container form-container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="heading">Edit Physician Account</h2>
        <a href="" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
    </div>

    <div class="section">

        <form action="" method="POST">
            @csrf

            <h3>Account Information</h3>

            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="user_name" class="form-control" id="floatingInput" placeholder="User Name"  
                     value="{{ $getProviderData->users->username}}"
                        >
                    <label for="floatingInput">User Name</label>
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

                <div class="form-floating status-select">
                    <select class="form-select">
                        <option selected>Status</option>
                        <option value="1">Pending</option>
                        <option value="2">Active</option>
                        <option value="3">Not Active</option>
                    </select>
                    </input>
                </div>

                <div class="form-floating role-select">
                    <select class="form-select">
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
                    <button class="primary-fill">Edit</button>
                    <button class="primary-empty">Reset Password</button>
                </div>
            </div>



            <h3>Physician Information</h3>

            <div class="grid-2">

                <div class="form-floating ">
                    <input type="text" name="first_name" class="form-control" id="floatingInput" value="{{ $getProviderData->first_name}}"
                        placeholder="First Name">
                    <label for="floatingInput">First Name</label>
                    @error('first_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating ">
                    <input type="text" name="last_name" class="form-control" id="floatingInput" placeholder="Last Name" value="{{ $getProviderData->last_name}}">
                    <label for="floatingInput">Last Name</label>
                    @error('last_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="{{ $getProviderData->email}}" name="email">
                    <label for="floatingInput">Email</label>
                </div>

                <input type="tel" name="phone_number" class="form-control phone" id="telephone" value="{{ $getProviderData->mobile}}"
                    placeholder="Phone Number">
                @error('phone_number')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div class="form-floating "> 
                    <input type="text" name="medical_license" class="form-control" id="floatingInput" value="{{ $getProviderData->medical_license}}"
                        placeholder="Medical License">
                    <label for="floatingInput">Medical license # </label>
                </div>

                <div class="form-floating ">
                    <input type="text" name="npi_number" class="form-control" id="floatingInput" value="{{ $getProviderData->npi_number}}"
                        placeholder="NPI Number">
                    <label for="floatingInput">NPI Number</label>
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="{{ $getProviderData->syncEmailAddress}}" 
                    name="alt_email">
                    <label for="floatingInput">Email</label>
                </div>

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
                </div>

                <div>

                </div>

                <div class="d-flex flex-row justify-content-end gap-3">
                    <button class="primary-fill">Enter Payrate</button>
                    <button class="primary-fill">Edit</button>
                </div>

            </div>
            <h3>Mailing & Billing Information</h3>
            <div class="grid-2">

                <div class="form-floating ">
                    <input type="text" name="address1" class="form-control" id="floatingInput" placeholder="Address 1" value="{{ $getProviderData->address1}}">
                    <label for="floatingInput">Address 1</label>
                </div>

                <div class="form-floating ">
                    <input type="text" name="address2" class="form-control" id="floatingInput" placeholder="Address 2" value="{{ $getProviderData->address2}}">
                    <label for="floatingInput">Address 2</label>
                </div>

                <div class="form-floating ">
                    <input type="text" name="city" class="form-control" id="floatingInput" placeholder="city" value="{{ $getProviderData->city}}">
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
                    <input type="text" name="zip" class="form-control" id="floatingInput" placeholder="zip" value="{{ $getProviderData->zip}}">
                    <label for="floatingInput">Zip</label>
                </div>

                <input type="tel" name="alt_phone_number" class="form-control phone" id="telephone" value="{{ $getProviderData->alt_phone}}"
                    placeholder="Phone Number">
                @error('phone_number')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <div>

                </div>

                <div class="d-flex flex-row justify-content-end">
                    <button class="primary-fill">Edit</button>
                </div>


            </div>
            <h3>Provider Profile</h3>
            <div class="grid-2">

                <div class="form-floating ">
                    <input type="text" name="business_name" class="form-control" id="floatingInput" value="{{ $getProviderData->business_name}}"
                        placeholder="Business Name">
                    <label for="floatingInput">Business Name</label>
                </div>

                <div class="form-floating ">
                    <input type="text" name="business_website" class="form-control" id="floatingInput" value="{{ $getProviderData->business_website}}"
                        placeholder="Business Website">
                    <label for="floatingInput">Business Website</label>
                </div>

                <div>
                    {{-- Select Photo --}}
                    <div class="custom-file-input">
                        <input type="text" placeholder="Select Photo" readonly>
                        <label for="file-input"><i class="bi bi-cloud-arrow-up me-2 "></i> <span
                                class="upload-txt">Upload</span> </label>
                        <input type="file" id="file-input" hidden>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-1 ">
                    {{-- Select Signature --}}
                    <div class="custom-file-input">
                        <input type="text" placeholder="Select Signature" readonly>
                        <label for="signature-input"><i class="bi bi-cloud-arrow-up me-2"></i><span
                                class="upload-txt">Upload</span></label>
                        <input type="file" id="signature-input" hidden>
                    </div>

                    <button class="create-signature-btn"><i class="bi bi-pencil me-2 "></i>Create</button>
                    {{-- <canvas id="signatureCanvas" width="300" height="150"></canvas> --}}
                </div>


            </div>
            <div class="form-floating">
                <textarea class="form-control" placeholder="Admin Notes" id="floatingTextarea2" value="{{ $getProviderData->admin_notes}}"`
                    style="height: 120px"></textarea>
                <label for="floatingTextarea2">Admin Notes</label>
            </div>

            <div class="d-flex flex-row justify-content-end mt-4">
                <button class="primary-fill">Edit</button>
            </div>

        </form>
        <hr>
        <div>
            <h3>Onboarding</h3>

            <div class="table mt-4">
                <table>
                    <tbody>
                        <tr class="border-bottom-table">
                            <td>
                                <div class="d-flex gap-2 align-items-center">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <span class="ms-2">
                                        Independent Contractor Agreement
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="ms-4 btns">
                                    <button class="primary-fill">Upload</button>
                                    <button class="primary-fill ms-4">View</button>
                                </div>
                                <div class="ms-4 responsive-btns">
                                    <button class="primary-fill mb-3"><i class="bi bi-cloud-arrow-up"></i></button>
                                    <button class="primary-fill mb-2"><i class="bi bi-eye"></i></button>
                                </div>
                            </td>
                        </tr>

                        <tr class="border-bottom-table">
                            <td>
                                <div class="d-flex gap-2 align-items-center">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <span class="ms-2">
                                        Background Check
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="ms-4 btns">
                                    <button class="primary-fill ">Upload</button>
                                    <button class="primary-fill ms-4">View</button>
                                </div>
                                <div class="ms-4 responsive-btns">
                                    <button class="primary-fill mt-2 mb-3"><i class="bi bi-cloud-arrow-up"></i></button>
                                    <button class="primary-fill mb-2"><i class="bi bi-eye"></i></button>
                                </div>
                            </td>
                        </tr>

                        <tr class="border-bottom-table">
                            <td>
                                <div class="d-flex gap-2 align-items-center">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <span class="ms-2">
                                        HIPAA Compliance
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="ms-4 btns">
                                    <button class="primary-fill">Upload</button>
                                    <button class="primary-fill ms-4">View</button>
                                </div>
                                <div class="ms-4 responsive-btns">
                                    <button class="primary-fill mt-2 mb-3"><i class="bi bi-cloud-arrow-up"></i></button>
                                    <button class="primary-fill mb-2"><i class="bi bi-eye"></i></button>
                                </div>
                            </td>
                        </tr>

                        <tr class="border-bottom-table">
                            <td>
                                <div class="d-flex gap-2 align-items-center">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <span class="ms-2">
                                        Non-disclosure Agreement
                                    </span>
                                </div>

                            </td>
                            <td>
                                <div class="ms-4 btns">
                                    <button class="primary-fill ">Upload</button>
                                </div>
                                <div class="ms-4 responsive-btns">
                                    <button class="primary-fill mb-2 mt-2"><i class="bi bi-cloud-arrow-up"></i></button>
                                </div>
                            </td>
                        </tr>

                        <tr class="border-bottom-table">
                            <td>
                                <div class="d-flex gap-2 align-items-center">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <span class="ms-2">
                                        License Agreement
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="ms-4 btns">
                                    <button class="primary-fill ">Upload</button>
                                </div>
                                <div class="ms-4 responsive-btns">
                                    <button class="primary-fill mt-2 mb-2"><i class="bi bi-cloud-arrow-up"></i></button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <hr>

        <div class="d-flex flex-row justify-content-end gap-3">
            <button class="primary-fill">Save</button>
            <button class="btn btn-danger">Delete Account</button>
        </div>
    </div>

</div>
</div>

@endsection