@extends('patientIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientResetPassword.css') }}">

@endsection


@section('patientContent')



<!-- main content -->

<div class="container-fluid main-section">

    <div class="main-container">

        <div class="details">
            <div class="main-content">
                <a href=""><i class="bi bi-chevron-left"></i> Back</a>
            </div>
            <h1>Reset Your Password</h1>
        </div>


        <div class="form">

            <form>

                </div>
                <div class="mb-4 username">
                    <i class="bi bi-person-circle person-logo"></i>
                    <input type="text" class="form-control " placeholder=" Username">
                </div>

                <div class="buttons">
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                    <div class="back-login"> <a href=""> <i class="bi bi-chevron-left"></i> Back To Login</a> </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection