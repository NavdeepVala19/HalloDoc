@extends('index')
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/createRequest.css') }}">
@endsection

@section('nav-links')
    <a href="" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="">My Schedule</a>
    <a href="">My Profile</a>
@endsection

@section('content')
    <div class="container form-container ">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">Submit Information</h1>
            <a href="{{ route('provider-dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        <div class="section">
            <form action="/provider-request" method="POST">
                @csrf
                <h3>Patient</h3>
                <div class="mb-4 form-grid">
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

                    <input type="tel" name="phone_number" class="form-control phone" id="telephone"
                        placeholder="Phone Number">
                    @error('phone_number')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="form-floating ">
                        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Email address</label>
                    </div>
                    <div class="form-floating ">
                        <input type="date" class="form-control" id="floatingInput" placeholder="date of birth">
                        <label for="floatingInput">Date Of Birth(Optional)</label>
                    </div>
                </div>
                <h3>Location</h3>
                <div class="mb-4 form-grid">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="floatingInput" placeholder="Street">
                        <label for="floatingInput">Street</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="floatingInput" placeholder="City">
                        <label for="floatingInput">City</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control phone" id="floatingInput" placeholder="State">
                        <label for="floatingInput">State</label>
                    </div>
                    <div class="form-floating">
                        <input type="email" class="form-control" id="floatingInput" placeholder="zip code">
                        <label for="floatingInput">Zip Code (Optional)</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="floatingInput" placeholder="Room">
                        <label for="floatingInput">Room # (Optional)</label>
                    </div>
                </div>
                <div class="mb-3">
                    <button class="primary-empty me-3 ">Verify</button>
                    <button class="primary-empty"><i class="bi bi-geo-alt"></i> Map</button>
                </div>

                <h3>Notes</h3>
                <div class="mb-4">
                    <div class="form-floating">
                        <textarea class="form-control note" placeholder="notes" id="floatingTextarea2"></textarea>
                        <label for="floatingTextarea2">Physician Notes (optional)</label>
                    </div>
                </div>

                <div class="mb-4 d-flex justify-content-end gap-3 ">
                    <input type="submit" value='Save' class="primary-fill">
                    <a href="{{ route('provider-dashboard') }}" class="primary-empty">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
