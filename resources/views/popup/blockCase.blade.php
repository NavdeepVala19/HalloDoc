{{-- Block Case Pop-up --}}
{{-- This pop-up will open when admin clicks on “Block Case” link from Actions menu. From the new state, admin
can block any case. All blocked cases can be seen in Block history page. --}}
<div class="pop-up block-case">
    <div class="popup-heading-section d-flex align-items-center justify-content-between">
        <span>Confirm Block</span>
        <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="m-3">
        <span>Patient Name: </span>
        <span class="displayPatientName"></span>
    </div>
    <form action="{{ route('admin.block.case') }}" method="POST" id="adminBlockCase">
        @csrf
        <div class="m-3">
            <input type="text" class="requestId" name="requestId" value="" hidden>
            <div class="form-floating">
                <textarea class="form-control @error('block_reason') is-invalid @enderror" name="block_reason"
                    placeholder="Reason for block request" id="floatingTextarea2"></textarea>
                <label for="floatingTextarea2">Reason for Block Request</label>
                @error('block_reason')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="p-2 d-flex align-items-center justify-content-end gap-2">
            <input type="submit" value="Confirm" class="primary-fill" id="adminBlockCaseBtn">
            <button class="primary-empty hide-popup-btn">Cancel</button>
        </div>
    </form>
</div>
