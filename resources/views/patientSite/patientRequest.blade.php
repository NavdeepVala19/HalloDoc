@extends('index')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientRequest.css') }}">

@endsection

@section('content')

<div class="container">

    <!-- this div is for heading and back button -->

    <div class="header_part">
        <button type="button" class="btn btn-back">
            < Back</button>
    </div>

    <div class="patient-container">


        <form action="{{route('patientRequests')}}" method="post" enctype="multipart/form-data">
            @csrf
            
            <div class="patient-details">

                <!-- Symptoms Detail Field -->

                <div class="symp-details">
                    <div class="patient-info-text">
                        <h4>Patient Information</h4>
                    </div>


                    <input type="hidden" name="request_type" value="1">


                    <div class="area-text">
                        <div class="form-floating">
                            <textarea class="form-control text-area-box" placeholder="Leave a comment here" name="symptoms"
                                id="floatingTextarea3" style="height: 150px"></textarea>
                            <label for="floatingTextarea2" class="floatingTextarea2">Enter Brief Details of
                                Symptoms(optional)</label>
                        </div>
                    </div>

                </div>

                <!--  Patient FirstName, LastName ,DOB Field  -->

                <div class="row patient-details-row1">
                    <div class="col-md patient-details-col1">
                        <input type="text" placeholder="FirstName" class="form-control first-name-text "
                            id="" aria-describedby="helpId" name="first_name">
                    </div>
                    <div class="col-md patient-details-col2">
                        <input type="text" placeholder="LastName" class="form-control last-name-text"
                            id="" aria-describedby="helpId" name="last_name">
                    </div>
                </div>

                <div class="row patient-details-row2">

                    <div class="col-md patient-details-col3 me-4">
                        <label for="">Date of Birth</label>
                        <input type="date" placeholder="Date-Of-Birth" class="form-control date-of-birth w-50 "
                            id="" name="date_of_birth">

                    </div>

                </div>


                <!--     Patient Contact Information    -->

                <div class="patient-contact-inputs">

                    <div class="patient-contact-text">
                        <h4>Patient Contact Information</h4>
                    </div>

                    <div class="row patient-contact-row1">
                        <div class="col-md patient-contact-col1">
                            <input type="email" placeholder="Email" class="form-control email-text"
                                id="" aria-describedby="helpId" name="email">
                        </div>
                        <div class="col-md patient-contact-col2">

                            <input type="tel" placeholder="Mobile No" class="form-control mobile-text"
                                id="" aria-describedby="helpId" pattern="[0-9]{10}"
                                name="phone_number">
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
                            <input type="text" placeholder="Street" class="form-control patient-location-street-text"
                                id="exampleInputEmail1" aria-describedby="helpId" name="street">
                        </div>
                        <div class="col-md patient-location-col2">
                            <input type="text" placeholder="City" class="form-control patient-location-city-text"
                                id="exampleInputEmail1" aria-describedby="helpId" name="city">
                        </div>
                    </div>


                    <div class="row patient-location-row2">
                        <div class="col-md patient-location-col1">
                            <input type="text" placeholder="State" class="form-control patient-location-state-text"
                                id="exampleInputEmail1" aria-describedby="helpId" name="state">
                        </div>
                        <div class="col-md patient-location-col2">
                            <input type="text" placeholder="Zip Code" class="form-control patient-location-zipcode-text"
                                id="exampleInputEmail1" aria-describedby="helpId" name="zipcode">
                        </div>
                    </div>


                    <div class="row patient-location-row3">

                        <div class="col-md patient-location-col3 me-4">
                            <input type="text" placeholder="Room/Suite(optional)" name="room"
                                class="form-control patient-location-room-text w-50" id="exampleInputPassword1"
                                name="room">
                        </div>

                    </div>
                </div>

                <!--  photo upload or documents -->

                <!-- <div class="docs-upload">

                    <div class="patient-doc-text">
                        <h4>Upload Photo or document (optional)</h4>
                    </div>

                    <div class="input-group mb-3">
                         <input type="file" class="form-control select-file-text" placeholder="Select File"
                            aria-describedby="fileHelpId" name="image">
                        <a href="" class="primary-fill btn-upload" type="button">Upload</a>
                        <button  class="primary-fill btn-upload" >Upload</button>
                        
                        
                        
                        <input type="file" class="form-control input-file" name="docs" id="" placeholder="select file"
                        aria-describedby="fileHelpId" />
                        <button  class="primary-fill btn-upload" >Upload</button>  



                    </div>
                </div> -->


                <!--  SUBMIT and CANCEL Buttons -->

                <div class="buttons">
                    <button class="primary-fill btn-submit" type="submit">Submit</button>
                    <button class="primary-empty btn-cancel" type="cancel">Cancel</button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection