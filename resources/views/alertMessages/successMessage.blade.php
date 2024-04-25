{{-- SendLink Completed Successfully --}}
@if (session('successMessage'))
<div class="alert alert-success popup-message ">
    <span>
        {{ session('successMessage') }}
    </span>
    <i class="bi bi-check-circle-fill"></i>
</div>
@endif