@extends('patientIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientRegister.css') }}">

@endsection

@section('patientContent')

   @if (session('error'))
        <div class="alert alert-danger popup-message ">
            <span>
                {{ session('error') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif


<!-- main content -->
<div class="container-fluid main-section w-45 patient-update-password">
    <div class="password-reset">
        <div class="details">
            <h1>Reset Password</h1>
        </div>
        <div class="form">
            <form action="{{ route('reset.password.post')}}" method="post" id="patientRegister">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-4 password register">
                    <i class="bi bi-eye-fill person-eye"></i>
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="exampleInputPassword1" placeholder="New Password" name="new_password">
                    @error('new_password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 confirm-password register">
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
<script defer src="{{ asset('assets/patientSite/patientRegister.js') }}"></script>
<script defer src="{{ asset('assets/patientSite/patientSite.js') }}"></script>
@endsection