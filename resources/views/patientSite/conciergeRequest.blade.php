@extends('patientRequests')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/patientSite/conciergeRequest.css') }}">
@endsection

@section('patientRequests')
    @include('loading')

    <div class="container mb-3">
        <div class="header_part">
            <a href="{{ route('submit.request') }}" type="button" class="primary-empty">
                < Back</a>
        </div>
        <div class="patient-container">
            <!-- Concierge Information -->
            <form action="{{ route('concierge.request.submit') }}" method="post" id="patientRequestForm">
                @csrf
                <div class="Concierge-inputs">
                    <div class="Concierge-inputs">
                        <div class="Concierge-text">
                            <h4>Concierge Information</h4>
                        </div>
                        <input type="hidden" name="request_type" value="3">
                        <div class="grid-2">
                            <div class="form-floating" id="form-floating">
                                <input type="text" name="concierge_first_name" autocomplete="off"
                                    class="form-control concierge_first_name @error('concierge_first_name') is-invalid @enderror"
                                    id="floatingInput1" value="{{ old('concierge_first_name') }}"
                                    placeholder="Your First Name">
                                <label for="floatingInput1">Your First Name</label>
                                @error('concierge_first_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating" id="form-floating">
                                <input type="text" name="concierge_last_name"
                                    class="form-control concierge_last_name @error('concierge_last_name') is-invalid @enderror" autocomplete="off"
                                    id="floatingInput2" value="{{ old('concierge_last_name') }}" 
                                    placeholder="Your Last Name">
                                <label for="floatingInput2">Your Last Name</label>
                                @error('concierge_last_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating" style="height: 58px;" id="form-floating">
                                <input type="tel" name="concierge_mobile"
                                    class="form-control phone @error('concierge_mobile') is-invalid @enderror" autocomplete="off"
                                    id="telephone" value="{{ old('concierge_mobile') }}">
                                @error('concierge_mobile')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating" id="form-floating">
                                <input type="email"
                                    class="form-control email @error('concierge_email') is-invalid @enderror" autocomplete="off"
                                    id="floatingInput3" placeholder="name@example.com" name="concierge_email"
                                    value="{{ old('concierge_email') }}">
                                <label for="floatingInput3">Email</label>
                                @error('concierge_email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating" id="form-floating">
                                <input type="text"
                                    class="form-control @error('concierge_hotel_name') is-invalid @enderror" autocomplete="off"
                                    id="floatingInput4" value="{{ old('concierge_hotel_name') }}"
                                    name="concierge_hotel_name" placeholder="Hotel/Property Name">
                                <label for="floatingInput4">Hotel/Property Name</label>
                                @error('concierge_hotel_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!--  Concierge Location Information   -->
                    <div class="patient-location-inputs">
                        <div class="patient-location-text">
                            <h4>Concierge location</h4>
                        </div>
                        <div class="grid-2">
                            <div class="form-floating" id="form-floating">
                                <input type="text" name="concierge_street"
                                    class="form-control concierge_street @error('concierge_street') is-invalid @enderror" autocomplete="off"
                                    id="floatingInput5" placeholder="Street" value="{{ old('concierge_street') }}">
                                <label for="floatingInput5">Street</label>
                                @error('concierge_street')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating" id="form-floating">
                                <input type="text" name="concierge_city"
                                    class="form-control concierge_city @error('concierge_city') is-invalid @enderror" autocomplete="off"
                                    id="floatingInput6" placeholder="City" value="{{ old('concierge_city') }}">
                                <label for="floatingInput6">City</label>
                                @error('concierge_city')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating" id="form-floating">
                                <input type="text" name="concierge_state"
                                    class="form-control concierge_state @error('concierge_state') is-invalid @enderror" autocomplete="off"
                                    id="floatingInput7" placeholder="State" value="{{ old('concierge_state') }}">
                                <label for="floatingInput7">State</label>
                                @error('concierge_state')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating w-100" id="form-floating">
                                <input type="number" name="concierge_zip_code"
                                    class="form-control zipcode @error('concierge_zip_code') is-invalid @enderror" autocomplete="off"
                                    id="floatingInput8" placeholder="Zipcode" value="{{ old('concierge_zip_code') }}"
                                    min="0">
                                <label for="floatingInput8">Zipcode</label>
                                @error('concierge_zip_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- patient details -->

                    <div class="patient-details">
                        <!-- Symptoms Detail Field -->
                        <div class="symp-details">
                            <div class="patient-info-text">
                                <h4>Patient Information</h4>
                            </div>
                            <div class="area-text">
                                <div class="form-floating" id="form-floating">
                                    <textarea class="form-control text-area-box" placeholder="Leave a comment here" name="symptoms" autocomplete="off"
                                        id="floatingTextarea3" style="height: 150px">{{ old('symptoms') }}</textarea>
                                    <label for="floatingTextarea3" class="floatingTextarea2 symptoms-notes-label">Enter Brief Details of
                                        Symptoms(optional)</label>
                                </div>
                            </div>
                        </div>

                        <!--  Patient FirstName, LastName ,DOB Field  -->
                        <div class=" grid-2">
                            <div class="form-floating" id="form-floating">
                                <input type="text" name="first_name"
                                    class="form-control first_name @error('first_name') is-invalid @enderror" autocomplete="off"
                                    id="floatingInput9" value="{{ old('first_name') }}" placeholder="First Name">
                                <label for="floatingInput9">First Name</label>
                                @error('first_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-floating" id="form-floating">
                                <input type="text" name="last_name"
                                    class="form-control last_name @error('last_name') is-invalid @enderror" autocomplete="off"
                                    id="floatingInput10" value="{{ old('last_name') }}" placeholder="Last Name">
                                <label for="floatingInput10">Last Name</label>
                                @error('last_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating" id="form-floating">
                                <input type="date"
                                    class="form-control date_of_birth @error('date_of_birth') is-invalid @enderror" autocomplete="off"
                                    id="floatingInput11" name="date_of_birth" placeholder="date of birth"
                                    value="{{ old('date_of_birth') }}">
                                <label for="floatingInput11">Date Of Birth</label>
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
                                    <input type="email" class="form-control email @error('email') is-invalid @enderror" autocomplete="off"
                                        id="floatingInput12" placeholder="name@example.com" name="email"
                                        value="{{ old('email') }}">
                                    <label for="floatingInput12">Email</label>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="" style="height: 58px;" id="form-floating">
                                    <input type="number" name="phone_number"
                                        class="form-control phone @error('phone_number') is-invalid @enderror" autocomplete="off"
                                        id="telephone" value="{{ old('phone_number') }}" placeholder="Phone Number" min="0">
                                    @error('phone_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-floating" id="form-floating">
                                    <input type="number" name="room"
                                        class="form-control room @error('room') is-invalid @enderror" autocomplete="off"
                                        id="floatingInput13" placeholder="room" value="{{ old('room') }}"
                                        min="0">
                                    <label for="floatingInput13">Room (Optional) </label>
                                    @error('room')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  SUBMIT and CANCEL Buttons -->
                    <div class="buttons">
                        <button class="primary-fill btn-submit" type="submit">Submit</button>
                        <a href="{{ route('submit.request') }}" type="button" class="primary-empty">Cancel </a>
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
    <script defer src="{{ asset('assets/patientSite/patientSite.js') }}"></script>
    <script defer src="{{ URL::asset('assets/patientSite/conciergeRequestFormValidation.js') }}"></script>
    <script defer src="{{ asset('assets/patientSite/patientValidInfoSubmit.js') }}"></script>
@endsection
