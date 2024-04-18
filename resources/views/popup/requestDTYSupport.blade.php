{{-- Request DTY Support pop-up ->  --}}
<div class="pop-up request-support">
    <div class="popup-heading-section d-flex align-items-center justify-content-between">
        <span>Request Support</span>
        <button class="hide-popup-btn requestDTYClose"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('sendRequestSupport') }}" method="POST" id="requestDTYSupportForm">
        @csrf
        <div class="p-4 d-flex flex-column align-items-center justify-content-center gap-2">
            <p>To all unscheduled Physicians:We are short on coverage and needs additional support On Call to respond to Requests</p>
            <div class="form-floating">
                <textarea class="form-control @error('contact_msg') is-invalid @enderror pop-up-request-support" placeholder="Leave a comment here" id="floatingTextarea2" name="contact_msg"
                    style="height: 120px"></textarea>
                <label for="floatingTextarea2">Message</label>
                <span id="errorMsg"></span> 
                @error('contact_msg')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="p-2 d-flex align-items-center justify-content-end gap-2">
            <input type="submit" value="Send" class="primary-fill">
            <button type="button" class="primary-empty hide-popup-btn requestDTYClose">Cancel</button>
        </div>
    </form>
</div>


