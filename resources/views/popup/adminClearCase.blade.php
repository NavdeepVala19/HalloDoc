{{-- Clear Case Pop-up --}}
    {{-- This pop-up will open when admin clicks on “Clear case” link from Actions menu. From the pending and close
state, admin can clear the case from the action grid. --}}
<div class="pop-up clear-case ">
    <form action="{{ route('admin.clear.case') }}" method="post">
        @csrf
        <input type="text" class="request_id" value="" name="requestId" hidden>
        <div class="d-flex flex-column align-items-center justify-content-center p-4">
            <i class="bi bi-exclamation-circle-fill warning-icon"></i>
            <div>
                <h3 class="text-center">Confirmation for clear case</h3>
                <p class="text-center">Are you sure, you want to clear this request? Once clear, you are not able to see
                    this
                    request!
                </p>
            </div>
            <div>
                <input type="submit" value="Clear" class="primary-fill">
                <button class="primary-empty hide-popup-btn">Cancel</button>
            </div>
        </div>
    </form>
</div>