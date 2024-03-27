@extends('index')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientProfile.css') }}">
@endsection

@section('nav-links')
<a href="{{route('patientDashboardData')}}" class="">Dashboard</a>
<a href="" class="active-link">Profile</a>
@endsection

@section('content')
<div class="container form-container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="heading">User Profile</h2>
        <a href="{{ route('patientDashboardData') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
    </div>

    {{-- Form Starts From Here --}}
    <div class="section">

        <form action="{{route('patientProfileEdited')}}" method="post" id="patientProfileEditForm">

            @csrf
            <h3>General Information </h3>

            <!-- <input type="hidden" name="email" value="{{Session::get('email')}}"> -->

            <div class="grid-2">
                <div class="form-floating ">
                    <input type="text" name="first_name" class="form-control first_name" id="floatingInput" value="{{$getPatientData->first_name}}" placeholder="First Name">
                    <label for="floatingInput">First Name</label>
                    @error('first_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <span class="errorMsg"></span>
                </div>
                <div class="form-floating ">
                    <input type="text" name="last_name" class="form-control last_name" id="floatingInput" value="{{$getPatientData->last_name}}" placeholder="Last Name">
                    <label for="floatingInput">Last Name</label>
                    @error('last_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <span class="errorMsg"></span>
                </div>

                <div class="form-floating ">
                    <input type="date" class="form-control date_of_birth" id="floatingInput" name="date_of_birth" placeholder="date of birth" value="{{$getPatientData->date_of_birth}}">
                    <label for="floatingInput">Date Of Birth</label>
                    @error('date_of_birth')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <span class="errorMsg"></span>
                </div>

            </div>

            <h4>Contact Information</h4>

            <div class="grid-2">

                <div class="form-floating" style="height: 58px;">
                    <input type="tel" name="phone_number" class="form-control phone_number" id="telephone" value="{{$getPatientData->mobile}}" placeholder="Phone Number">
                    @error('phone_number')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <span class="errorMsg"></span>
                </div>

                <div class="form-floating ">
                    <input type="email" class="form-control email" id="floatingInput" value="{{$getPatientData->email}}" placeholder="name@example.com" name="email">
                    <label for="floatingInput">Email</label>
                    @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <span class="errorMsg"></span>
                </div>


            </div>

            <h4>Patient Location</h4>

            <div class="grid-2">

                <div class="form-floating ">
                    <input type="text" name="street" class="form-control street" id="floatingInput" placeholder="Street" value="{{$getPatientData->street}}">
                    <label for="floatingInput">Street</label>
                    @error('street')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <span class="errorMsg"></span>
                </div>

                <div class="form-floating ">
                    <input type="text" name="city" class="form-control city" id="floatingInput" placeholder="City" value="{{$getPatientData->city}}">
                    <label for="floatingInput">City</label>
                    @error('city')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <span class="errorMsg"></span>
                </div>

                <div class="form-floating ">
                    <input type="text" name="state" class="form-control state" id="floatingInput" placeholder="State" value="{{$getPatientData->state}}">
                    <label for="floatingInput">State</label>
                    @error('state')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <span class="errorMsg"></span>
                </div>

                <div class="d-flex gap-4 align-items-center">

                    <div class="form-floating w-100">
                        <input type="text" name="zipcode" class="form-control zipcode" id="floatingInput" placeholder="Zipcode" value="{{$getPatientData->zipcode}}">
                        <label for="floatingInput">Zipcode</label>
                        @error('zipcode')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <span class="errorMsg"></span>
                    </div>
                    <a href="{{route('patientLocationOnMap')}}" class="primary-empty d-flex gap-2"> <i class="bi bi-geo-alt"></i> Map</a>
                </div>
            </div>

            <div class="text-end">

                <button class="primary-fill me-2" type="submit" id="patientProfileSubmitBtn">Submit</button>
                <a href="{{ route('patientProfile') }}" class="primary-empty" type="reset" id="patientProfileCancelBtn">Cancel </a>
            </div>



        </form>
    </div>
</div>
@endsection


@section('script')
<script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
<script>
    $(document).ready(function() {

        // $('#patientProfileEditForm').validate({
        //     rules: {
        //         email: {
        //             required: true,
        //             email: true,
        //             minlength: 2,
        //             maxlength: 30
        //         },
        //         first_name: {
        //             required: true,
        //             minlength: 2,
        //             maxlength: 30
        //         },
        //         last_name: {
        //             required: true,
        //             minlength: 2,
        //             maxlength: 30
        //         },
        //         phone_number: {
        //             required: true,
        //             minlength: 2,
        //             maxlength: 30,
        //             RegExp: /^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/,
        //         },
        //         street: {
        //             required: true,
        //             minlength: 2,
        //             maxlength: 30
        //         },
        //         city: {
        //             required: true,
        //             RegExp: /^[a-zA-Z ,_-]+?$/,
        //             minlength: 2,
        //             maxlength: 30
        //         },
        //         state: {
        //             required: true,
        //             RegExp: /^[a-zA-Z ,_-]+?$/,
        //             minlength: 2,
        //             maxlength: 30
        //         },
        //         zipcode: {
        //             required: true,

        //         },
        //     },
        //     messages: {
        //         email: {
        //             required: "Please enter a valid email format (e.g., user@example.com).",
        //         },
        //         first_name: {
        //             required: "Please enter a firstname between 2 and 30 character",
        //         },
        //         last_name: {
        //             required: "Please enter a lastname between 2 and 30 character",
        //         },
        //         phone_number: {
        //             required: "Please enter a valid mobile",
        //         },
        //         street: {
        //             required: "Please enter a street",
        //         },
        //         city: {
        //             required: "Please enter a city",
        //         },
        //         state: {
        //             required: "Please enter a state",
        //         },
        //         zipcode: {
        //             required: "Please enter a zipcode",
        //             zipcode: function(element) {
        //                 return $(element).val().length === 6;
        //             },
        //         },
        //         errorElement: 'span',
        //         errorPlacement: function(error, element) {
        //             error.addClass('errorMsg');
        //             element.closest('.form-floating').append(error);
        //         },
        //         highlight: function(element, errorClass, validClass) {
        //             $(element).addClass('is-invalid').removeClass('is-valid');
        //         },
        //         unhighlight: function(element, errorClass, validClass) {
        //             $(element).removeClass('is-invalid').addClass('is-valid');
        //         }
        //     }

        // });
        $('#patientProfileEditForm').validate({
            rules: {
                first_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 30,
                },
                email: {
                    required: true,
                    email: true,
                },
                last_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 30
                },
                phone_number: {
                    required: true,
                    minlength: 2,
                    maxlength: 30,
                    // RegExp: /^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/,
                },
                street: {
                    required: true,
                    minlength: 2,
                    maxlength: 30
                },
                city: {
                    required: true,
                    minlength: 2,
                    maxlength: 30,
                    // RegExp: /^[a-zA-Z ,_-]+?$/,
                },
                state: {
                    required: true,
                    minlength: 2,
                    maxlength: 30,
                    RegExp: /^[a-zA-Z ,_-]+?$/,
                },
                zipcode: {
                    required: true,

                },
            },
            messages: {
                email: {
                    required: "Please enter a valid email format (e.g., user@example.com).",
                },
                first_name: {
                    required: "Please enter a firstname between 2 and 30 character",
                },
                last_name: {
                    required: "Please enter a lastname between 2 and 30 character",
                },

            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('errorMsg');
                element.closest('.form-floating').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            }
        });
    });
</script>
@endsection