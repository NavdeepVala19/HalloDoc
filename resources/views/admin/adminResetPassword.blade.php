@extends('adminIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/admin/adminResetPassword.css') }}">

@endsection


@section('adminContent')

<div class="container-fluid main-section mt-5">

    <div class="main-container">

        <div class="details">
            <h1>Reset Your Password</h1>
        </div>

        @if (Session::has('message'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('message') }}
        </div>
        @endif


        <div class="form">
            <form action="{{route('adminForgotPassword')}}" method="post">
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
                    <div class="back-login"> <a href="{{route('adminLogin')}}"> <i class="bi bi-chevron-left"></i> Back To Login</a> </div>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection