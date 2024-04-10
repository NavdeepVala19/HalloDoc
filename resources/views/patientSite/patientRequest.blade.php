@extends('patientRequests')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientRequest.css') }}">
@endsection

@section('patientRequests')
<div class="container mb-3">
    <!-- this div is for heading and back button -->
    <div class="header_part">
        <a href="{{ route('submitRequest') }}" type="button" class="primary-empty">
            < Back</a>
    </div>

    <div class="loader">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
            <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9" />
            <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z" />
        </svg>
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
                        <textarea class="form-control note" name='symptoms' placeholder="notes" id="floatingTextarea2" value="{{ old('symptoms') }}" style="height: 150px"></textarea>
                        <label for="floatingTextarea2">Enter Brief Details of Symptoms(optional)</label>
                    </div>
                </div>

                <!--  Patient FirstName, LastName ,DOB Field  -->
                <div class=" grid-2">
                    <div class="form-floating">
                        <input type="text" name="first_name" class="form-control first_name @error('first_name') is-invalid @enderror" id="floatingInput1" placeholder="First Name" value="{{ old('first_name') }}">
                        <label for="floatingInput1">First Name</label>
                        @error('first_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="text" name="last_name" class="form-control last_name @error('last_name') is-invalid @enderror" id="floatingInput2" placeholder="Last Name" value="{{ old('last_name') }}">
                        <label for="floatingInput2">Last Name</label>
                        @error('last_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating">
                        <input type="date" class="form-control date_of_birth @error('date_of_birth') is-invalid @enderror" id="floatingInput3" name="date_of_birth" placeholder="date of birth" value="{{ old('date_of_birth') }}">
                        <label for="floatingInput3">Date Of Birth</label>
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
                            <input type="email" class="form-control email @error('email') is-invalid @enderror" id="floatingInput4" placeholder="name@example.com" name="email" value="{{ old('email') }}">
                            <label for="floatingInput4">Email</label>
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="" style="height: 58px;">
                            <input type="tel" name="phone_number" class="form-control phone @error('phone_number') is-invalid @enderror" id="telephone" placeholder="Phone Number" value="{{ old('phone_number') }}">
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
                            <input type="text" name="street" class="form-control street @error('street') is-invalid @enderror" id="floatingInput5" placeholder="Street" value="{{ old('street') }}">
                            <label for="floatingInput5">Street</label>
                            @error('street')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating ">
                            <input type="text" name="city" class="form-control city @error('city') is-invalid @enderror" id="floatingInput6" placeholder="City" value="{{ old('city') }}">
                            <label for="floatingInput6">City</label>
                            @error('city')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating ">
                            <input type="text" name="state" class="form-control state @error('state') is-invalid @enderror" id="floatingInput7" placeholder="State" value="{{ old('state') }}">
                            <label for="floatingInput7">State</label>
                            @error('state')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating w-100">
                            <input type="number" name="zipcode" class="form-control zipcode @error('zipcode') is-invalid @enderror" id="floatingInput8" placeholder="Zipcode" value="{{ old('zipcode') }}">
                            <label for="floatingInput8">Zipcode</label>
                            @error('zipcode')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating ">
                            <input type="number" name="room" class="form-control room @error('room') is-invalid @enderror" id="floatingInput9" placeholder="room" value="{{ old('room') }}">
                            <label for="floatingInput9">Room (Optional) </label>
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
                        @error('docs')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <!--  SUBMIT and CANCEL Buttons -->
                <div class="buttons">
                    <button class="primary-fill me-2" type="submit"> <i class="loading-spinner fa fa-lg fas fas-spinner fa-spin"></i> Submit</button>
                    <a href="{{ route('submitRequest') }}" type="button" class="primary-empty">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
<script defer src="{{ URL::asset('assets/patientSite/patientRequestFormValidation.js') }}"></script>
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection