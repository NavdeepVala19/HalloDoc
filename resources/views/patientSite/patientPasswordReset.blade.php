@extends('patientIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientRegister.css') }}">

@endsection


@section('patientContent')



<!-- main content -->

<div class="container-fluid main-section w-45">

    <div class="password-reset">

        <div class="details">
            <h1>Reset Password</h1>
        </div>
        <div class="form">

            <form action="{{ route('reset.password.post')}}" method="post">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-4 password">
                    <i class="bi bi-eye-fill person-eye"></i>
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="exampleInputPassword1" placeholder="New Password" name="new_password">
                    @error('new_password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 confirm-password">
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
<script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection