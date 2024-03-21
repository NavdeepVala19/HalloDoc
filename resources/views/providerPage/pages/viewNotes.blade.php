@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/providerProfile.css') }}">
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
                Notes
            </h1>
            <a href="{{ route('provider.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>


        <div class="section">
            <div class="grid-2">
                <div class="d-flex align-items-center gap-4">
                    <i class="bi bi-arrow-down-up"></i>
                    <div>
                        <h2>Transfer Notes</h2>
                        <span>Who transfered note and when</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <i class="bi bi-person"></i>
                    <div>
                        <h2>Physician Notes</h2>
                        <span>Who transfered note and when</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <i class="bi bi-person-check"></i>
                    <div>
                        <h2>Admin Notes</h2>
                        <span>Who transfered note and when</span>
                    </div>
                </div>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" placeholder="injury" id="floatingTextarea2"></textarea>
                <label for="floatingTextarea2">Additional Notes</label>
            </div>
            <div class="text-end">
                <button class="primary-fill">Save Changes</button>
            </div>
        </div>
    </div>
@endsection
