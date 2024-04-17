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
                <a href="{{route('patientSite')}}"><i class="bi bi-chevron-left"></i> Back</a>
            </div>
            <h1>Login To Your Account</h1>
        </div>

        @if (Session::has('error'))
        <div class="alert alert-danger popup-message" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif

        @if (Session::has('success'))
        <div class="alert alert-success popup-message" role="alert">
            {{ Session::get('success') }}
        </div>
        @endif


        <div class="form">

            <form action="{{route('patient_logged_in')}}" method="post" id="patientLogin">
                @csrf

                <div class="mb-4 email patientLogin">
                    <i class="bi bi-person-circle person-logo"></i>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email" name="email" value="{{old('email')}}">
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 password patientLogin">
                    <i class="bi bi-eye-fill person-eye"></i>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword1" placeholder="password" name="password">
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="buttons">
                    <button type="submit" class="btn btn-primary">Log In</button>

                    <div class="forgot-pass"> <a href="{{route('forgot_password')}}">Forgot Password?</a> </div>
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