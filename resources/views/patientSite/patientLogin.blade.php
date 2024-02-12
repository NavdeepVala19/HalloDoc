@extends('patientIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientLogin.css') }}">

@endsection


@section('patientContent')

<!-- header section -->
<div class="container-fluid main-container">

    <nav class="navbar">
        <div class="logo-img">
            <a href="">
                <img class="logo img-fluid" src="{{ URL::asset('/assets/logo.png') }}" alt="">
            </a>
        </div>
        <div class="d-flex align-items-center justify-content-end  gap-3">
            <a href="" class="primary-empty toggle-mode">
                <i class="bi bi-moon"></i>
            </a>
        </div>

    </nav>

    <!-- main content -->
    <div class="main-content">
        <a href=""><i class="bi bi-chevron-left"></i> Back</a></div>

        <h1>Login To Your Account</h1>

    </div>

</div>

@endsection