@extends('patientIndex')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientLogin.css') }}">
@endsection

@section('patientContent')
    <!-- main content -->
    <div class="container-fluid main-section patient_login">
        <div class="patient-login">
            <div class="details">
                <div class="main-content">
                    <a href="{{ route('patient.home_page') }}"><i class="bi bi-chevron-left"></i> Back</a>
                </div>
                <h1>Login To Your Account</h1>
            </div>
            <div class="form">
                <form action="{{ route('patient.login') }}" method="post" id="patientLogin">
                    @csrf
                    <div class="mb-4 email patientLogin">
                        <i class="bi bi-person-circle person-logo"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email" name="email"
                            autocomplete="off" value="{{ old('email') }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 password patientLogin">
                        <i class="bi bi-eye-fill person-eye"></i>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="exampleInputPassword1" placeholder="password" name="password">
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        @if (Session::has('error'))
                            <div class="text-danger error-message text-center" role="alert">
                                <span>
                                    {{ Session::get('error') }}
                                </span>
                            </div>
                        @endif
                        @if (Session::has('message'))
                            <div class="text-success error-message text-center" role="alert">
                                <span>
                                    {{ Session::get('message') }}
                                </span>
                            </div>
                        @endif
                        @if (Session::has('success'))
                            <div class="text-success error-message text-center" role="alert">
                                <span>
                                    {{ Session::get('success') }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">Log In</button>
                        <div class="forgot-pass"> <a href="{{ route('patient.forgot.password') }}">Forgot Password?</a> </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/patientSite/patientSite.js') }}"></script>
    <script defer src="{{ asset('assets/patientSite/patientLogin.js') }}"></script>
@endsection
