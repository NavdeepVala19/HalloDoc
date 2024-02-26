@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/commonpage/viewUploads.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('provider-dashboard') }}" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="">My Schedule</a>
    <a href="">My Profile</a>
@endsection

@section('content')
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">
                Send Order
            </h1>
            <a href="{{ route('provider-dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        <form action="" method="POST">
            @csrf
            <input type="hidden" name="request_id" value="{{ $id }}">
            <div class="section">
                <div class="grid-2">
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                            <option value="4">Four</option>
                            <option value="5">Five</option>
                        </select>
                        <label for="floatingSelect">Select Profession</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="business" class="form-control" id="floatingInput"
                            placeholder="Business">
                        <label for="floatingInput">Business</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="business_contact" class="form-control" id="floatingInput"
                            placeholder="Business Contact">
                        <label for="floatingInput">Business Contact</label>
                    </div>
                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control" id="floatingInput" placeholder="email">
                        <label for="floatingInput">Email</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="fax_number" class="form-control" id="floatingInput"
                            placeholder="Fax Number">
                        <label for="floatingInput">Fax Number</label>
                    </div>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" name="prescription" placeholder="injury" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Prescription or Order details</label>
                </div>

                <div class="grid-2">

                    <div class="form-floating">
                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                            <option selected>Not Required</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <label for="floatingSelect">Number of Refil</label>
                    </div>
                </div>

                <div class="text-end">
                    <input type="submit" value="Submit" class="primary-fill">
                    <button class="primary-empty">Cancel</button>
                </div>
            </div>
        </form>
    </div>
@endsection
