@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/commonpage/viewUploads.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('provider.dashboard') }}" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="">My Schedule</a>
    <a href="">My Profile</a>
@endsection

@section('content')
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">
                Close Case
            </h1>
            <a href="{{ route('provider.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        <form action="" method="POST">
            <div class="section">
                <div class="d-flex align-items-center justify-content-between mb-3">

                    <div>
                        <p>Patient Name</p>
                        <span class="patient-name">PatientName Bold with Blue color</span>
                        <span class="confirmation-number">(Confirmation Number)</span>
                    </div>

                    <div>
                        <button class="primary-empty">Create Invoice Through Quickbooks</button>
                    </div>
                </div>
                <h3>
                    Documents
                </h3>


                <div class="table-responsive">
                    <table class="table table-hover ">
                        <thead class="table-secondary">
                            <tr>
                                <th></th>
                                <th>Upload Date <i class="bi bi-arrow-up"></i></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="bi bi-filetype-doc doc-symbol"></i></td>
                                <td>Date</td>
                                <td> <button class="primary-empty"><i class="bi bi-cloud-download"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Patient</h3>
                <div class="mb-4 grid-2">
                    <input type="text" name="request_type_id" value="1" hidden>
                    <div class="form-floating ">
                        <input type="text" name="first_name"
                            class="form-control @error('first_name') is-invalid @enderror" id="floatingInput"
                            placeholder="First Name">
                        <label for="floatingInput">First Name</label>
                        @error('first_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                            id="floatingInput" placeholder="Last Name">
                        <label for="floatingInput">Last Name</label>
                        @error('last_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating ">
                        <input type="date" class="form-control" id="floatingInput" placeholder="date of birth">
                        <label for="floatingInput">Date Of Birth</label>
                    </div>

                    <div class="d-flex gap-2 align-items-center">

                        <input type="tel" name="phone_number"
                            class="form-control phone @error('last_name') is-invalid @enderror" id="telephone"
                            placeholder="Phone Number">
                        @error('phone_number')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <button class="primary-empty"><i class="bi bi-telephone"></i></button>
                    </div>
                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Email address</label>
                        @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="text-end">
                    <input type="submit" value="Edit" class="primary-fill">
                    <button class="primary-empty">Close Case</button>
                </div>
            </div>
        </form>
    </div>
@endsection
