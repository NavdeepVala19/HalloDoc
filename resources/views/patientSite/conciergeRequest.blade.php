@extends('index')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/conciergeRequest.css') }}">
@endsection

@section('content')
<div class="container">

    <!-- this div is for back button -->


    <div class="header_part">
        <a href="{{route('submitRequest')}}" type="button" class="primary-empty">
            < Back</a>
    </div>

    <div class="patient-container">

        <!-- Concierge Information -->

        <form action="{{ route('conciergeRequests') }}" method="post">
            @csrf

            <div class="Concierge-inputs">

                <div class="Concierge-inputs">

                    <div class="Concierge-text">
                        <h4>Concierge Information</h4>
                    </div>

                    <input type="hidden" name="request_type" value="4">



                    <input type="hidden" name="request_type" value="4">

                    <div class="row Concierge-row1">
                        <div class="col-md family-col1">
                            <input type="text" placeholder="Your First Name" class="form-control concierge-first-name-text @error('concierge_first_name') is-invalid @enderror" name="concierge_first_name" id="" aria-describedby="emailHelp" value="{{ old('concierge_first_name') }}">
                            @error('concierge_first_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md Concierge-col2">
                            <input type="text" placeholder="Your Last Name" class="form-control concierge-last-name-text @error('concierge_last_name') is-invalid @enderror" name="concierge_last_name" id="" aria-describedby="emailHelp" value="{{ old('concierge_last_name') }}">
                            @error('concierge_last_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <div class="row Concierge-row2">
                        <div class="col-md Concierge-col1">
                            <input type="tel" placeholder="Mobile Number" class="form-control concierge-mobile-text @error('concierge_mobile') is-invalid @enderror" name="concierge_mobile" value="{{ old('concierge_mobile') }}">
                            @error('concierge_mobile')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md Concierge-col2">
                            <input type="email" placeholder="Your Email" class="form-control concierge-email-text @error('concierge_email') is-invalid @enderror" name="concierge_email" id="" aria-describedby="emailHelp" value="{{ old('concierge_email') }}">
                            @error('concierge_email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <div class="row Concierge-row3">

                        <div class="col-md Concierge-col3 me-4">
                            <input type="text" placeholder="Hotel/Property Name" name="concierge_hotel_name" class="form-control hotel-text w-50 @error('concierge_hotel_name') is-invalid @enderror" id="exampleInputPassword1" value="{{ old('concierge_hotel_name') }}">
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

                    <div class="row patient-location-row1">
                        <div class="col-md patient-location-col1">
                            <input type="text" placeholder="Street" class="form-control patient-location-street-text @error('concierge_street') is-invalid @enderror" name="concierge_street" id="concierge_street" value="{{ old('concierge_street') }}">
                            @error('concierge_street')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md patient-location-col2">
                            <input type="text" placeholder="City" class="form-control patient-location-city-text @error('concierge_city') is-invalid @enderror" name="concierge_city" id="concierge_city" value="{{ old('concierge_city') }}">
                            @error('concierge_city')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <div class="row patient-location-row2">
                        <div class="col-md patient-location-col1">
                            <input type="text" placeholder="State" class="form-control patient-location-state-text @error('concierge_state') is-invalid @enderror" name="concierge_state" id="concierge_state" value="{{ old('concierge_state') }}">
                            @error('concierge_state')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md patient-location-col2">
                            <input type="text" placeholder="Zip Code" class="form-control patient-location-zipcode-text @error('concierge_zip_code') is-invalid @enderror" name="concierge_zip_code" id="concierge_zip_code" value="{{ old('concierge_zip_code') }}">
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
                            <div class="form-floating">
                                <textarea class="form-control text-area-box" placeholder="Leave a comment here" name="symptoms" id="floatingTextarea3" style="height: 150px" value="{{ old('symptoms') }}"></textarea>
                                <label for="floatingTextarea2" class="floatingTextarea2">Enter Brief Details of
                                    Symptoms(optional)</label>
                            </div>
                        </div>

                    </div>

                    <!--  Patient FirstName, LastName ,DOB Field  -->

                    <div class="row patient-details-row1">
                        <div class="col-md patient-details-col1">
                            <input type="text" placeholder="FirstName" class="form-control first-name-text @error('first_name') is-invalid @enderror" id="" aria-describedby="helpId" name="first_name" value="{{ old('first_name') }}">
                            @error('first_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>


                        <div class="col-md patient-details-col2">
                            <input type="text" placeholder="LastName" class="form-control last-name-text @error('last_name') is-invalid @enderror" id="" aria-describedby="helpId" name="last_name" value="{{ old('last_name') }}">
                            @error('last_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>

                    <div class="row patient-details-row2">

                        <div class="col-md patient-details-col3 me-4">
                            <label for="">Date of Birth</label>
                            <input type="date" placeholder="Date-Of-Birth" class="form-control date-of-birth w-50 @error('date_of_birth') is-invalid @enderror" id="" name="date_of_birth" value="{{ old('date_of_birth') }}">
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

                        <div class="row patient-contact-row1">
                            <div class="col-md patient-contact-col1">
                                <input type="email" placeholder="Email" class="form-control email-text @error('email') is-invalid @enderror" id="" aria-describedby="helpId" name="email" value="{{ old('email') }}">
                                @error('email')
                                <div class="text-danger" role="alert">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md patient-contact-col2">

                                <input type="tel" placeholder="Mobile No" class="form-control mobile-text @error('phone_number') is-invalid @enderror" id="" aria-describedby="helpId" name="phone_number" value="{{ old('phone_number') }}">
                                @error('phone_number')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>

                    </div>

                    <!--   Patient Location Information   -->

                    <div class="row patient-location-row3">

                        <div class="col-md patient-location-col3 me-4">
                            <input type="number" placeholder="Room/Suite(optional)" name="room" class="form-control patient-location-room-text w-50 " id="room" name="room" value="{{ old('room') }}">

                        </div>


                    </div>
                </div>


                <!--  SUBMIT and CANCEL Buttons -->

                <div class="buttons">
                    <button class="primary-fill btn-submit" type="submit">Submit</button>
                    <a href="{{route('submitRequest')}}" type="button" class="primary-empty">Cancel </a>
                </div>




        </form>
    </div>


    <div class="overlay" style="display: none;"></div>
    <div class="pop-up submit-valid-details" id="validDetailsPopup" style="display: none;">

        <div class="m-5 d-flex flex-column justify-content-center align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" fill="currentColor" class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
            </svg>
            <h3 class="mt-4">Information</h3>

            <div class="mt-2">When submitting a request,you must provide the correct
                contact information for the patient or the responsibly party.
                Failure to provide the correct email and phone number will be delay servide or be declined
            </div>
            <button class="primary-fill submit-valid-details-ok-btn w-6 mt-4" id="closePopupBtn">Ok</button>
        </div>

    </div>
</div>
</div>
@endsection

@section('script')
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection