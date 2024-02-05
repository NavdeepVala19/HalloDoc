@extends('index')

@section('nav-links')
    <a href="">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="">My Schedule</a>
    <a href="">My Profile</a>
@endsection

@section('content')
    {{-- Main Content of the Page --}}
    <div class="container">
        <div class="mb-5">
            <div class="case active p-1 ps-3 d-flex flex-column justify-content-between">
                <span>
                    logo NEW
                </span>
                <span>
                    1{{-- New Cases --}}
                </span>
            </div>`
        </div>
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <h3>Patients </h3> <span>(New)</span>
            </div>
            <div>
                <button class="primary-btn"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                        <path
                            d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
                    </svg>
                    Send Link</button>
                <button class="primary-btn">Create Requests</button>
            </div>
        </div>
        <div>
            <input type="search" name="search_patient" id="">
        </div>
    </div>
@endsection
