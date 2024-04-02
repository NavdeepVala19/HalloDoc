{{-- Order Created Successfully Pop-up Message --}}
@if (session('orderPlaced'))
    <div class="alert alert-success popup-message ">
        <span>
            {{ session('orderPlaced') }}
        </span>
        <i class="bi bi-check-circle-fill"></i>
    </div>
@endif
