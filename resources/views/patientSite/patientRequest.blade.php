@extends('index')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientRequest.css') }}">

@endsection



@section('content')

<div class="container">

    <!-- this div is for heading and back button -->

    <div class="header_part">
        <a href="{{route('submitRequest')}}" type="button" class="primary-empty">
            < Back</a>
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
                            <textarea class="form-control text-area-box" name="symptoms" id="floatingTextarea3" style="height: 150px"></textarea>
                            <label>Enter Brief Details of
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
                        <input type="text" placeholder="LastName" class="form-control last-name-text @error('last_name') is-invalid @enderror " aria-describedby="helpId" name="last_name" value="{{ old('last_name') }}">

                        @error('last_name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                </div>

                <div class="row patient-details-row2">

                    <div class="col-md patient-details-col3 me-4">
                        <label for="">Date of Birth</label>
                        <input type="date" placeholder="Date-Of-Birth" class="form-control date-of-birth w-50 @error('date_of_birth') is-invalid @enderror " id="" name="date_of_birth" value="{{ old('date_of_birth') }}">
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
                            <input type="email" placeholder="Email" class="form-control email-text @error('email') is-invalid @enderror" id="" value="{{ old('email') }}" name="email">

                            @error('email')
                            <div class="text-danger" role="alert">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md patient-contact-col2">

                            <input type="tel" placeholder="Mobile Number" value="{{ old('phone_number') }}" class="form-control mobile-text mobile @error('phone_number') is-invalid @enderror" id="" name="phone_number">

                            @error('phone_number')
                            <div class="text-danger" role="alert">{{ $message }}</div>
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
                            <input type="text" placeholder="Street" value="{{ old('street') }}" class="form-control patient-location-street-text @error('street') is-invalid @enderror" id="street" name="street">
                            @error('street')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md patient-location-col2">
                            <input type="text" placeholder="City" value="{{ old('city') }}" class="form-control patient-location-city-text @error('city') is-invalid @enderror" id="city" name="city">
                            @error('city')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>


                    <div class="row patient-location-row2">
                        <div class="col-md patient-location-col1">
                            <input type="text" placeholder="State" value="{{ old('state') }}" class="form-control patient-location-state-text @error('state') is-invalid @enderror" id="state" name="state">
                            @error('state')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md patient-location-col2">
                            <input type="text" placeholder="Zip Code" value="{{ old('zipcode') }}" class="form-control patient-location-zipcode-text @error('zipcode') is-invalid @enderror" id="zipcode" name="zipcode">
                            @error('zipcode')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>


                    <div class="row patient-location-row3">

                        <div class="col-md patient-location-col3 me-4">
                            <input type="number" placeholder="Room/Suite(optional)" name="room" value="{{ old('room') }}" class="form-control patient-location-room-text w-50  @error('room') is-invalid @enderror" id="room" name="room">
                            @error('room')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
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
                        <p></p>
                    </div>
                </div>


                <!--  SUBMIT and CANCEL Buttons -->

                <div class="buttons">
                    <button class="primary-fill me-2" type="submit">Submit</button>
                    <button class="primary-empty " type="cancel">Cancel</button>
                </div>

            </div>
        </form>
    </div>
</div>




@endsection