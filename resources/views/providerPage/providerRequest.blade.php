@extends('index')
@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/providerRequest.css') }}">
@endsection

@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
@endsection


@section('nav-links')
    <a href="{{ route('provider.dashboard') }}" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="{{ route('provider.scheduling') }}">My Schedule</a>
    <a href="{{ route('provider.profile') }}">My Profile</a>
@endsection

@section('content')
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">Submit Information</h1>
            <a href="{{ route('provider.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        <div class="section">
            <form action="{{ route('provider.request.data') }}" method="POST" id="providerCreateRequestForm">
                @csrf
                <h3>Patient</h3>
                <div class="mb-4 form-grid">
                    <input type="text" name="request_type_id" value="1" hidden>
                    <div class="form-floating ">
                        <input type="text" name="first_name"
                            class="form-control @error('first_name') is-invalid @enderror" id="floatingInput1"
                            placeholder="First Name" value="{{ old('first_name') }}">
                        <label for="floatingInput1">First Name</label>
                        @error('first_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                            id="floatingInput2" placeholder="Last Name" value="{{ old('last_name') }}">
                        <label for="floatingInput2">Last Name</label>
                        @error('last_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <div class="form-floating" style="height: 58px;">
                            <input type="tel" name="phone_number"
                                class="form-control phone @error('phone_number') is-invalid @enderror" id="telephone"
                                placeholder="Phone Number" value="{{ old('phone_number') }}">
                        </div>
                        @error('phone_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            id="floatingInput3" placeholder="name@example.com" value="{{ old('email') }}">
                        <label for="floatingInput3">Email address</label>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="date" name="dob" class="form-control" id="floatingInput4"
                            placeholder="date of birth" value="{{ old('dob') }}">
                        <label for="floatingInput4">Date Of Birth(Optional)</label>
                    </div>
                </div>
                <h3>Location</h3>
                <div class="mb-4 form-grid">
                    <div class="form-floating">
                        <input type="text" name="street" class="form-control @error('street') is-invalid @enderror"
                            id="floatingInput5" placeholder="Street" value="{{ old('street') }}">
                        <label for="floatingInput5">Street</label>
                        @error('street')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                            id="floatingInput6" placeholder="City" value="{{ old('city') }}">
                        <label for="floatingInput6">City</label>
                        @error('city')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                            id="floatingInput7" placeholder="State" value="{{ old('state') }}">
                        <label for="floatingInput7">State</label>
                        @error('state')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating">
                        <input type="number" name="zip" class="form-control" id="floatingInput" placeholder="zip code"
                            value="{{ old('zip') }}">
                        <label for="floatingInput">Zip Code (Optional)</label>
                    </div>
                    <div class="form-floating">
                        <input type="number" name="room" class="form-control" id="floatingInput" placeholder="Room"
                            value="{{ old('room') }}">
                        <label for="floatingInput">Room # (Optional)</label>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="button" class="primary-empty me-3 ">Verify</button>
                    <button type="button" class="primary-empty"><i class="bi bi-geo-alt"></i> Map</button>
                </div>

                <h3>Notes</h3>
                <div class="mb-4">
                    <div class="form-floating">
                        <textarea class="form-control note" name='note' placeholder="notes" id="floatingTextarea2">{{ old('note') }}</textarea>
                        <label for="floatingTextarea2">Physician Notes (optional)</label>
                    </div>
                </div>

                <div class="mb-4 d-flex justify-content-end gap-3 ">
                    <input type="submit" value='Save' class="primary-fill" id="providerSaveButton">
                    <a href="{{ route('provider.dashboard') }}" class="primary-empty">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
{{-- @section('script')
    <script src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/validation.js') }}"></script>
@endsection --}}
