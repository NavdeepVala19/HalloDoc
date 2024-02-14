@extends('index')

@section('nav-links')
    <a href="{{ route('provider-dashboard') }}">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="">My Schedule</a>
    <a href="" class="active-link">My Profile</a>
@endsection

@section('content')
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">
                New Request
                {{-- Show the name as per request_type_id and with proper color --}}
            </h1>
            <a href="{{ route('provider-dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>


        <div class="section">
            <form action="" method="POST">
                @csrf
                <h3>Patient Information</h3>

                <div>
                    <p>Confirmation Number</p>
                    {{-- <span> Display Confirmation Number as per the request </span> --}}
                </div>

                <div class="form-floating h-25">
                    <textarea class="form-control " placeholder="injury" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Patient Notes</label>
                </div>

                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="first_name" class="form-control" id="floatingInput"
                            placeholder="First Name">
                        <label for="floatingInput">First Name</label>
                        @error('first_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="last_name" class="form-control" id="floatingInput"
                            placeholder="Last Name">
                        <label for="floatingInput">Last Name</label>
                        @error('last_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="date" class="form-control" id="floatingInput" placeholder="date of birth">
                        <label for="floatingInput">Date Of Birth</label>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <input type="tel" name="phone_number" class="form-control phone" id="telephone"
                            placeholder="Phone Number">
                        @error('phone_number')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <button class="primary-empty"><i class="bi bi-telephone"></i></button>
                    </div>
                    <div class="form-floating ">
                        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Email</label>
                    </div>
                </div>
                <h3>Location Information</h3>
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="region" class="form-control" id="floatingInput" placeholder="region">
                        <label for="floatingInput">Region</label>
                        @error('region')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex align-items-center gap-2 ">
                        <div class="form-floating w-100">
                            <input type="text" name="business_name" class="form-control" id="floatingInput"
                                placeholder="business">
                            <label for="floatingInput">Business Name/Address</label>
                        </div>
                        <button class="primary-empty"><i class="bi bi-geo-alt"></i></button>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="room" class="form-control" id="floatingInput" placeholder="room">
                        <label for="floatingInput">Room #</label>
                    </div>
                </div>

                <div class="text-end">
                    <button class="primary-fill">Assign</button>
                    <button class="primary-fill">View Notes</button>
                    <button class="primary-red">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endsection
