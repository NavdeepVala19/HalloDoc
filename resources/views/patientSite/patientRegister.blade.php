@extends('patientIndex')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientRegister.css') }}">
@endsection

@section('patientContent')
    @if (Session::has('message'))
        <div class="alert alert-danger popup-message" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif
    <!-- main content -->
    <div class="container-fluid main-section patient-register">
        <div class="password-reset">
            <div class="details">
                <h1>Create Account</h1>
            </div>
            <div class="form">
                <form action="{{ route('patientRegistered') }}" method="post" id="patientRegister">
                    @csrf
                    <div class="mb-4 email register">
                        <i class="bi bi-person-circle person-logo"></i>
                        <input type="email" class="form-control  @error('email') is-invalid @enderror"
                            id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email"
                            value="{{ old('email') }}" name="email">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4 password register">
                        <i class="bi bi-eye-fill person-eye"></i>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="exampleInputPassword1" placeholder="Password" name="password">
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 confirm-password register">
                        <i class="bi bi-eye-fill person-eye-two"></i>
                        <input type="password" class="form-control  @error('confirm_password') is-invalid @enderror"
                            id="exampleInputPassword2" placeholder="Confirm Password" name="confirm_password">
                        @error('confirm_password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/patientSite/patientRegister.js') }}"></script>
    <script defer src="{{ asset('assets/patientSite/patientSite.js') }}"></script>
@endsection
