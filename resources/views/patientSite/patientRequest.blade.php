@extends('patientSiteIndex')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientRequest.css') }}">
@endsection

@section('patientSiteContent')
<div class="container">
    <!-- this div is for heading and back button -->
    <div class="header_part">
        <a href="{{ route('submitRequest') }}" type="button" class="primary-empty">
            < Back</a>
    </div>
    <div class="patient-container">
        <form action="{{ route('patientRequests') }}" method="post" enctype="multipart/form-data" id="patientRequestForm">
            @csrf
            <div class="patient-details">
                <!-- Symptoms Detail Field -->
                <div class="symp-details">
                    <div class="patient-info-text">
                        <h4>Patient Information</h4>
                    </div>
                    <div class="form-floating">
                        <textarea class="form-control note" name='patient_note' placeholder="notes" id="floatingTextarea2" value="{{ old('patient_note') }}" style="height: 150px"></textarea>
                        <label for="floatingTextarea2">Enter Brief Details of Symptoms(optional)</label>
                    </div>
                </div>

                <!--  Patient FirstName, LastName ,DOB Field  -->
                <div class=" grid-2">
                    <div class="form-floating">
                        <input type="text" name="first_name" class="form-control first_name" id="floatingInput" value="" placeholder="First Name" value="{{ old('first_name') }}">
                        <label for="floatingInput">First Name</label>
                        @error('first_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="text" name="last_name" class="form-control last_name" id="floatingInput" value="" placeholder="Last Name" value="{{ old('last_name') }}">
                        <label for="floatingInput">Last Name</label>
                        @error('last_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating">
                        <input type="date" class="form-control date_of_birth" id="floatingInput" name="date_of_birth" placeholder="date of birth" value="{{ old('date_of_birth') }}">
                        <label for="floatingInput">Date Of Birth</label>
                        @error('date_of_birth')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!--     Patient Contact Information    -->
                <div class="patient-contact-inputs">
                    <div class="patient-contact-text">
                        <h4>Patient Contact Information</h4>
                    </div>
                    <div class="grid-2">
                        <div class="form-floating ">
                            <input type="email" class="form-control email" id="floatingInput" placeholder="name@example.com" name="email" value="{{ old('email') }}">
                            <label for="floatingInput">Email</label>
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="" style="height: 58px;">
                            <input type="tel" name="phone_number" class="form-control phone" id="telephone" placeholder="Phone Number" value="{{ old('phone_number') }}">
                            @error('phone_number')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!--   Patient Location Information   -->
                <div class="patient-location-inputs">
                    <div class="patient-location-text">
                        <h4> Patient Location</h4>
                    </div>
                    <div class="grid-2">
                        <div class="form-floating ">
                            <input type="text" name="street" class="form-control street" id="floatingInput" placeholder="Street" value="{{ old('street') }}">
                            <label for="floatingInput">Street</label>
                            @error('street')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating ">
                            <input type="text" name="city" class="form-control city" id="floatingInput" placeholder="City" value="{{ old('city') }}">
                            <label for="floatingInput">City</label>
                            @error('city')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating ">
                            <input type="text" name="state" class="form-control state" id="floatingInput" placeholder="State" value="{{ old('state') }}">
                            <label for="floatingInput">State</label>
                            @error('state')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating w-100">
                            <input type="number" name="zipcode" class="form-control zipcode" id="floatingInput" placeholder="Zipcode" value="{{ old('zipcode') }}">
                            <label for="floatingInput">Zipcode</label>
                            @error('zipcode')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating ">
                            <input type="number" name="room" class="form-control room" id="floatingInput" placeholder="room" value="{{ old('room') }}">
                            <label for="floatingInput">Room</label>
                            @error('room')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <!--  photo upload or documents -->
                <div class="docs-upload">
                    <div class="patient-doc-text">
                        <h4>Upload Photo or document (optional)</h4>
                    </div>
                    <div class="custom-file-input mb-4">
                        <input type="file" name="docs" id="file-upload-request" hidden>
                        <label for="file-upload-request" class="upload-label">
                            <div class="p-2 file-label">
                                Select File
                            </div>
                            <span class="primary-fill upload-btn">
                                <i class="bi bi-cloud-arrow-up me-2"></i>
                                <span class="upload-txt">Upload</span>
                            </span>
                        </label>
                    </div>
                </div>


                <!--  SUBMIT and CANCEL Buttons -->
                <div class="buttons">
                    <button class="primary-fill me-2" type="submit">Submit</button>
                    <a href="{{ route('submitRequest') }}" type="button" class="primary-empty">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection