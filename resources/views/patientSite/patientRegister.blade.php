@extends('patientIndex')

@section('css')

<link rel="stylesheet" href="{{ URL::asset('assets/patientSite/patientRegister.css') }}">

@endsection


@section('patientContent')



<!-- main content -->

<div class="container-fluid main-section">

    <div class="main-container">

        <div class="details">
            <h1>Create Account</h1>
        </div>


        <div class="form">

            <form>

                </div>
                <div class="mb-4 email">
                    <i class="bi bi-person-circle person-logo"></i>
                    <input type="email" class="form-control " id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email">


                </div>
                <div class="mb-4 password">
                    <i class="bi bi-eye-fill person-eye"></i>
                    <input type="password" class="form-control " id="exampleInputPassword1" placeholder="Password">
                </div>

                <div class="mb-3 confirm-password">
                    <i class="bi bi-eye-fill person-eye-two"></i>
                    <input type="password" class="form-control " id="exampleInputPassword2" placeholder="Confrim Password">
                </div>

                <div class="buttons">
                    <button type="submit" class="btn btn-primary">Create</button>

                </div>
            </form>
        </div>
    </div>
</div>


@endsection