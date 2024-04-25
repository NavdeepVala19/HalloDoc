{{-- Wrong Request on url (case doesn't exists) --}}
@if (session('dangerMessage'))
<div class="alert alert-danger popup-message ">
    <span>
        {{ session('dangerMessage') }}
    </span>
    <i class="bi bi-check-circle-fill"></i>
</div>
@endif