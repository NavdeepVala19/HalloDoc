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



        @csrf
        <h3>General Information </h3>

        <!-- <input type="hidden" name="email" value="{{Session::get('email')}}"> -->


        <div class="grid-2">
            <div class="form-floating ">
                <input type="text" name="first_name" class="form-control first_name" id="floatingInput" value="{{$getEmailData->first_name}}" disabled placeholder="First Name">
                <label for="floatingInput">First Name</label>
                @error('first_name')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating ">
                <input type="text" name="last_name" class="form-control last_name" id="floatingInput" value="{{$getEmailData->last_name}}" disabled placeholder="Last Name">
                <label for="floatingInput">Last Name</label>
                @error('last_name')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating ">
                <input type="date" class="form-control date_of_birth" id="floatingInput" name="date_of_birth" placeholder="date of birth" disabled value="{{$getEmailData->date_of_birth}}">
                <label for="floatingInput">Date Of Birth</label>
                @error('date_of_birth')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <h4>Contact Information</h4>

        <div class="grid-2">

            <div class="form-floating" style="height: 58px;">
                <input type="tel" name="phone_number" class="form-control phone" id="telephone" value="{{$getEmailData->mobile}}" disabled placeholder="Phone Number">
                @error('phone_number')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating ">
                <input type="email" class="form-control email" id="floatingInput" value="{{$getEmailData->email}}" disabled placeholder="name@example.com" name="email">
                <label for="floatingInput">Email</label>
                @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>




        </div>

        <h4>Patient Location</h4>

        <div class="grid-2">

            <div class="form-floating ">
                <input type="text" name="street" class="form-control street" id="floatingInput" disabled placeholder="Street" value="{{$getEmailData->street}}">
                <label for="floatingInput">Street</label>
                @error('street')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating ">
                <input type="text" name="city" class="form-control city" id="floatingInput" disabled placeholder="City" value="{{$getEmailData->city}}">
                <label for="floatingInput">City</label>
                @error('city')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating ">
                <input type="text" name="state" class="form-control state" id="floatingInput" disabled placeholder="State" value="{{$getEmailData->state}}">
                <label for="floatingInput">State</label>
                @error('state')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror

            </div>

            <div class="d-flex gap-4 align-items-center">

                <div class="form-floating w-100">
                    <input type="text" name="zipcode" class="form-control zipcode" disabled id="floatingInput" placeholder="Zipcode" value="{{$getEmailData->zipcode}}">
                    <label for="floatingInput">Zipcode</label>
                    @error('zipcode')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                </div>
                <a href="{{route('patientLocationOnMap')}}" class="primary-empty d-flex gap-2"> <i class="bi bi-geo-alt"></i> Map</a>
            </div>
        </div>

        <div class="text-end">
            <a href="{{ route('patientProfileEditData', $getEmailData->id) }}" class="primary-fill">Edit</a>
        </div>

    </div>
</div>
@endsection


@section('script')
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection