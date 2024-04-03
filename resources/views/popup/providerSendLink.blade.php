{{-- Send Link pop-up -> used to send link of Submit Request Screen page to the patient via email and SMS --}}
<div class="pop-up send-link">
    <div class="popup-heading-section d-flex align-items-center justify-content-between">
        <span>Send mail to patient for submitting request</span>
        <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
    </div>
    <form action="{{ route('send.mail') }}" method="POST" id="providerSendLinkForm">
        @csrf
        <div class="p-4 d-flex flex-column align-items-center justify-content-center gap-2">
            <div class="form-floating ">
                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                    id="floatingInput1" placeholder="First Name">
                <label for="floatingInput1">First Name</label>
                @error('first_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-floating ">
                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                    id="floatingInput2" placeholder="Last Name">
                <label for="floatingInput2">Last Name</label>
                @error('last_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating">

                <input type="tel" name="phone_number"
                    class="form-control phone @error('phone_number') is-invalid @enderror" id="telephone"
                    placeholder="Phone Number">

                @error('phone_number')
                    <div class="text-danger w-100">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-floating">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    id="floatingInput3" placeholder="name@example.com">
                <label for="floatingInput3">Email</label>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="p-2 d-flex align-items-center justify-content-end gap-2">
            <input type="submit" value="Send" class="primary-fill" id="providerSendLinkButton">
            <button class="primary-empty hide-popup-btn">Cancel</button>
        </div>
    </form>
</div>
