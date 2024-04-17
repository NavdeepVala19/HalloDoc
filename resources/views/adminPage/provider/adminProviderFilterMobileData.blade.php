   @if ($providersData->isEmpty())
   <div class="d-flex justify-content-center align-items-center">
       <div class="no-record">No Provider Found</div>
   </div>
   @endif
   @foreach ($providersData as $data)
   <div class="mobile-list">
       <div class="main-section mt-3">
           <h5 class="heading"> <input class="form-check-input" type="checkbox" value="" id="checkbox">
               {{ $data->first_name }}
           </h5>
           <div class="detail-box">
               <span>
                   On Call Status: <strong>Available</strong>
               </span>
           </div>
       </div>
       <div class="details mt-3">
           <span><i class="bi bi-person"></i> Role Name : Physician</span>
           <br>
           <span><i class="bi bi-check2"></i>Status : {{ $data->status }} </span>
           <div class="p-2 d-flex align-items-center justify-content-end gap-2">
               <button type="button" data-id='{{ $data->id }}' class="primary-empty contact-btn mt-2 mb-2">Contact</button>
               <a href="{{ route('adminEditProvider',Crypt::encrypt( $data->id)) }}" type="button" class="primary-empty btn edit-btn mt-2 mb-2">Edit</a>
           </div>
       </div>
   </div>
   @endforeach
   {{ $providersData->links('pagination::bootstrap-5') }}



   <!-- contact your provider pop-up -->
   <div class="pop-up new-provider-pop-up">
       <div class="popup-heading-section d-flex align-items-center justify-content-between">
           <span class="ms-3">Contact Your Provider</span>
           <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
       </div>
       <p class="mt-4 ms-3">Choose communication to send message</p>
       <div class="ms-3">
           <form action="{{ route('sendMailToProvider', $data->id) }}" method="post" id="ContactProviderForm">
               @csrf
               <input type="text" name="provider_id" class="provider_id" hidden>
               <div class="form-check">
                   <input class="form-check-input" type="radio" name="contact" value="sms" checked id="flexRadioDefault">
                   <label class="form-check-label" for="flexRadioDefault">
                       SMS
                   </label>
               </div>
               <div class="form-check">
                   <input class="form-check-input" type="radio" name="contact" value="email" id="flexRadioDefault">
                   <label class="form-check-label" for="flexRadioDefault">
                       Email
                   </label>
               </div>
               <div class="form-check">
                   <input class="form-check-input" type="radio" name="contact" value="both" id="flexRadioDefault">
                   <label class="form-check-label" for="flexRadioDefault">
                       Both
                   </label>
               </div>
               <div class="form-floating">
                   <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" name="contact_msg" style="height: 120px"></textarea>
                   <label for="floatingTextarea2">Message</label>
               </div>
       </div>
       <div class="p-2 d-flex align-items-center justify-content-end gap-2">
           <button class="primary-fill sen-btn" type="submit">Send</button>
           <button class="primary-empty hide-popup-btn">Cancel</button>
       </div>
       </form>
   </div>