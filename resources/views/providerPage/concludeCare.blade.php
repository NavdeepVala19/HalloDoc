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
                Conclude Care
            </h1>
            <a href="{{ route('provider.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>


        <div class="section">
            <p>Patient Name</p>
            {{-- <span class="patient-name">{{ $data->first_name }}</span> --}}

            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3>Encounter Forms</h3>
                <div>
                    <input type="file" name="document" id="document" hidden>
                    <label for="document" class="primary-empty"><i class="bi bi-cloud-upload"></i> Upload</label>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover ">
                    <thead class="table-secondary">
                        <tr>
                            <th>Documents</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
