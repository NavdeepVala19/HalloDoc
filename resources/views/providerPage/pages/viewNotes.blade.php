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
                        @if ($adminAssignedCase)
                            <span>Admin transferred to Dr. {{ $adminAssignedCase->transferedPhysician->first_name }}
                                {{ $adminAssignedCase->transferedPhysician->last_name }} on
                                {{ $adminAssignedCase->created_at->format('d-m-Y') }}
                                at {{ $adminAssignedCase->created_at->format('H:i:s') }} :
                                {{ $adminAssignedCase->notes }}
                            </span>
                        @endif
                        @if ($providerTransferedCase && $providerTransferedCase->provider)
                            <div>Dr. {{ $providerTransferedCase->provider->first_name }}
                                {{ $providerTransferedCase->provider->last_name }} transferred to Admin on
                                {{ $providerTransferedCase->created_at->format('d-m-Y') }} at
                                {{ $providerTransferedCase->created_at->format('H:i:s') }} :
                                {{ $providerTransferedCase->notes }}
                            </div>
                        @endif
                        @if ($adminTransferedCase && $adminTransferedCase->provider)
                            <div>Admin transferred to Dr. {{ $adminTransferedCase->provider->first_name }}
                                {{ $adminTransferedCase->provider->last_name }} on
                                {{ $adminTransferedCase->created_at->format('d-m-Y') }} at
                                {{ $adminTransferedCase->created_at->format('H:i:s') }} :
                                {{ $adminTransferedCase->notes }}
                            </div>
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
            <form action="{{ route('provider.store.note') }}" method="POST">
                @csrf
                <input type="text" value="{{ $id }}" name="requestId" hidden>
                <div class="form-floating mb-3">
                    <textarea class="form-control @error('physician_note') is-invalid @enderror" name="physician_note" placeholder="injury"
                        id="floatingTextarea2"></textarea>
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
