@extends('adminIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/admin/adminResetPassword.css') }}">

@endsection


@section('adminContent')

<div class="container-fluid main-section mt-5">

    <div class="main-container1">

        <div class="details">
            <h1>Reset Your Password</h1>
        </div>

        @if (Session::has('message'))
        <div class="alert alert-success popup-message" role="alert">
            {{ Session::get('message') }}
        </div>
        @endif

        @if (Session::has('error'))
        <div class="alert alert-danger popup-message" role="alert">
            {{ Session::get('error') }}
        </div>
        @endif

        <div class="form">
            <form action="{{route('adminForgotPassword')}}" method="post" id="adminLogin">
                @csrf
                <div class="mb-4 username" id="adminLogin">
                    <i class="bi bi-person-circle person-logo"></i>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder=" email" name="email">
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="buttons">
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                    <div class="back-login"> <a href="{{route('adminLogin')}}"> <i class="bi bi-chevron-left"></i> Back To Login</a> </div>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection


@section('script')
<script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
<script defer src="{{ URL::asset('assets/admin/adminLogin.js') }}"></script>
@endsection