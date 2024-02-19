@extends('patientIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientRegister.css') }}">

@endsection


@section('patientContent')



<!-- main content -->

<div class="container-fluid main-section">

    <div class="main-container">

        <div class="details">
            <h1>Reset Password</h1>
        </div>
        <div class="form">

            <form action="{{ route('reset.password.post')}}" method="post">
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