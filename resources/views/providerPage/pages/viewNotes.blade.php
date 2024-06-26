@extends('index')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/providerPage/providerProfile.css') }}">
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
    {{-- Note added Successfully --}}
    @if (session('providerNoteAdded'))
        <div class="alert alert-success popup-message ">
            <span>
                {{ session('providerNoteAdded') }}
            </span>
            <i class="bi bi-check-circle-fill"></i>
        </div>
    @endif
    <div class="container form-container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="heading">
                Notes
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


        <div class="section">
            <div class="grid-2 notes-section">
                <div class="d-flex align-items-center gap-4">
                    <i class="bi bi-arrow-down-up notes-logo"></i>
                    <div>
                        <h2>Transfer Notes</h2>
                        @if ($adminAssignedCase && $adminAssignedCase->transferedPhysician)
                            <div>
                                <span class='fs-5 fw-bold'>
                                    1. Admin transferred to Dr. {{ $adminAssignedCase->transferedPhysician->first_name }}
                                    {{ $adminAssignedCase->transferedPhysician->last_name }}
                                </span>
                                <br>
                                on {{ $adminAssignedCase->created_at->format('d-m-Y') }}
                                at {{ $adminAssignedCase->created_at->format('H:i:s') }} :
                                <span class="fw-bold fst-italic">
                                    {{ $adminAssignedCase->notes }}
                                </span>
                            </div>
                        @endif
                        @if ($providerTransferedCase && $providerTransferedCase->provider)
                            <div>
                                <span class="fs-5 fw-bold">
                                    2. Dr. {{ $providerTransferedCase->provider->first_name }}
                                    {{ $providerTransferedCase->provider->last_name }} transferred to Admin
                                </span>
                                <br>
                                on
                                {{ $providerTransferedCase->created_at->format('d-m-Y') }} at
                                {{ $providerTransferedCase->created_at->format('H:i:s') }} :
                                <span class="fw-bold fst-italic">
                                    {{ $providerTransferedCase->notes }}
                                </span>
                            </div>
                        @endif
                        @if ($adminTransferedCase && $adminTransferedCase->transferedPhysician)
                            <div>
                                <span class="fs-5 fw-bold">
                                    3. Admin transferred to Dr.
                                    {{ $adminTransferedCase->transferedPhysician->first_name }}
                                    {{ $adminTransferedCase->transferedPhysician->last_name }}
                                </span>
                                <br>
                                on {{ $adminTransferedCase->created_at->format('d-m-Y') }} at
                                {{ $adminTransferedCase->created_at->format('H:i:s') }} :
                                <span class="fw-bold fst-italic">
                                    {{ $adminTransferedCase->notes }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <i class="bi bi-person notes-logo"></i>
                    <div class="notes">
                        <h2>Physician Notes</h2>
                        @if (!empty($note))
                            <span>{{ $note->physician_notes }}</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <i class="bi bi-person-check notes-logo"></i>
                    <div class="notes">
                        <h2>Admin Notes</h2>
                        @if (!empty($note))
                            <span>{{ $note->admin_notes }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <form action="{{ route('provider.store.note') }}" method="POST" id="providerNoteForm">
                @csrf
                <input type="text" value="{{ $requestId }}" name="requestId" hidden>
                <div class="form-floating mb-3">
                    <textarea class="form-control @error('physician_note') is-invalid @enderror" name="physician_note" placeholder="injury"
                        id="floatingTextarea2">{{ $note->physician_notes ?? '' }}</textarea>
                    <label for="floatingTextarea2">Additional Notes</label>
                    @error('physician_note')
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

@section('script')
    <script src="{{ asset('assets/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/validation.js') }}"></script>
@endsection
