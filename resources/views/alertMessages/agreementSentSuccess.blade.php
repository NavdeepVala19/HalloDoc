{{-- SendLink Completed Successfully --}}
@if (session('agreementSent'))
<div class="alert alert-success popup-message ">
    <span>
        {{ session('agreementSent') }}
    </span>
    <i class="bi bi-check-circle-fill"></i>
</div>
@endif