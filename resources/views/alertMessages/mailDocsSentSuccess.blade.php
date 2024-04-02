{{-- Mail of All The selected Documents are sent --}}
@if (session('mailDocsSent'))
    <div class="alert alert-success popup-message ">
        <span>
            {{ session('mailDocsSent') }}
        </span>
        <i class="bi bi-check-circle-fill"></i>
    </div>
@endif
