@extends('adminIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/admin/adminResetPassword.css') }}">

@endsection


@section('adminContent')
<!-- main content -->

<div class="container-fluid main-section mt-5">

    <div class="main-container">

        <div class="details">
            <h1>Reset Password</h1>
        </div>

        @if (Session::has('message'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('message') }}
        </div>
        @endif

        <div class="form">

            <form action="{{route('updatePasswordPost')}}" method="post">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="mb-4 password">
                    <i class="bi bi-eye-fill person-eye"></i>
                    <input type="password" class="form-control " id="exampleInputPassword1" placeholder="New Password"
                        name="new_password">
                </div>

                <div class="mb-3 confirm-password">
                    <i class="bi bi-eye-fill person-eye-two"></i>
                    <input type="password" class="form-control " id="exampleInputPassword2"
                        placeholder="Confirm Password" name="confirm_password">
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