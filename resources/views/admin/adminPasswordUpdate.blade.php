@extends('adminIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/admin/adminResetPassword.css') }}">

@endsection


@section('adminContent')
<!-- main content -->

<div class="container-fluid main-section mt-5">

    <div class="main-container2">

        <div class="details">
            <h1>Reset Password</h1>
        </div>

        @if (Session::has('message'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('message') }}
        </div>
        @endif

        <div class="form">
            <form action="{{route('updatePasswordPost')}}" method="post" id="adminLogin">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-4 password" id="adminLogin">
                    <i class="bi bi-eye-fill person-eye"></i>
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="exampleInputPassword1" placeholder="New Password" name="new_password">
                    @error('new_password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 confirm-passwor" id="adminLogin">
                    <i class="bi bi-eye-fill person-eye-two"></i>
                    <input type="password" class="form-control @error('confirm_password') is-invalid @enderror" id="exampleInputPassword2" placeholder="Confirm Password" name="confirm_password">
                    @error('confirm_password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="buttons">
                    <button type="submit" class="btn btn-primary">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection



@section('script')
<script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
<script defer src="{{ URL::asset('assets/admin/adminLogin.js') }}"></script>
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection