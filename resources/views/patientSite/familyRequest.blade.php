@extends('patientRequests')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/patientSite/familyRequest.css') }}">
@endsection

@section('patientRequests')
    @include('loading')
    <div class="container mb-3">
        <!-- this div is for back button -->
        <div class="header_part">
            <a href="{{ route('submitRequest') }}" type="button" class="primary-empty" id="back-btn">
                < Back</a>
        </div>
        <div class="patient-container">
            <form action="{{ route('familyRequests') }}" method="post" enctype="multipart/form-data"
                id="patientRequestForm">
                @csrf
                <!-- Family/Friend Information -->
                <div class="family-inputs">
                    <div class="family-text">
                        <h4> Family/Friend Information</h4>
                    </div>
                    <div class=" grid-2">
                        <div class="form-floating" id="form-floating">
                            <input type="text" class="form-control @error('family_first_name') is-invalid @enderror"
                                id="floatingInput1" value="{{ old('family_first_name') }}" name="family_first_name"
                                placeholder="Your First Name">
                            <label for="floatingInput1">Your First Name</label>
                            @error('family_first_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating" id="form-floating">
                            <input type="text" class="form-control @error('family_last_name') is-invalid @enderror"
                                id="floatingInput2" value="{{ old('family_last_name') }}" name="family_last_name"
                                placeholder="Your Last Name">
                            <label for="floatingInput2">Your Last Name</label>
                            @error('family_last_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating" style="height: 58px;" id="form-floating">
                            <input type="tel" name="family_phone_number"
                                class="form-control phone @error('family_phone_number') is-invalid @enderror" id="telephone"
                                value="{{ old('family_phone_number') }}">
                            @error('family_phone_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating" id="form-floating">
                            <input type="email" class="form-control email @error('family_email') is-invalid @enderror"
                                id="floatingInput3" placeholder="name@example.com" name="family_email"
                                value="{{ old('family_email') }}">
                            <label for="floatingInput3">Email</label>
                            @error('family_email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating" id="form-floating">
                            <input type="text" class="form-control @error('family_relation') is-invalid @enderror"
                                id="floatingInput4" value="{{ old('family_relation') }}" name="family_relation"
                                placeholder="Relation with Patient">
                            <label for="floatingInput4">Relation with Patient</label>
                            @error('family_relation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="patient-details">
                    <!-- Symptoms Detail Field -->
                    <div class="symp-details">
                        <div class="patient-info-text">
                            <h4>Patient Information</h4>
                        </div>
                        <div class="form-floating" id="form-floating">
                            <textarea class="form-control note" name='symptoms' placeholder="notes" id="floatingTextarea2" style="height: 150px">{{ old('symptoms') }}</textarea>
                            <label for="floatingTextarea2">Enter Brief Details of Symptoms(optional)</label>
                        </div>
                    </div>

                    <!--  Patient FirstName, LastName ,DOB Field  -->

                    <div class=" grid-2">
                        <div class="form-floating" id="form-floating">
                            <input type="text" name="first_name"
                                class="form-control first_name @error('first_name') is-invalid @enderror"
                                id="floatingInput5" placeholder="First Name" value="{{ old('first_name') }}">
                            <label for="floatingInput5">First Name</label>
                            @error('first_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating" id="form-floating">
                            <input type="text" name="last_name"
                                class="form-control last_name @error('last_name') is-invalid @enderror" id="floatingInput6"
                                placeholder="Last Name" value="{{ old('last_name') }}">
                            <label for="floatingInput6">Last Name</label>
                            @error('last_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating" id="form-floating">
                            <input type="date"
                                class="form-control date_of_birth @error('date_of_birth') is-invalid @enderror"
                                id="floatingInput7" name="date_of_birth" placeholder="date of birth"
                                value="{{ old('date_of_birth') }}">
                            <label for="floatingInput7">Date Of Birth</label>
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
                                    id="floatingInput8" placeholder="name@example.com" name="email"
                                    value="{{ old('email') }}">
                                <label for="floatingInput8">Email</label>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="" style="height: 58px;" id="form-floating">
                                <input type="tel" name="phone_number"
                                    class="form-control phone @error('phone_number') is-invalid @enderror" id=""
                                    value="{{ old('phone_number') }}" placeholder="Phone Number">
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
                                    class="form-control street @error('street') is-invalid @enderror" id="floatingInput9"
                                    placeholder="Street" value="{{ old('street') }}">
                                <label for="floatingInput9">Street</label>
                                @error('street')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating" id="form-floating">
                                <input type="text" name="city"
                                    class="form-control city @error('city') is-invalid @enderror" id="floatingInput10"
                                    placeholder="City" value="{{ old('city') }}">
                                <label for="floatingInput10">City</label>
                                @error('city')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating" id="form-floating">
                                <input type="text" name="state"
                                    class="form-control state @error('state') is-invalid @enderror" id="floatingInput11"
                                    placeholder="State" value="{{ old('state') }}">
                                <label for="floatingInput11">State</label>
                                @error('state')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating w-100" id="form-floating">
                                <input type="number" name="zipcode"
                                    class="form-control zipcode @error('zipcode') is-invalid @enderror"
                                    id="floatingInput12" placeholder="Zipcode" value="{{ old('zipcode') }}"
                                    min="0">
                                <label for="floatingInput12">Zipcode</label>
                                @error('zipcode')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating" id="form-floating">
                                <input type="number" name="room"
                                    class="form-control room @error('room') is-invalid @enderror" id="floatingInput13"
                                    placeholder="room" value="{{ old('room') }}" min="0">
                                <label for="floatingInput13">Room (optional)</label>
                                @error('room')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
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
                                @error('docs')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!--  SUBMIT and CANCEL Buttons -->
                        <div class="buttons">
                            <button class="primary-fill btn-submit" type="submit">Submit</button>
                            <a href="{{ route('submitRequest') }}" type="button" class="primary-empty"
                                id="cancel-btn">Cancel </a>
                        </div>
            </form>
        </div>

        <div class="overlay" style="display: none;"></div>
        <div class="pop-up submit-valid-details" id="validDetailsPopup" style="display: none;">

            <div class="m-5 d-flex flex-column justify-content-center align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" fill="currentColor"
                    class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                </svg>
                <h3 class="mt-4">Information</h3>

                <div class="mt-2">When submitting a request,you must provide the correct
                    contact information for the patient or the responsibly party.
                    Failure to provide the correct email and phone number will be delay servide or be declined
                </div>
                <button class="primary-fill submit-valid-details-ok-btn w-6 mt-4" id="closePopupBtn"
                    type="button">Ok</button>
            </div>

        </div>

    </div>
    </div>
@endsection

@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ URL::asset('assets/patientSite/familyRequestFormValidation.js') }}"></script>
    <script defer src="{{ asset('assets/patientSite/patientSite.js') }}"></script>
    <script defer src="{{ asset('assets/patientSite/patientValidInfoSubmit.js') }}"></script>
@endsection
