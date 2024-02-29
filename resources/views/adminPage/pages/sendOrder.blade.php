@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/commonpage/viewUploads.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}" class="active-link">Dashboard</a>
    <a href="">Provider Location</a>
    <a href="">My Profile</a>
    <a href="">Providers</a>
    <a href="{{ route('admin.partners') }}">Partners</a>
    <a href="">Access</a>
    <a href="">Records</a>
@endsection

@section('content')
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">
                Send Order
            </h1>
            <a href="{{ route('admin.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        <form action="{{ route('admin.send.order') }}" method="POST">
            @csrf
            <input type="text" name="requestId" value="{{ $id }}" hidden>
            <div class="section">
                <div class="grid-2">
                    <div class="form-floating">
                        <select class="form-select profession-menu" id="floatingSelect"
                            aria-label="Floating label select example">
                            <option selected>Open this select menu</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->profession_name }}</option>
                            @endforeach
                        </select>
                        <label for="floatingSelect">Select Profession</label>
                    </div>
                    <div class="form-floating ">
                        <select name="vendor_id" class="form-select business-menu" id="floatingSelect"
                            aria-label="Floating label select example">
                            <option selected>Buisness</option>
                        </select>
                        <label for="floatingSelect">Select Business</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="business_contact" class="form-control business_contact" id="floatingInput"
                            placeholder="Business Contact">
                        <label for="floatingInput">Business Contact</label>
                    </div>
                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control email" id="floatingInput" placeholder="email">
                        <label for="floatingInput">Email</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="fax_number" class="form-control fax_number" id="floatingInput"
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
                        <select class="form-select" name="refills" id="floatingSelect"
                            aria-label="Floating label select example">
                            <option selected>Not Required</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                            <option value="4">Four</option>
                            <option value="5">Five</option>
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
