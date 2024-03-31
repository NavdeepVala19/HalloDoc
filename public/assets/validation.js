$(document).ready(function () {
    $("#providerCreateRequestForm").validate({
        rules: {
            first_name: "required",
            last_name: "required",
            phone_number: "required",
            email: {
                required: true,
                email: true,
            },
            street: "required",
            city: "required",
            state: "required",
            zip: {
                required: false,
                digits: true,
            },
            room: {
                required: false,
                digits: true,
            },
        },
        messages: {
            first_name: "Please enter your first name",
            last_name: "Please enter your last name",
            phone_number: "Please enter your phone number",
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
            },
            street: "Please enter your street",
            city: "Please enter your city",
            state: "Please enter your state",
            zip: "Please enter a valid zip code",
            room: "Please enter a valid room number",
        },
        errorPlacement: function (error, element) {
            var errorDiv = $('<div class="text-danger"></div>');

            // Append the error message to the error div
            errorDiv.append(error);

            // Insert the error div after the form-floating element
            $(".form-floating").forEach((element) => {
                element.closest(".form-floating").append(errorDiv);
            });
        },
    });
    $("#providerSaveButton").click(function () {
        if ($("#providerCreateRequestForm").valid()) {
            $("#providerCreateRequestForm").submit();
        }
    });

    $("#cancelCaseForm").validate({
        rules: {
            case_tag: "required",
            reason: "required",
        },
        messages: {
            case_tag: "Select A Case Tag To Cancel the Case",
            reason: "Provide Cancellation Notes",
        },
        errorPlacement: function (error, element) {
            var errorDiv = $('<div class="text-danger"></div>');
            errorDiv.append(error);
            // $(".form-floating").forEach((element) => {
            element.closest(".form-floating").append(errorDiv);
            // });
            // $(".form-floating").each(function() {
            //     $(this).append(errorDiv);
            // });
        },
    });
    $(".cancel-case").click(function () {
        console.log("Cancel Case");
        if ($("#cancelCaseForm").valid()) {
            $("#cancelCaseForm").submit();
        }
    });
});
