@extends('adminIndex')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/adminResetPassword.css') }}">
@endsection

@section('adminContent')
    @include('loading');

    <div class="container-fluid main-section mt-5">
        <div class="main-container1">
            <div class="details">
                <h1>Reset Your Password</h1>
            </div>
            <div class="form">
                <form action="{{ route('admin.forgot.password') }}" method="post" id="adminLogin">
                    @csrf
                    <div class="mb-4 username" id="adminLog">
                        <i class="bi bi-person-circle person-logo"></i>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder=" email" autocomplete="off"
                            name="email">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        @if (Session::has('error'))
                            <div class="text-danger error-message text-center" role="alert">
                                <span>
                                    {{ Session::get('error') }}
                                </span>
                            </div>
                        @endif
                        @if (Session::has('message'))
                            <div class="text-danger error-message text-center" role="alert">
                                <span>
                                    {{ Session::get('message') }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                        <div class="back-login"> <a href="{{ route('login') }}"> <i class="bi bi-chevron-left"></i>
                                Back To Login</a> </div>
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
