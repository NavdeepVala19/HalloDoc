<table class="provider-table table" id="all-providers-data">
    <thead class="table-secondary">
        <tr>
            <td style="width: 7%;" class="theader">Stop Notification</td>
            <td class="theader">Provider Name</td>
            <td class="theader">Role</td>
            <td class="theader">On Call Status</td>
            <td class="theader">Status</td>
            <td style="width: 13%;" class="theader">Actions</td>
        </tr>
    </thead>

    <tbody>
        @foreach ($providersData as $data)
        <tr>
            <td class="checks"> <input class="form-check-input" type="checkbox" value="" id="checkbox">
            </td>
            <td class="data"> {{$data->first_name}}</td>
            <td class="data"> Physician</td>
            <td class="data"> Available</td>
            <td class="data"> {{$data->status}} </td>
            <td class="data gap-1">
                <button type="button" data-id='{{$data->id}}' class="primary-empty contact-btn mt-2 mb-2">Contact</button>
                <a href="{{route('adminEditProvider', $data->id) }}" type="button" class="primary-empty btn edit-btn mt-2 mb-2">Edit</a>
            </td>
        </tr>
        @endforeach
    </tbody>



    <!-- contact your provider pop-up -->

    <div class="pop-up new-provider-pop-up">
        <div class="popup-heading-section d-flex align-items-center justify-content-between">
            <span class="ms-3">Contact Your Provider</span>
            <button class="hide-popup-btn"><i class="bi bi-x-lg"></i></button>
        </div>
        <p class="mt-4 ms-3">Choose communication to send message</p>
        <div class="ms-3 ">

            <form action="#" method="post" id="ContactProviderForm">
                @csrf

                <input type="text" name="provider_id" class="provider_id" hidden>

                <div class="radio-sms">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                    <label class="form-check-label ms-1" for="flexRadioSMS">
                        SMS
                    </label>
                </div>

                <div class="radio-email">
                    <input class="form-check-input" type="radio" value="email" name="emailContact" id="flexRadioDefault2">
                    <label class="form-check-label ms-1" for="flexRadioEmail">
                        Email
                    </label>
                </div>

                <div class="radio-both">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3">
                    <label class="form-check-label ms-1" for="flexRadioBoth">
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

    