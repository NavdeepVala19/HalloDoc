@extends('patientSiteIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/familyRequest.css') }}">

@endsection

@section('patientSiteContent')

<div class="container">

    <!-- this div is for back button -->

    <div class="header_part">
        <a href="{{route('submitRequest')}}" type="button" class="primary-empty" id="back-btn">
            < Back</a>
    </div>

    <div class="patient-container">


        <form action="{{route('familyRequests')}}" method="post" enctype="multipart/form-data" id="patientRequestForm">
            @csrf

            <!-- Family/Friend Information -->

            <div class="family-inputs">

                <div class="family-text">
                    <h4> Family/Friend Information</h4>
                </div>

                <input type="hidden" name="request_type" value="2">




                <div class="row family-row1">
                    <div class="col-md family-col1 patient">
                        <input type="text" placeholder="Your First Name" class="form-control family-first-name-text @error('family_first_name') is-invalid @enderror" value="{{ old('family_first_name') }}" name="family_first_name">
                        @error('family_first_name')
                        <div class="text-danger ">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md family-col2 patient">
                        <input type="text" placeholder="Your Last Name" class="form-control family-last-name-text @error('family_last_name') is-invalid @enderror" name="family_last_name" id="" aria-describedby="emailHelp" value="{{ old('family_last_name') }}">
                        @error('family_last_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <div class="row family-row2">
                    <div class="col-md family-col1 patient">
                        <input type="text" placeholder="Mobile No" class="form-control family-mobile-text @error('family_phone_number') is-invalid @enderror" name="family_phone_number" value="{{ old('family_phone_number') }}" id="" aria-describedby="emailHelp">
                        @error('family_phone_number')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md family-col2 patient">
                        <input type="email" placeholder="Your Email" class="form-control family-email-text @error('family_email') is-invalid @enderror" name="family_email" id="" value="{{ old('family_email') }}" aria-describedby="emailHelp">
                        @error('family_email')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <div class="row family-row3">

                    <div class="col-md family-col3 me-4 patient">
                        <input type="text" placeholder="Relation with Patient" name="family_relation" class="form-control family-relation-text w-50 @error('family_relation') is-invalid @enderror" value="{{ old('family_relation') }}" id="family_relation">
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

                    <div class="area-text">
                        <div class="form-floating">
                            <textarea class="form-control text-area-box" placeholder="Leave a comment here" name="symptoms" value="{{ old('symptoms') }}" id="floatingTextarea3" style="height: 150px"></textarea>
                            <label for="floatingTextarea2" class="floatingTextarea2">Enter Brief Details of
                                Symptoms(optional)</label>
                        </div>
                    </div>

                </div>

                <!--  Patient FirstName, LastName ,DOB Field  -->

                <div class="row patient-details-row1">
                    <div class="col-md patient-details-col1 patient">
                        <input type="text" placeholder="FirstName" class="form-control first-name-text @error('first_name') is-invalid @enderror" id="" value="{{ old('first_name') }}" aria-describedby="helpId" name="first_name">
                        @error('first_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>



                    <div class="col-md patient-details-col2 patient">
                        <input type="text" placeholder="LastName" class="form-control last-name-text @error('last_name') is-invalid @enderror" id="" value="{{ old('last_name') }}" aria-describedby="helpId" name="last_name">
                        @error('last_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                </div>

                <div class="row patient-details-row2">

                    <div class="col-md patient-details-col3 me-4 patient">
                        <label for="">Date of Birth</label>
                        <input type="date" placeholder="Date-Of-Birth" class="form-control date-of-birth w-50 @error('date_of_birth') is-invalid @enderror" id="" value="{{ old('date_of_birth') }}" name="date_of_birth">
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
                        <div class="col-md patient-contact-col1 patient">
                            <input type="email" placeholder="Email" class="form-control email-text @error('email') is-invalid @enderror" id="" value="{{ old('email') }}" aria-describedby="helpId" name="email">
                            @error('email')
                            <div class="text-danger" role="alert">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md patient-contact-col2 patient">
                            <input type="tel" placeholder="Mobile No" class="form-control mobile-text @error('phone_number') is-invalid @enderror" id="" value="{{ old('phone_number') }}" aria-describedby="helpId" name="phone_number">
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

                    <div class="row patient-location-row1">
                        <div class="col-md patient-location-col1 patient">
                            <input type="text" placeholder="Street" class="form-control patient-location-street-text @error('street') is-invalid @enderror" id="street" name="street" value="{{ old('street') }}">
                            @error('street')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md patient-location-col2 patient">
                            <input type="text" placeholder="City" class="form-control patient-location-city-text @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}">
                            @error('city')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>


                    <div class="row patient-location-row2">
                        <div class="col-md patient-location-col1 patient">
                            <input type="text" placeholder="State" class="form-control patient-location-state-text @error('state') is-invalid @enderror" id="state" aria-describedby="helpId" name="state" value="{{ old('state') }}">
                            @error('state')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md patient-location-col2 patient">
                            <input type="text" placeholder="Zip Code" class="form-control patient-location-zipcode-text @error('zipcode') is-invalid @enderror" id="zipcode" aria-describedby="helpId" name="zipcode" value="{{ old('zipcode') }}">
                            @error('zipcode')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>


                    <div class="row patient-location-row3">
                        <div class="col-md patient-location-col3 me-4">
                            <input type="number" placeholder="Room/Suite(optional)" name="room" class="form-control patient-location-room-text w-50  " id="room" name="room" value="{{ old('room') }}">
                        </div>


                    </div>
                </div>

                <!--  photo upload or documents -->

                <div class="docs-upload">

                    <div class="patient-doc-text">
                        <h4>Upload Photo or document (optional)</h4>
                    </div>

                    <div class="input-group mb-3">

                        <label for="">Select File</label>
                        <div class="file-selection-container" onclick="openFileSelection()">
                            <input type="file" id="fileInput" class="file-input" name="docs" />
                            <div class="file-button">Upload</div>
                        </div>
                        <p id="demo"></p>
                    </div>
                </div>



                <!--  SUBMIT and CANCEL Buttons -->
                <div class="buttons">
                    <button class="primary-fill btn-submit" type="submit">Submit</button>
                    <a href="{{route('submitRequest')}}" type="button" class="primary-empty" id="cancel-btn">Cancel </a>
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
<script defer src="{{ asset('assets/validation/jquery.validate.min.js')}}"></script>
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection