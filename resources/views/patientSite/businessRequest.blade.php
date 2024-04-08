@extends('patientSiteIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/businessRequest.css') }}">

@endsection

@section('patientSiteContent')

<div class="container">

    <!-- this div is for back button -->

    <div class="header_part">
        <a href="{{route('submitRequest')}}" type="button" class="primary-empty">
            < Back</a>
    </div>

    <form action="{{route('businessRequests')}}" method="post" id="patientRequestForm">
        @csrf
        <div class="patient-container">
            <!-- Business Information -->
            <div class="business-inputs">
                <div class="business-text">
                    <h4> Business Information</h4>
                </div>
                <div class="grid-2">
                    <div class="form-floating">
                        <input type="text" name="business_first_name" class="form-control business_first_name" id="floatingInput" placeholder="Your First Name" value="{{ old('business_first_name') }}">
                        <label for="floatingInput">Your First Name</label>
                        @error('business_first_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="text" name="business_last_name" class="form-control business_last_name" id="floatingInput" value="{{ old('business_last_name') }}" placeholder="Your Last Name">
                        <label for="floatingInput">Your Last Name</label>
                        @error('business_last_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating" style="height: 58px;">
                        <input type="tel" name="business_mobile" class="form-control phone" id="telephone" placeholder="Phone Number" value="{{ old('business_mobile') }}">
                        @error('business_mobile')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="email" class="form-control email" id="floatingInput" placeholder="name@example.com" name="business_email" value="{{ old('business_email') }}">
                        <label for="floatingInput">Email</label>
                        @error('business_email')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="floatingInput" value="{{ old('business_property_name') }}" name="business_property_name" placeholder="Business/Property Name">
                        <label for="floatingInput">Business/Property Name</label>
                        @error('business_property_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="number" class="form-control" id="floatingInput" value="{{ old('case_number') }}" name="case_number" placeholder="Case Number (optional)">
                        <label for="floatingInput">Case Number (optional)</label>
                        @error('case_number')
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
                            <textarea class="form-control text-area-box" placeholder="Leave a comment here" name="symptoms" id="floatingTextarea3" style="height: 150px" value="{{ old('symptoms') }}"></textarea>
                            <label for="floatingTextarea2" class="floatingTextarea2">Enter Brief Details of
                                Symptoms(optional)</label>
                        </div>
                    </div>
                </div>

                <!--  Patient FirstName, LastName ,DOB Field  -->

                <div class=" grid-2">
                    <div class="form-floating">
                        <input type="text" name="first_name" class="form-control first_name" id="floatingInput" value="{{ old('first_name') }}" placeholder="First Name">
                        <label for="floatingInput">First Name</label>
                        @error('first_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="text" name="last_name" class="form-control last_name" id="floatingInput" value="{{ old('last_name') }}" placeholder="Last Name">
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

                <!--  SUBMIT and CANCEL Buttons -->

                <div class="buttons">
                    <button class="primary-fill btn-submit" type="submit">Submit</button>
                    <a href="{{route('submitRequest')}}" type="button" class="primary-empty">Cancel </a>
                </div>


            </div>
        </div>
    </form>


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

@endsection


@section('script')
<script defer src="{{ asset('assets/validation/jquery.validate.min.js')}}"></script>
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection