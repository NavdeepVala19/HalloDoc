@extends('index')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/conciergeRequest.css') }}">

@endsection

@section('content')

<div class="container">

    <!-- this div is for back button -->

    <div class="header_part">
        <button type="button" class="btn btn-back">
            < Back</button>
    </div>

    <div class="patient-container">

        <!-- Concierge Information -->

        <form action="{{route('conciergeRequests')}}" method="post">
            @csrf

            <div class="Concierge-inputs">

                <div class="Concierge-text">
                    <h4>Concierge Information</h4>
                </div>

                <input type="hidden" name="request_type" value="3">

                <div class="row Concierge-row1">
                    <div class="col-md family-col1">
                        <input type="text" placeholder="Your First Name" class="form-control concierge-first-name-text"
                            name="concierge_first_name" id="" aria-describedby="emailHelp">
                    </div>
                    <div class="col-md Concierge-col2">
                        <input type="text" placeholder="Your Last Name" class="form-control concierge-last-name-text"
                            name="concierge_last_name" id="" aria-describedby="emailHelp">
                    </div>
                </div>


                <div class="row Concierge-row2">
                    <div class="col-md Concierge-col1">
                        <input type="text" placeholder="Mobile No" class="form-control concierge-mobile-text"
                            name="concierge_mobile" id="" aria-describedby="emailHelp">
                    </div>
                    <div class="col-md Concierge-col2">
                        <input type="email" placeholder="Your Email" class="form-control concierge-email-text"
                            name="concierge_email" id="" aria-describedby="emailHelp">
                    </div>
                </div>


                <div class="row Concierge-row3">

                    <div class="col-md Concierge-col3 me-4">
                        <input type="text" placeholder="Hotel/Property Name" name="concierge_hotel_name"
                            class="form-control hotel-text w-50" id="exampleInputPassword1">
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
                        <input type="text" placeholder="Street" class="form-control patient-location-street-text"
                            name="concierge_street" id="" aria-describedby="emailHelp">
                    </div>
                    <div class="col-md patient-location-col2">
                        <input type="text" placeholder="City" class="form-control patient-location-city-text"
                            name="concierge_city" id="" aria-describedby="emailHelp">
                    </div>
                </div>


                <div class="row patient-location-row2">
                    <div class="col-md patient-location-col1">
                        <input type="text" placeholder="State" class="form-control patient-location-state-text"
                            name="concierge_state" id="" aria-describedby="emailHelp">
                    </div>
                    <div class="col-md patient-location-col2">
                        <input type="text" placeholder="Zip Code" class="form-control patient-location-zipcode-text"
                            name="concierge_zip_code" id="" aria-describedby="emailHelp">
                    </div>
                </div>

            </div>


            <div class="patient-details">
                <form>
                    <!-- Symptoms Detail Field -->

                    <div class="symp-details">
                        <div class="patient-info-text">
                            <h4>Patient Information</h4>
                        </div>

                        <div class="area-text">
                            <div class="form-floating">
                                <textarea class="form-control text-area-box" placeholder="Leave a comment here"
                                    name="symptoms" id="floatingTextarea3" style="height: 150px"></textarea>
                                <label for="floatingTextarea2" class="floatingTextarea2">Enter Brief Details of
                                    Symptoms(optional)</label>
                            </div>
                        </div>

                    </div>


                    <!--  Patient FirstName, LastName ,DOB Field  -->

                    <div class="row patient-details-row1">
                        <div class="col-md patient-details-col1">
                            <input type="text" placeholder="FirstName" class="form-control first-name-text"
                                name="first_name" id="" aria-describedby="emailHelp">
                        </div>
                        <div class="col-md patient-details-col2">
                            <input type="text" placeholder="LastName" class="form-control last-name-text"
                                name="last_name" id="" aria-describedby="emailHelp">
                        </div>
                    </div>

                    <div class="row patient-details-row2">

                        <div class="col-md patient-details-col3 me-4">
                            <label for="">Date of Birth</label>
                            <input type="date" placeholder="Date-Of-Birth" class="form-control date-of-birth w-50 "
                                name="date_of_birth" id="">

                        </div>

                    </div>


                    <!--     Patient Contact Information    -->

                    <div class="patient-contact-inputs">

                        <div class="patient-contact-text">
                            <h4>Patient Contact Information</h4>
                        </div>

                        <div class="row patient-contact-row1">
                            <div class="col-md patient-contact-col1">
                                <input type="email" placeholder="Email" class="form-control email-text" name="email"
                                    id="" aria-describedby="emailHelp">
                            </div>
                            <div class="col-md patient-contact-col2">

                                <input type="tel" placeholder="Mobile No" class="form-control mobile-text"
                                    name="phone_number" id="" aria-describedby="emailHelp" pattern="[0-9]{10}">
                            </div>
                        </div>

                    </div>

                    <!--   Patient Location Information   -->


                    <div class="patient-location-inputs">

                        <div class="patient-location-text">
                            <h4> Patientlocation</h4>
                        </div>

                        <div class="row patient-location-row3">

                            <div class="col-md patient-location-col3 me-4">
                                <input type="text" placeholder="Room/Suite(optional)" name="room"
                                    class="form-control patient-location-room-text w-50" id="exampleInputPassword1">
                            </div>

                        </div>
                    </div>


                    <!--  SUBMIT and CANCEL Buttons -->

                    <div class="buttons">
                        <button class="primary-fill btn-submit" type="submit">Submit</button>
                        <button class="primary-empty btn-cancel" type="cancel">Cancel</button>
                    </div>




                </form>
            </div>
    </div>
</div>

@endsection