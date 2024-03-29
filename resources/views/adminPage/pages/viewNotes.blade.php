@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/providerProfile.css') }}">
@endsection

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}" class="active-link">Dashboard</a>
    <a href="{{ route('providerLocation') }}">Provider Location</a>
    <a href="">My Profile</a>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Providers
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('adminProvidersInfo') }}">Provider</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.scheduling') }}">Scheduling</a></li>
            <li><a class="dropdown-item" href="">Invoicing</a></li>
        </ul>
    </div>
    <a href="{{ route('admin.partners') }}">Partners</a>
    <a href="{{ route('admin.access.view') }}">Access</a>
    <div class="dropdown record-navigation">
        <button class="record-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Records
        </button>
        <ul class="dropdown-menu records-menu">
            <li><a class="dropdown-item" href="{{ route('admin.search.records.view') }}">Search Records</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.email.records.view') }}">Email Logs</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.sms.records.view') }}">SMS Logs</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.patient.records.view') }}">Patient Records</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.block.history.view') }}">Blocked History</a></li>
        </ul>
    </div>
@endsection

@section('content')
    {{-- Note added Successfully --}}
    @if (session('adminNoteAdded'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('adminNoteAdded') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">
                Notes
            </h1>
            <a href="{{ route('admin.dashboard') }}" class="primary-empty"><i class="bi bi-chevron-left"></i> Back</a>
        </div>
        <div class="section">
            <div class="grid-2 notes-section">
                <div class="d-flex align-items-center gap-4">
                    <i class="bi bi-arrow-down-up notes-logo"></i>
                    <div>
                        <h2>Transfer Notes</h2>
                        @if ($adminAssignedCase)
                            <span>Admin transferred to Dr. {{ $adminAssignedCase->transferedPhysician->first_name }}
                                {{ $adminAssignedCase->transferedPhysician->last_name }} on
                                {{ $adminAssignedCase->created_at->format('d-m-Y') }}
                                at {{ $adminAssignedCase->created_at->format('H:i:s') }} :
                                {{ $adminAssignedCase->notes }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <i class="bi bi-person notes-logo"></i>
                    <div>
                        <h2>Physician Notes</h2>
                        @if (!empty($note))
                            <span>{{ $note->physician_notes }}</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <i class="bi bi-person-check notes-logo"></i>
                    <div>
                        <h2>Admin Notes</h2>
                        @if (!empty($note))
                            <span>{{ $note->admin_notes }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.store.note') }}" method="POST">
                @csrf
                <input type="text" value="{{ $id }}" name="requestId" hidden>
                <div class="form-floating mb-3">
                    <textarea class="form-control @error('admin_note') is-invalid @enderror" name="admin_note" placeholder="injury"
                        id="floatingTextarea2"></textarea>
                    <label for="floatingTextarea2">Additional Notes</label>
                    @error('admin_note')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-end">
                    <button type="submit" class="primary-fill">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection
