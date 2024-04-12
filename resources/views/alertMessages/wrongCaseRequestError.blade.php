{{-- Wrong Request on url (case doesn't exists) --}}
@if (session('wrongCase'))
<div class="alert alert-danger popup-message ">
    <span>
        {{ session('wrongCase') }}
    </span>
    <i class="bi bi-check-circle-fill"></i>
</div>
@endif