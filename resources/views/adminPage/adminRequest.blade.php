@extends('index')
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/adminRequest.css') }}">
@endsection

@section('nav-links')
    <a href="" class="active-link">Dashboard</a>
    <a href="">Provider Location</a>
    <a href="">My Profile</a>
    <div class="dropdown record-navigation">
        <button class="record-btn " type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Providers
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="">Provider</a></li>
            <li><a class="dropdown-item" href="">Scheduling</a></li>
            <li><a class="dropdown-item" href="">Invoicing</a></li>
        </ul>
    </div>
    <a href="">Partners</a>
    <a href="">Access</a>
    <a href="">Records</a>
@endsection

@section('content')
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">Submit Information</h1>
            <a href="{{ route('admin.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

    <div class="section">
        <form action="{{ route('adminCreatedPatientRequest') }}" method="POST" id="patientProfileEditForm">
            @csrf
            <h3>Patient</h3>
            <div class="mb-4 form-grid">
                <input type="text" name="request_type" value="1" hidden>

                <div class="form-floating ">
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" id="floatingInput" placeholder="First Name" value="{{ old('first_name') }}">
                    <label for="floatingInput">First Name</label>
                    @error('first_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <span id="errorMsg"></span>
                </div>
                <div class="form-floating ">
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" id="floatingInput" placeholder="Last Name" value="{{ old('last_name') }}">
                    <label for="floatingInput">Last Name</label>
                    @error('last_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <div class="form-floating" style="height: 58px;">
                        <input type="tel" name="phone_number" class="form-control phone @error('phone_number') is-invalid @enderror" id="telephone" placeholder="Phone Number" value="{{ old('phone_number') }}">
                    </div>
                    @error('phone_number')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating ">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="floatingInput" placeholder="name@example.com" value="{{ old('email') }}">
                    <label for="floatingInput">Email address</label>
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating ">
                    <input type="date" class="form-control" id="floatingInput" placeholder="date of birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                    <label for="floatingInput">Date Of Birth(Optional)</label>
                </div>
            </div>
            <h3>Location</h3>
            <div class="mb-4 form-grid">
                <div class="form-floating">
                    <input type="text" name="street" class="form-control @error('street') is-invalid @enderror" id="floatingInput" placeholder="Street" value="{{ old('street') }}">
                    <label for="floatingInput">Street</label>
                    @error('street')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" id="floatingInput" placeholder="City" value="{{ old('city') }}">
                    <label for="floatingInput">City</label>
                    @error('city')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" id="floatingInput" placeholder="State" value="{{ old('state') }}">
                    <label for="floatingInput">State</label>
                    @error('state')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="number" name="zip" class="form-control" id="floatingInput" placeholder="zip code" value="{{ old('zip') }}">
                    <label for="floatingInput">Zip Code (Optional)</label>
                </div>
                <div class="form-floating">
                    <input type="number" name="room" class="form-control" id="floatingInput" placeholder="Room" value="{{ old('room') }}">
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
                    <textarea class="form-control note" name='adminNote' placeholder="notes" id="floatingTextarea2" value="{{ old('adminNote') }}"></textarea>
                    <label for="floatingTextarea2">Admin Notes (optional)</label>
                </div>

                <div class="mb-4 d-flex justify-content-end gap-3 ">
                    <input type="submit" value='Save' class="primary-fill">
                    <a href="{{ route('provider.dashboard') }}" class="primary-empty">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('script')
<script defer src="{{ asset('assets/validation/jquery.validate.min.js')}}"></script>
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection
