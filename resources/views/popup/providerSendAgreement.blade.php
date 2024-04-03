 {{-- Send Agreement Pop-up --}}
 {{-- This pop-up will open when admin/provider will click on “Send agreement” link from Actions menu. From the
pending state, providers need to send an agreement link to patients. --}}
 <div class="pop-up send-agreement">
     <div class="popup-heading-section d-flex align-items-center justify-content-between">
         <span>Send Agreement</span>
         <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
     </div>
     <div class="p-3">
         <div>
             <span class="request-detail">Show the name and color of request (i.e. patinet, family, business,
                 concierge)</span>
             <p class="m-2">To send Agreement please make sure you are updating the correct contact information below
                 for the responsible party. </p>
         </div>
         <form action="{{ route('send.agreement') }}" method="POST" id='providerSendAgreement'>
             @csrf
             <input type="text" class="send-agreement-id" name="request_id" value="" hidden>
             <div>
                 <div class="form-floating">
                     <input type="text" name="phone_number"
                         class="form-control @error('phone_number') is-invalid @enderror agreement-phone-number"
                         id="floatingInput1" placeholder="Phone Number">
                     <label for="floatingInput1">Phone Number</label>
                     @error('phone_number')
                         <div class="text-danger">{{ $message }}</div>
                     @enderror
                 </div>
                 <div class="form-floating">
                     <input type="email" name="email"
                         class="form-control @error('email') is-invalid @enderror agreement-email" id="floatingInput2"
                         placeholder="name@example.com">
                     <label for="floatingInput2">Email</label>
                     @error('email')
                         <div class="text-danger">{{ $message }}</div>
                     @enderror
                 </div>
             </div>
     </div>
     <div class="p-2 d-flex align-items-center justify-content-end gap-2">
         <input type="submit" value="Send" class="primary-fill send-case" id='providerSendAgreementBtn'>
         <button type="button" class="primary-empty hide-popup-btn">Cancel</button>
     </div>
     </form>
 </div>
