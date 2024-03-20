@extends('index')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/businessRequest.css') }}">

@endsection

@section('content')

<div class="container">

    <!-- this div is for back button -->

    <div class="header_part">
        <a href="{{route('submitRequest')}}" type="button" class="primary-empty">
            < Back</a>
    </div>

    <form action="{{route('businessRequests')}}" method="post">
        @csrf

        <div class="patient-container">

            <!-- Business Information -->

            <div class="business-inputs">

                <div class="business-text">
                    <h4> Business Information</h4>
                </div>


                <input type="hidden" name="request_type" value="3">


                <div class="row business-row1">
                    <div class="col-md business-col1">

                        <input type="text" placeholder="Your First Name" class="form-control business-first-name-text  @error('business_first_name') is-invalid @enderror" name="business_first_name" id="" aria-describedby="emailHelp">
                        @error('business_first_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror


                    </div>
                    <div class="col-md business-col2">
                        <input type="text" placeholder="Your Last Name" class="form-control business-last-name-text @error('business_last_name') is-invalid @enderror" name="business_last_name" id="">
                        @error('business_last_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                </div>


                <div class="row business-row2">
                    <div class="col-md business-col1">
                        <input type="tel" placeholder="Mobile Number" class="form-control business-mobile-text @error('business_mobile') is-invalid @enderror" name="business_mobile" id="business_mobile">
                        @error('business_mobile')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="col-md business-col2">
                        <input type="email" placeholder="Your Email" class="form-control business-email-text @error('business_email') is-invalid @enderror" name="business_email" id="" aria-describedby="emailHelp">
                        @error('business_email')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                </div>


                <div class="row business-row3">

                    <div class="col-md business-col1">

                        <input type="text" placeholder="Business/Property Name" class="form-control business-name-text @error('business_property_name') is-invalid @enderror" name="business_property_name" id="floatingInput">
                        @error('business_property_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="col-md business-col2">
                        <input type="text" placeholder="Case Number(optional)" name="case_number" class="form-control business-case-number-text ">

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
                            <textarea class="form-control text-area-box" placeholder="Leave a comment here" name="symptoms" id="floatingTextarea3" style="height: 150px"></textarea>
                            <label for="floatingTextarea2" class="floatingTextarea2">Enter Brief Details of
                                Symptoms(optional)</label>
                        </div>
                    </div>

                </div>

                <!--  Patient FirstName, LastName ,DOB Field  -->

                <div class="row patient-details-row1">
                    <div class="col-md patient-details-col1">
                        <input type="text" placeholder="FirstName" class="form-control first-name-text @error('first_name') is-invalid @enderror" id="" aria-describedby="helpId" name="first_name">
                        @error('first_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>


                    <div class="col-md patient-details-col2">
                        <input type="text" placeholder="LastName" class="form-control last-name-text @error('last_name') is-invalid @enderror" id="" aria-describedby="helpId" name="last_name">
                        @error('last_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                </div>

                <div class="row patient-details-row2">

                    <div class="col-md patient-details-col3 me-4">
                        <label for="">Date of Birth</label>
                        <input type="date" placeholder="Date-Of-Birth" class="form-control date-of-birth w-50 @error('date_of_birth') is-invalid @enderror" id="" name="date_of_birth">
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
                            <input type="email" placeholder="Email" class="form-control email-text @error('email') is-invalid @enderror" id="" aria-describedby="helpId" name="email">
                            @error('email')
                            <div class="text-danger" role="alert">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md patient-contact-col2">

                            <input type="tel" placeholder="Mobile No" class="form-control mobile-text @error('phone_number') is-invalid @enderror" id="" aria-describedby="helpId" name="phone_number">
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
                        <div class="col-md patient-location-col1">
                            <input type="text" placeholder="Street" class="form-control patient-location-street-text @error('street') is-invalid @enderror" id="street" name="street">
                            @error('street')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md patient-location-col2">
                            <input type="text" placeholder="City" class="form-control patient-location-city-text @error('city') is-invalid @enderror" id="city" name="city">
                            @error('city')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>


                    <div class="row patient-location-row2">
                        <div class="col-md patient-location-col1">
                            <input type="text" placeholder="State" class="form-control patient-location-state-text @error('state') is-invalid @enderror" id="state" name="state">
                            @error('state')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md patient-location-col2">
                            <input type="number" placeholder="Zip Code" class="form-control patient-location-zipcode-text @error('zipcode') is-invalid @enderror" id="zipcode" name="zipcode">
                            @error('zipcode')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>


                    <div class="row patient-location-row3">

                        <div class="col-md patient-location-col3 me-4">
                            <input type="number" placeholder="Room/Suite(optional)" name="room" class="form-control patient-location-room-text w-50 " id="room" name="room">

                        </div>


                    </div>
                </div>

                <!--  SUBMIT and CANCEL Buttons -->

                <div class="buttons">
                    <button class="primary-fill btn-submit" type="submit">Submit</button>
                    <button class="primary-empty btn-cancel" type="reset">Cancel</button>
                </div>


            </div>
        </div>
    </form>



</div>

@endsection