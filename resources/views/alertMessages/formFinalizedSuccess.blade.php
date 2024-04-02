{{-- Encounter Form Finalized Successfully --}}
@if (session('formFinalized'))
    <div class="alert alert-success popup-message ">
        <span>
            {{ session('formFinalized') }}
        </span>
        <i class="bi bi-check-circle-fill"></i>
    </div>
@endif
