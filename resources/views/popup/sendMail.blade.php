{{-- Send Mail Pop-up --}}
<div class="pop-up send-mail">
    <div class="popup-heading-section d-flex align-items-center justify-content-between">
        <span>Send Mail</span>
        <button class="hide-popup-btn sendMailCancel"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="m-3">
        <span>Patient Name: </span>
        <span class="displayPatientName"></span>
        <div>
            <span class="displayPatientEmail"></span>
        </div>
    </div>
    <form action="{{ route('send.mail.patient') }}" method="POST" id="sendMailForm">
        @csrf
        <div class="m-3">
            <input type="text" class="requestId" name="requestId" value="" hidden>
            <div class="form-floating">
                <textarea name="message" class="form-control @error('message') is-invalid @enderror" placeholder="message"
                    id="floatingTextarea2"></textarea>
                <label for="floatingTextarea2">Message</label>
                @error('message')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="p-2 d-flex align-items-center justify-content-end gap-2">
            <input type="submit" value="Confirm" class="primary-fill">
            <button class="primary-empty hide-popup-btn sendMailCancel">Cancel</button>
        </div>
    </form>
</div>
