 {{-- Transfer Request --}}
 <div class="pop-up transfer-request">
    <div class="popup-heading-section d-flex align-items-center justify-content-between">
        <span>Transfer Request</span>
        <button class="hide-popup-btn providerTransferCancel"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="p-2 mt-2">This request will be transferred to admin.</div>
    <form action="{{ route('provider.transfer.case') }}" method="POST" id="providerTransferCase">
        @csrf
        <input type="text" class="requestId" name="requestId" hidden>
        <div class="d-flex align-items-center justify-content-center gap-2">
            <div class="form-floating">
                <textarea name="notes"
                    class="form-control transfer-description @error('notes')
                    is-invalid
                @enderror"
                    placeholder="notes" id="floatingTextarea2"></textarea>
                <label for="floatingTextarea2">Description</label>
                @error('notes')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="p-2 d-flex align-items-center justify-content-end gap-2">
            <button type="submit" class="primary-fill" id="providerTransferCaseBtn">Submit</button>
            <button type="button" class="primary-empty hide-popup-btn providerTransferCancel">Cancel</button>
        </div>
    </form>
</div>