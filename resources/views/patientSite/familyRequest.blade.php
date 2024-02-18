@extends('index')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/familyRequest.css') }}">

@endsection

@section('content')

<div class="container">

    <!-- this div is for back button -->

    <div class="header_part">
        <a type="button" class="btn btn-back" href="{{route('submitRequest')}}">
            < Back</a>
    </div>

    <div class="patient-container">


        <form action="{{route('familyRequests')}}" method="post" enctype="multipart/form-data">
            @csrf

            <!-- Family/Friend Information -->

            <div class="family-inputs">

                <div class="family-text">
                    <h4> Family/Friend Information</h4>
                </div>
                
                <input type="hidden" name="request_type" value="2">     




                <div class="row family-row1">
                    <div class="col-md family-col1">
                        <input type="text" placeholder="Your First Name"
                            class="form-control family-first-name-text @error('family_first_name') is-invalid @enderror"
                            name="family_first_name">
                        @error('family_first_name')
                        <div class="text-danger ">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md family-col2">
                        <input type="text" placeholder="Your Last Name"
                            class="form-control family-last-name-text @error('family_last_name') is-invalid @enderror"
                            name="family_last_name" id="" aria-describedby="emailHelp">
                        @error('family_last_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <div class="row family-row2">
                    <div class="col-md family-col1">
                        <input type="text" placeholder="Mobile No"
                            class="form-control family-mobile-text @error('family_mobile') is-invalid @enderror"
                            name="family_phone_number" id="" aria-describedby="emailHelp">
                        @error('family_mobile')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md family-col2">
                        <input type="email" placeholder="Your Email"
                            class="form-control family-email-text @error('family_email') is-invalid @enderror"
                            name="family_email" id="" aria-describedby="emailHelp">
                        @error('family_email')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <div class="row family-row3">

                    <div class="col-md family-col3 me-4">
                        <input type="text" placeholder="Relation with Patient"
                            name="family_relation" class="form-control family-relation-text w-50 @error('family_relation') is-invalid @enderror" id="family_relation">
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
                        <input type="text" placeholder="FirstName"
                            class="form-control first-name-text @error('first_name') is-invalid @enderror" id=""
                            aria-describedby="helpId" name="first_name">
                            @error('first_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    </div>
                  


                    <div class="col-md patient-details-col2">
                        <input type="text" placeholder="LastName"
                            class="form-control last-name-text @error('last_name') is-invalid @enderror" id=""
                            aria-describedby="helpId" name="last_name">
                            @error('last_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    </div>
               

                </div>

                <div class="row patient-details-row2">

                    <div class="col-md patient-details-col3 me-4">
                        <label for="">Date of Birth</label>
                        <input type="date" placeholder="Date-Of-Birth" class="form-control date-of-birth w-50 " id=""
                            name="date_of_birth">

                    </div>

                </div>


                <!--     Patient Contact Information    -->

                <div class="patient-contact-inputs">

                    <div class="patient-contact-text">
                        <h4>Patient Contact Information</h4>
                    </div>

                    <div class="row patient-contact-row1">
                        <div class="col-md patient-contact-col1">
                            <input type="email" placeholder="Email"
                                class="form-control email-text @error('email') is-invalid @enderror" id=""
                                aria-describedby="helpId" name="email">
                                @error('email')
                        <div class="text-danger" role="alert">{{ $message }}</div>
                        @enderror
                        </div>
                 

                        <div class="col-md patient-contact-col2">

                            <input type="tel" placeholder="Mobile No"
                                class="form-control mobile-text @error('mobile') is-invalid @enderror" id=""
                                aria-describedby="helpId" name="phone_number">
                                @error('mobile')
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
                            <input type="text" placeholder="Street"
                                class="form-control patient-location-street-text @error('street') is-invalid @enderror"
                                id="street"  name="street">
                                @error('street')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>
                      

                        <div class="col-md patient-location-col2">
                            <input type="text" placeholder="City"
                                class="form-control patient-location-city-text @error('city') is-invalid @enderror"
                                id="city"  name="city">
                                @error('city')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>
                 

                    </div>


                    <div class="row patient-location-row2">
                        <div class="col-md patient-location-col1">
                            <input type="text" placeholder="State"
                                class="form-control patient-location-state-text @error('state') is-invalid @enderror"
                                id="state" aria-describedby="helpId" name="state">
                                @error('state')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>
                   

                        <div class="col-md patient-location-col2">
                            <input type="text" placeholder="Zip Code"
                                class="form-control patient-location-zipcode-text @error('zipcode') is-invalid @enderror"
                                id="zipcode" aria-describedby="helpId" name="zipcode">
                                @error('zipcode')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>
                    

                    </div>


                    <div class="row patient-location-row3">

                        <div class="col-md patient-location-col3 me-4">
                            <input type="number" placeholder="Room/Suite(optional)" name="room"
                                class="form-control patient-location-room-text w-50  @error('room') is-invalid @enderror"
                                id="room" name="room">
                                @error('room')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>
                   

                    </div>
                </div>

                <!--  photo upload or documents -->

                <!-- <div class="docs-upload">

                    <div class="patient-doc-text">
                        <h4>Upload Photo or document (optional)</h4>
                    </div>

                    <div class="input-group mb-3">

                        <input type="file" class="form-control input-file" name="docs" id="" placeholder="select file"
                            aria-describedby="fileHelpId" />
                        <button class="primary-fill btn-upload">Upload</button>

                    </div>


                </div> -->



                <!--  SUBMIT and CANCEL Buttons -->
                <div class="buttons">
                    <button class="primary-fill btn-submit" type="submit">Submit</button>
                    <button class="primary-empty btn-cancel" type="cancel">Cancel</button>
                </div>


        </form>
    </div>
    </form>
</div>
</div>

@endsection