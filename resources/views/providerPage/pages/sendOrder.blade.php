@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/commonpage/viewUploads.css') }}">
@endsection

@section('username')
    {{ !empty(Auth::user()) ? Auth::user()->username : '' }}
@endsection


@section('nav-links')
    <a href="{{ route('provider.dashboard') }}" class="active-link">Dashboard</a>
    <a href="">Invoicing</a>
    <a href="{{ route('provider.scheduling') }}">My Schedule</a>
    <a href="{{ route('provider.profile') }}">My Profile</a>
@endsection

@section('content')
    {{-- Uses Orders table --}}
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">
                Send Order
            </h1>
            <a href="{{ route(
                'provider.status',
                $data->status == 1
                    ? 'new'
                    : ($data->status == 3
                        ? 'pending'
                        : ($data->status == 4 || $data->status == 5
                            ? 'active'
                            : 'conclude')),
            ) }}"
                class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>

        <form action="{{ route('provider.send.order') }}" method="POST" id="providerSendOrderForm">
            @csrf
            <input type="text" name="requestId" value="{{ $requestId }}" hidden>
            <div class="section">
                <div class="grid-2">
                    <div class="form-floating">
                        <select name="profession"
                            class="form-select profession-menu @error('profession') is-invalid @enderror"
                            id="floatingSelect1" aria-label="Floating label select example">
                            <option selected disabled>Open this select menu</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->profession_name }}</option>
                            @endforeach
                        </select>
                        <label for="floatingSelect1">Select Profession</label>
                        @error('profession')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <select name="vendor_id"
                            class="form-select business-menu @error('vendor_id')
                            is-invalid
                        @enderror"
                            id="floatingSelect2" aria-label="Floating label select example">
                            <option selected disabled>Buisness</option>
                        </select>
                        <label for="floatingSelect2">Select Business</label>
                        @error('vendor_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="text" name="business_contact"
                            class="form-control business_contact @error('business_contact') is-invalid @enderror"
                            id="floatingInput3" placeholder="Business Contact" value="{{ old('business_contact') }}" disabled>
                        <label for="floatingInput3">Business Contact</label>
                        @error('business_contact')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="email" name="email" class="form-control email @error('email') is-invalid @enderror"
                            id="floatingInput4" placeholder="email" value="{{ old('email') }}" disabled>
                        <label for="floatingInput4">Email</label>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating ">
                        <input type="number" name="fax_number"
                            class="form-control fax_number @error('fax_number') is-invalid @enderror" id="floatingInput5"
                            placeholder="Fax Number" value="{{ old('fax_number') }}" min="0" disabled>
                        <label for="floatingInput5">Fax Number</label>
                        @error('fax_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-floating">
                    <textarea class="form-control note" name="prescription" placeholder="injury" id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Prescription or Order details</label>
                </div>
                <div class="grid-2">
                    <div class="form-floating">
                        <select name="refills" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                            <option selected disabled>Not Required</option>
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
                    <input type="submit" value="Submit" class="primary-fill" id="providerSendOrderSubmit">
                    <a href="{{ route(
                        'provider.status',
                        $data->status == 1
                            ? 'new'
                            : ($data->status == 3
                                ? 'pending'
                                : ($data->status == 4 || $data->status == 5
                                    ? 'active'
                                    : 'conclude')),
                    ) }}"
                        class="primary-empty">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script defer src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script defer src="{{ asset('assets/validation.js') }}"></script>
@endsection
