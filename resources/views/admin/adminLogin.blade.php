@extends('adminIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/admin/adminLogin.css') }}">

@endsection


@section('adminContent')

<!-- main content -->
<!-- login credentials input -->

<div class=" main-section mt-5">

    <div class="w-50">

        <div class="details">
            <h2>Login To Your Account</h2>
        </div>

        @if (Session::has('message'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('message') }}
        </div>
        @endif


        <div class="form">

            <form action="{{route('adminLoggedIn')}}" method="post">
                @csrf
        </div>
        <div class="mb-4 email">
            <i class="bi bi-person-circle person-logo"></i>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email" name="email">
            @error('email')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3 password">
            <i class="bi bi-eye-fill person-eye"></i>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword1" placeholder="password" name="password">
            @error('password')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="buttons">
            <button type="submit" class="btn btn-primary">Log In</button>

            <div class="forgot-pass"> <a href="{{route('adminresetpassword')}}">Forgot Password?</a> </div>
        </div>
        </form>
    </div>
</div>



@endsection


@section('script')
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection