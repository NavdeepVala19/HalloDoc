@extends('patientSiteIndex')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientProfile.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('patient.dashboard') }}" class="">Dashboard</a>
    <a href="" class="active-link">Profile</a>
@endsection

@section('patientSiteContent')
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="heading">User Profile</h2>
            <a href="{{ route('patient.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        {{-- Form Starts From Here --}}
        <div class="section">
            @csrf
            <h3>General Information </h3>
            <input type="hidden" name="email" value="{{ Session::get('email') }}">
            <div class="grid-2">
                <div class="form-floating">
                    <input type="text" name="first_name" class="form-control first_name" id="floatingInput1" autocomplete="off"
                        value="{{ $getEmailData->first_name ? $getEmailData->first_name : ' ' }}" disabled
                        placeholder="First Name">
                    <label for="floatingInput1">First Name</label>
                    @error('first_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="text" name="last_name" class="form-control last_name" id="floatingInput2" autocomplete="off"
                        value="{{ $getEmailData->last_name ? $getEmailData->last_name : ' ' }}" disabled
                        placeholder="Last Name">
                    <label for="floatingInput2">Last Name</label>
                    @error('last_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="date" class="form-control date_of_birth" id="floatingInput3" name="date_of_birth" autocomplete="off"
                        placeholder="date of birth" disabled
                        value="{{ $getEmailData->date_of_birth ? $getEmailData->date_of_birth : ' ' }}">
                    <label for="floatingInput3">Date Of Birth</label>
                    @error('date_of_birth')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h4>Contact Information</h4>

            <div class="grid-2">
                <div class="form-floating" style="height: 58px;">
                    <input type="tel" name="phone_number" class="form-control phone" id="telephone" autocomplete="off"
                        value="{{ $getEmailData->mobile ? $getEmailData->mobile : ' ' }}" disabled>
                    @error('phone_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="email" class="form-control email" id="floatingInput4" autocomplete="off"
                        value="{{ $getEmailData->email ? $getEmailData->email : ' ' }}" disabled
                        placeholder="name@example.com" name="email">
                    <label for="floatingInput4">Email</label>
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <h4>Patient Location</h4>
            <div class="grid-2">
                <div class="form-floating"> 
                    <input type="text" name="street" class="form-control street" id="floatingInput5" disabled autocomplete="off"
                        placeholder="Street" value="{{ $getEmailData->street ? $getEmailData->street : ' ' }}">
                    <label for="floatingInput5">Street</label>
                    @error('street')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="text" name="city" class="form-control city" id="floatingInput6" disabled autocomplete="off"
                        placeholder="City" value="{{ $getEmailData->city ? $getEmailData->city : ' ' }}">
                    <label for="floatingInput6">City</label>
                    @error('city')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="text" name="state" class="form-control state" id="floatingInput7" disabled autocomplete="off"
                        placeholder="State" value="{{ $getEmailData->state ? $getEmailData->state : ' ' }}">
                    <label for="floatingInput7">State</label>
                    @error('state')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex gap-4 align-items-center">
                    <div class="form-floating w-100">
                        <input type="text" name="zipcode" class="form-control zipcode" disabled id="floatingInput8" autocomplete="off"
                            placeholder="Zipcode" value="{{ $getEmailData->zipcode ? $getEmailData->zipcode : ' ' }}"
                            min="0">
                        <label for="floatingInput8">Zipcode</label>
                        @error('zipcode')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <a href="{{ route('patient.location.on.map') }}" class="primary-empty d-flex gap-2"> <i
                            class="bi bi-geo-alt"></i> Map</a>
                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('patient.profile.edit.view', Crypt::encrypt($getEmailData->id)) }}"
                    class="primary-fill">Edit</a>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection
