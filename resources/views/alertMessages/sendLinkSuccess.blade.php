{{-- SendLink Completed Successfully --}}
@if (session('linkSent'))
<div class="alert alert-success popup-message ">
    <span>
        {{ session('linkSent') }}
    </span>
    <i class="bi bi-check-circle-fill"></i>
</div>
@endif