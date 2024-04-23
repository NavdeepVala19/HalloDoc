@extends('patientSiteIndex')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientNewRequest.css') }}">
@endsection

@section('patientSiteContent')
    <div class="container mb-3">
        <!-- this div is for heading and back button -->
        <div class="header_part">
            <a href="{{ route('patientDashboardData') }}" type="button" class="primary-empty">
                < Back</a>
        </div>
        <div class="patient-container">
            <form action="{{ route('createdPatientRequests') }}" method="post" enctype="multipart/form-data"
                id="patientNewRequest">
                @csrf
                <div class="patient-details">
                    <!-- Symptoms Detail Field -->
                    <div class="symp-details">
                        <div class="patient-info-text">
                            <h4>Patient Information</h4>
                        </div>
                        <div class="form-floating" id="form-floating">
                            <textarea class="form-control note @error('symptoms') is-invalid @enderror" name='symptoms' placeholder="notes"
                                id="floatingTextarea2" style="height: 150px">{{ old('symptoms') }}</textarea>
                            <label for="floatingTextarea2">Enter Brief Details of Symptoms(optional)</label>
                            @error('symptoms')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!--  Patient FirstName, LastName ,DOB Field  -->
                    <div class=" grid-2">
                        <div class="form-floating" id="form-floating">
                            <input type="text" name="first_name"
                                class="form-control first_name @error('first_name') is-invalid @enderror"
                                id="floatingInput1" value="" placeholder="First Name" value="{{ old('first_name') }}">
                            <label for="floatingInput1">First Name</label>
                            @error('first_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating" id="form-floating">
                            <input type="text" name="last_name"
                                class="form-control last_name @error('last_name') is-invalid @enderror" id="floatingInput2"
                                value="" placeholder="Last Name" value="{{ old('last_name') }}">
                            <label for="floatingInput2">Last Name</label>
                            @error('last_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating" id="form-floating">
                            <input type="date"
                                class="form-control date_of_birth @error('date_of_birth') is-invalid @enderror"
                                id="floatingInput3" name="date_of_birth" placeholder="date of birth"
                                value="{{ old('date_of_birth') }}">
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
                            <div class="form-floating" id="form-floating">
                                <input type="email" class="form-control email @error('email') is-invalid @enderror"
                                    id="floatingInput4" placeholder="name@example.com" name="email"
                                    value="{{ $email }}" disabled>
                                <label for="floatingInput4">Email</label>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating" style="height: 58px;" id="form-floating">
                                <input type="tel" name="phone_number"
                                    class="form-control phone @error('phone_number') is-invalid @enderror" id="telephone"
                                    placeholder="Phone Number" value="{{ old('phone_number') }}">
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
                            <div class="form-floating" id="form-floating">
                                <input type="text" name="street"
                                    class="form-control street @error('street') is-invalid @enderror" id="floatingInput5"
                                    placeholder="Street" value="{{ old('street') }}">
                                <label for="floatingInput5">Street</label>
                                @error('street')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating" id="form-floating">
                                <input type="text" name="city"
                                    class="form-control city @error('city') is-invalid @enderror" id="floatingInput6"
                                    placeholder="City" value="{{ old('city') }}">
                                <label for="floatingInput6">City</label>
                                @error('city')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating" id="form-floating">
                                <input type="text" name="state"
                                    class="form-control state  @error('state') is-invalid @enderror" id="floatingInput7"
                                    placeholder="State" value="{{ old('state') }}">
                                <label for="floatingInput7">State</label>
                                @error('state')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating w-100" id="form-floating">
                                <input type="number" name="zipcode"
                                    class="form-control zipcode  @error('zipcode') is-invalid @enderror"
                                    id="floatingInput8" placeholder="Zipcode" value="{{ old('zipcode') }}"
                                    min="0">
                                <label for="floatingInput8">Zipcode</label>
                                @error('zipcode')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating" id="form-floating">
                                <input type="number" name="room"
                                    class="form-control room  @error('room') is-invalid @enderror" id="floatingInput9"
                                    placeholder="room" value="{{ old('room') }}" min="0">
                                <label for="floatingInput9">Room(optional)</label>
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
                        <div class="custom-file-input mb-4" id="form-floating">
                            <input type="file" name="docs" id="file-upload-request" hidden>
                            <label for="file-upload-request" class="upload-label" style="color: #3c9eff;">
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
                        <a href="{{ route('patientDashboardData') }}" type="button" class="primary-empty">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/patientSite/patientNewRequest.js') }}"></script>
    <script defer src="{{ asset('assets/patientSite/patientSite.js') }}"></script>
@endsection
