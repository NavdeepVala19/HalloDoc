{{-- No Records Found Error Message --}}
@if (session('noRecordFound'))
    <div class="alert alert-danger popup-message ">
        <span>
            {{ session('noRecordFound') }}
        </span>
        <i class="bi bi-check-circle-fill"></i>
    </div>
@endif
