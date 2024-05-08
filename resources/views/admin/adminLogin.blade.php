@extends('adminIndex')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/admin/adminLogin.css') }}">
@endsection


@section('adminContent')
    <!-- main content -->
    <!-- login credentials input -->

    <div class="main-section mt-5">
        <div class="w-50">
            <div class="details">
                <h2>Login To Your Account</h2>
            </div>

            <div class="form">
                <form action="{{ route('admin.login') }}" method="post" id="adminLogin">
                    @csrf
                    <input type="hidden" name="latitude" id="lat">
                    <input type="hidden" name="longitude" id="lng">
                    <div class="mb-4 email" id="adminLog">
                        <i class="bi bi-person-circle person-logo"></i>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email" name="email"
                            autocomplete="off" value="{{ old('email') }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 password" id="adminLog">
                        <i class="bi bi-eye-fill person-eye"></i>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="exampleInputPassword1" placeholder="password" name="password">
                        @error('password')
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
                            <div class="text-success error-message text-center" role="alert">
                                <span>
                                    {{ Session::get('message') }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">Log In</button>
                        <div class="forgot-pass"> <a href="{{ route('admin.reset.password.view') }}">Forgot Password?</a> </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ URL::asset('assets/admin/adminLogin.js') }}"></script>
    <!-- <script defer src="{{ URL::asset('assets/adminProvider/providerMapLocation.js') }}"></script> -->
    <script defer src="{{ URL::asset('assets/patientSite/patientSite.js') }}"></script>
@endsection
