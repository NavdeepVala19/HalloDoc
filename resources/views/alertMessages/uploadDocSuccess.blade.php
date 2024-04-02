{{-- Document Upload Was Successfully --}}
@if (session('uploadSuccessful'))
<div class="alert alert-success popup-message">
    <span>
        {{ session('uploadSuccessful') }}
    </span>
    <i class="bi bi-check-circle-fill"></i>
</div>
@endif