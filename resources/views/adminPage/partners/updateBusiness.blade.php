@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/admin.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/adminPage/partners/partners.css') }}">
@endsection

@section('nav-links')
    <a href="">Dashboard</a>
    <a href="">Provider Location</a>
    <a href="">My Profile</a>
    <a href="">Providers</a>
    <a href="{{ route('admin.partners') }}" class="active-link">Partners</a>
    <a href="">Access</a>
    <a href="">Records</a>
@endsection

@section('content')
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="heading">Update Business</h2>
            <a href="{{ route('admin.partners') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        <div class="section">
            <h4>Submit Information</h4>

            <form action="" method="POST">
                <div class="grid-2">
                    <div class="form-floating ">
                        <input type="text" name="buisness_name" class="form-control" id="floatingInput"
                            placeholder="Business Name">
                        <label for="floatingInput">Business Name</label>
                    </div>
                    <div class="form-floating">
                        <select id="floatingSelect" class="form-select">
                            <option selected>Select Profession</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        <label for="floatingSelect">Profession</label>
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="fax_number" class="form-control" id="floatingInput"
                            placeholder="Fax Number">
                        <label for="floatingInput">Fax Number</label>
                    </div>
                    <input type="tel" name="mobile" class="form-control phone" id="telephone" placeholder="mobile">

                    <div class="form-floating ">
                        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Email</label>
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="business_contact" class="form-control" id="floatingInput"
                            placeholder="Business Contact">
                        <label for="floatingInput">Business Contact</label>
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="Street" class="form-control" id="floatingInput" placeholder="Street">
                        <label for="floatingInput">Street</label>
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="City" class="form-control" id="floatingInput" placeholder="City">
                        <label for="floatingInput">City</label>
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="State" class="form-control" id="floatingInput" placeholder="State">
                        <label for="floatingInput">State</label>
                    </div>

                    <div class="form-floating ">
                        <input type="text" name="zip" class="form-control" id="floatingInput"
                            placeholder="Zip/postal">
                        <label for="floatingInput">Zip/postal</label>
                    </div>
                </div>
                <div class="text-end">
                    <input type="submit" value="Save" class="primary-fill">
                    <a href="{{ route('admin.partners') }}" class="primary-empty">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
