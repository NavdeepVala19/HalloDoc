{{-- Encounter --}}
<div class="pop-up encounter">
    <div class="popup-heading-section d-flex align-items-center justify-content-between">
        <span>Select Type Of Care</span>
        <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('provider.active.encounter') }}" method="GET">
        <div class="p-4 d-flex align-items-center justify-content-center gap-2">
            <input type="text" name="requestId" class="case-id" value="" hidden>
            {{-- If the provider selects the housecall, then that request will be in same state, but status changes from MdEnRoute to MdEnSite --}}
            <button type="button" class="primary-empty housecall-btn">Housecall</button>
            <input type="text" class="house_call" name="house_call" hidden required>
            {{-- If the provider selects the consult, then that request will move into Conclude state. --}}
            <button type="button" class="primary-empty consult-btn">Consult</button>
            <input type="text" class="consult" name="consult" hidden required>
        </div>
        <div class="p-2 d-flex align-items-center justify-content-end gap-2">
            {{-- <button class="primary-fill encounter-save-btn">Save</button> --}}
            <input type="submit" class="primary-fill encounter-save-btn" id="save-btn" value="Save">
            <button type="button" class="primary-empty hide-popup-btn">Cancel</button>
        </div>
    </form>
</div>