@extends('index')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/businessRequest.css') }}">

@endsection

@section('content')

<div class="container">

    <!-- this div is for back button -->

    <div class="header_part">
        <button type="button" class="btn btn-back">
            < Back</button>
    </div>

    <form action="{{route('businessRequests')}}" method="post">
        @csrf
        
        <div class="patient-container">

            <!-- Business Information -->

            <div class="business-inputs">

                <div class="business-text">
                    <h4> Business Information</h4>
                </div>


                <input type="hidden" name="request_type" value="4">


                <div class="row business-row1">
                    <div class="col-md business-col1">

                        <input type="text" placeholder="Your First Name" class="form-control business-first-name-text" name="business_first_name"
                            id="" aria-describedby="emailHelp">

                    </div>
                    <div class="col-md business-col2">
                        <input type="text" placeholder="Your Last Name" class="form-control business-last-name-text" name="business_last_name"
                            id="">
                    </div>
                </div>


                <div class="row business-row2">
                    <div class="col-md business-col1"> 
                        <input type="text" placeholder="Mobile No" class="form-control business-mobile-text" name="business_mobile"
                            id="" aria-describedby="emailHelp">
                    </div>
                    <div class="col-md business-col2">
                        <input type="email" placeholder="Your Email" class="form-control business-email-text" name="business_email"
                            id="" aria-describedby="emailHelp">
                    </div>
                </div>


                <div class="row business-row3">

                    <div class="col-md business-col1">

                        <input type="text" placeholder="Business/Property Name" class="form-control business-name-text" name="business_property_name"
                            id="floatingInput">

                    </div>
                    <div class="col-md business-col2">
                        <input type="text" placeholder="Case Number(optional)" name="business_case_number"
                            class="form-control business-case-number-text" id="exampleInputEmail1"> 
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
                            <textarea class="form-control text-area-box" placeholder="Leave a comment here"  name="symptoms"
                                id="floatingTextarea3" style="height: 150px"></textarea>
                            <label for="floatingTextarea2" class="floatingTextarea2">Enter Brief Details of
                                Symptoms(optional)</label>
                        </div>
                    </div>

                </div>


                <!--  Patient FirstName, LastName ,DOB Field  -->

                <div class="row patient-details-row1">
                    <div class="col-md patient-details-col1">
                        <input type="text" placeholder="FirstName" class="form-control first-name-text" name="first_name"
                            id="" aria-describedby="emailHelp">
                    </div>
                    <div class="col-md patient-details-col2">
                        <input type="text" placeholder="LastName" class="form-control last-name-text" name="last_name"
                            id="" aria-describedby="emailHelp">
                    </div>
                </div>

                <div class="row patient-details-row2">

                    <div class="col-md patient-details-col3 me-4">
                        <label for="">Date of Birth</label>
                        <input type="date" placeholder="Date-Of-Birth" class="form-control date-of-birth w-50 " name="date_of_birth"
                            id="">

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

                            <input type="tel" placeholder="Mobile No" class="form-control mobile-text" name="phone_number"
                                id="" aria-describedby="emailHelp" pattern="[0-9]{10}">
                        </div>
                    </div>

                </div>

                <!--   Patient Location Information   -->


                <div class="patient-location-inputs">

                    <div class="patient-location-text">
                        <h4> Patientlocation</h4>
                    </div>

                    <div class="row patient-location-row1">
                        <div class="col-md patient-location-col1">
                            <input type="text" placeholder="Street" class="form-control patient-location-street-text" name="street"
                                id="" aria-describedby="emailHelp">
                        </div>
                        <div class="col-md patient-location-col2">
                            <input type="text" placeholder="City" class="form-control patient-location-city-text" name="city"
                                id="" aria-describedby="emailHelp">
                        </div>
                    </div>


                    <div class="row patient-location-row2">
                        <div class="col-md patient-location-col1">
                            <input type="text" placeholder="State" class="form-control patient-location-state-text" name="state"
                                id="" aria-describedby="emailHelp">
                        </div>
                        <div class="col-md patient-location-col2">
                            <input type="text" placeholder="Zip Code" class="form-control patient-location-zipcode-text" name="zipcode"
                                id="" aria-describedby="emailHelp">
                        </div>
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


            </div>
        </div>
    </form>

<!-- 
    <form>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="inputEmail4">Email</label>
                <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
            </div>
            <div class="form-group col-md-6">
                <label for="inputPassword4">Password</label>
                <input type="password" class="form-control" id="inputPassword4" placeholder="Password">
            </div>
        </div>
        <div class="form-group">
            <label for="inputAddress">Address</label>
            <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
        </div>
        <div class="form-group">
            <label for="inputAddress2">Address 2</label>
            <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputCity">City</label>
                <input type="text" class="form-control" id="inputCity">
            </div>
            <div class="form-group col-md-4">
                <label for="inputState">State</label>
                <select id="inputState" class="form-control">
                    <option selected>Choose...</option>
                    <option>...</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="inputZip">Zip</label>
                <input type="text" class="form-control" id="inputZip">
            </div>
        </div>
        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="gridCheck">
                <label class="form-check-label" for="gridCheck">
                    Check me out
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Sign in</button>
    </form> -->


</div>

@endsection