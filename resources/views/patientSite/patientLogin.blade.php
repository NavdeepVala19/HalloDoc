@extends('patientIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientLogin.css') }}">

@endsection


@section('patientContent')



<!-- main content -->

<div class="container-fluid main-section">

    <div class="main-container">

        <div class="details">
            <div class="main-content">
                <a href=""><i class="bi bi-chevron-left"></i> Back</a>
            </div>
            <h1>Login To Your Account</h1>
        </div>


        <div class="form">

            <form>

                </div>
                <div class="mb-4 email">
                    <i class="bi bi-person-circle person-logo"></i>
                    <input type="email" class="form-control " id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email">


                </div>
                <div class="mb-3 password">
                    <i class="bi bi-eye-fill person-eye"></i>
                    <input type="password" class="form-control " id="exampleInputPassword1" placeholder="password">
                </div>

                <div class="buttons">
                    <button type="submit" class="btn btn-primary">Log In</button>

                    <div class="forgot-pass"> <a href="">Forgot Password?</a> </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection