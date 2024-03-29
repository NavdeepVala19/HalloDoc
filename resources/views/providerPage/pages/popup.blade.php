@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/commonpage/popup.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('provider.dashboard') }}" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="">My Schedule</a>
    <a href="">My Profile</a>
@endsection

@section('content')
  
    {{-- Transfer Request Pop-up --}}
    {{-- This pop-up will open when admin clicks on “Transfer” link from Actions menu. From the pending state, admin
can transfer assigned request to another physician. --}}
    {{-- <div class="pop-up transfer-case">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>Transfer Request</span>
            <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
        </div>
        <p class="m-2">To transfer this request, search and select another Physician</p>
        <div>
            <div class="form-floating">
                <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                    <option selected>Regions</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
                <label for="floatingSelect">Narrow Search by Region</label>
            </div>
            <div class="form-floating">
                <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                    <option selected>Select Physician</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
                <label for="floatingSelect">Select Physician</label>
            </div>
            <div class="form-floating">
                <textarea class="form-control" placeholder="Description" id="floatingTextarea2"></textarea>
                <label for="floatingTextarea2">Description</label>
            </div>
        </div>
        <div class="p-2 d-flex align-items-center justify-content-end gap-2">
            <button class="primary-fill cancel-case">Confirm</button>
            <button class="primary-empty hide-popup-btn">Cancel</button>
        </div>
    </div> --}}


    {{-- Clear Case Pop-up --}}
    {{-- This pop-up will open when admin clicks on “Clear case” link from Actions menu. From the pending and close
state, admin can clear the case from the action grid. --}}
    {{-- <div class=" pop-up clear-case d-flex flex-column align-items-center justify-content-center p-4">
        <i class="bi bi-exclamation-circle-fill warning-icon"></i>
        <div>
            <h3 class="text-center">Confirmation for clear case</h3>
            <p class="text-center">Are you sure, you want to clear this request? Once clear, you are not able to see this
                request!
            </p>
        </div>
        <div>
            <button class="primary-fill">Clear</button>
            <button class="primary-empty hide-popup-btn">Cancel</button>
        </div>
    </div> --}}



    {{-- Send Agreement Pop-up --}}
    {{-- This pop-up will open when admin/provider will click on “Send agreement” link from Actions menu. From the
pending state, providers need to send an agreement link to patients. --}}
    {{-- <div class="pop-up send-agreement">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span>Send Agreement</span>
            <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="p-3">
            <div>
                <span>Show the name and color of request (i.e. patinet, family, business, concierge)</span>
                <p class="m-2">To send Agreement please make sure you are updating the correct contact information below
                    for
                    the
                    responsible party.
                </p>
            </div>
            <div>
                <div class="form-floating ">
                    <input type="text" name="phone_number" class="form-control" id="floatingInput"
                        placeholder="Phone Number">
                    <label for="floatingInput">Phone Number</label>
                </div>
                <div class="form-floating ">
                    <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Email</label>
                </div>
            </div>
        </div>
        <div class="p-2 d-flex align-items-center justify-content-end gap-2">
            <button class="primary-fill send-case">Send</button>
            <button class="primary-empty hide-popup-btn">Cancel</button>
        </div>
    </div> --}}
@endsection
