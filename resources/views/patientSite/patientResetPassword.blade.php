@extends('patientIndex')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientResetPassword.css') }}">
@endsection

@section('patientContent')
    @include('loading')
    <!-- main content -->
    <div class="container-fluid main-section patient-reset-password">
        <div class="reset-password">
            <div class="details">
                <div class="main-content">
                    <a href="{{ route('patient.login.view') }}"><i class="bi bi-chevron-left"></i> Back</a>
                </div>
                <h1>Reset Your Password</h1>
            </div>
            <div class="form">
                <form action="{{ route('forgot.password') }}" method="post" id="patientLogin">
                    @csrf
                    <div class="mb-4 username patientLogin">
                        <i class="bi bi-person-circle person-logo"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder=" email" autocomplete="off"
                            name="email">
                        @error('email')
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
                    </div>
                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                        <div class="back-login"> <a href="{{ route('patient.login.view') }}"> <i class="bi bi-chevron-left"></i>
                                Back To Login</a> </div>
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
