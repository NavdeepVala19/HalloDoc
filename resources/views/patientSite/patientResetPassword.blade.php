@extends('patientIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientResetPassword.css') }}">

@endsection


@section('patientContent')



<!-- main content -->

<div class="container-fluid main-section">

    <div class="reset-password">

        <div class="details">
            <div class="main-content">
                <a href="{{route('loginScreen')}}"><i class="bi bi-chevron-left"></i> Back</a>
            </div>
            <h1>Reset Your Password</h1>
        </div>

        @if (Session::has('message'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('message') }}
        </div>
        @endif

        <div class="form">
            <form action="{{route('forgot.password')}}" method="post">
                @csrf
                <div class="mb-4 username">
                    <i class="bi bi-person-circle person-logo"></i>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder=" email" name="email">
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="buttons">
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                    <div class="back-login"> <a href="{{route('loginScreen')}}"> <i class="bi bi-chevron-left"></i> Back
                            To
                            Login</a> </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection