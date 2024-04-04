$(document).ready(function () {
    // Phone Number Validation
    $.validator.addMethod(
        "phoneUS",
        function (phone_number, element) {
            return (
                this.optional(element) ||
                phone_number.match(
                    /^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/
                )
            );
        },
        "Please enter a valid phone number."
    );
    // Validation for city input field
    $.validator.addMethod(
        "city",
        function (value, element) {
            return value.match(/^[a-zA-Z ,_-]+?$/);
        },
        "Please enter a valid city name."
    );

    // Validation for state input field
    $.validator.addMethod(
        "state",
        function (value, element) {
            return value.match(/^[a-zA-Z ,_-]+?$/);
        },
        "Please enter a valid state name."
    );

    // Provider Create Request Client Side Validation
    $("#providerCreateRequestForm").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            last_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            phone_number: {
                required: true,
                phoneUS: true,
            },
            email: {
                required: true,
                email: true,
            },
            street: {
                required: true,
                minlength: 2,
                maxlength: 100,
            },
            city: {
                required: true,
                minlength: 2,
                maxlength: 40,
                city: true,
            },
            state: {
                required: true,
                minlength: 2,
                maxlength: 30,
                state: true,
            },
        },
        messages: {
            first_name: {
                required: "Please enter first name",
                minlength: "First Name should have at least 2 characters",
                maxlength: "First Name should not have more than 30 characters",
            },
            last_name: {
                required: "Please enter last name",
                minlength: "Last Name should have at least 2 characters",
                maxlength: "Last Name should not have more than 30 characters",
            },
            phone_number: {
                required: "Please enter mobile number",
                phoneUS: "Please enter a valid phone number",
            },
            email: {
                required: "Please enter email address",
                email: "Please enter a valid email address",
            },
            street: "Please enter your street",
            city: "Please enter your city",
            state: "Please enter your state",
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            // var errorDiv = $('<div class="text-danger"></div>');
            error.addClass("text-danger");
            element.closest(".form-floating").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
    $("#providerSaveButton").click(function () {
        if ($("#providerCreateRequestForm").valid()) {
            $("#providerCreateRequestForm").submit();
        }
    });

    // Admin Cancel Case Pop-Up Client Side Validation
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
            element.closest(".form-floating").append(errorDiv);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
    $("#cancel-case").click(function () {
        if ($("#cancelCaseForm").valid()) {
            $("#cancelCaseForm").submit();
        }
    });

    // Admin Send Link Pop-Up Validation
    $("#adminSendLinkForm, #providerSendLinkForm").validate({
        rules: {
            first_name: "required",
            last_name: "required",
            phone_number: {
                required: true,
                minlength: 10,
            },
            email: {
                required: true,
                email: true,
            },
        },
        messages: {
            first_name: { required: "Enter your First Name" },
            last_name: { required: "Enter your Last Name" },
            phone_number: {
                required: "Enter Phone Number to send a link.",
                minlength: "Phone number should atleast have 10 digits",
            },
            email: {
                required: "Enter Email address to send a link.",
                email: "Your email address must be in the format of name@domain.com",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            // var errorDiv = $('<div class="text-danger"></div>');
            // errorDiv.append(error);
            error.addClass("text-danger");
            element.closest(".form-floating").append(error);
            // element.parent().append(errorDiv);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
    $("#adminSendLinkButton, .providerSendLinkButton").click(function () {
        if ($("#adminSendLinkForm").valid()) {
            $("#adminSendLinkForm").submit();
        }
    });

    // Provider and Admin Send Agreement Pop-Up Validation
    $("#providerSendAgreement, #adminSendAgreement").validate({
        rules: {
            phone_number: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
        },
        messages: {
            phone_number: { required: "Enter Phone Number to Send Agreement" },
            email: {
                required: "Enter Email to Send Agreement.",
                email: "Your email address must be in the format of name@domain.com",
            },
        },
        // errorElement: "span",
        errorPlacement: function (error, element) {
            let errorBox = $("<div class='text-danger'></div>");
            errorBox.append(error);
            // error.addClass("text-danger");

            if (element.attr("name") == "phone_number") {
                element.closest(".form-floating .form-control").after(errorBox);
            } else if (element.attr("name") == "email") {
                element.closest(".form-floating .form-control").after(errorBox);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
    $("#providerSendAgreementBtn, #adminSendAgreementBtn").click(function () {
        if ($("#providerSendAgreement, #adminSendAgreement").valid()) {
            $("#providerSendAgreement, #adminSendAgreement").submit();
        }
    });

    // Provider Transfer Request
    $("#providerTransferCase").validate({
        rules: {
            notes: "required",
        },
        messages: {
            notes: "Please provide a note for this transfer request.",
        },
        errorPlacement: function (error, element) {
            let errorBox = $("<div class='text-danger'></div>");
            errorBox.append(error);
            element.closest(".form-floating").append(errorBox);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
    $("#providerTransferCaseBtn").click(function () {
        if ($("#providerTransferCase").valid()) {
            $("#providerTransferCase").submit();
        }
    });

    // Admin Assign Case and Admin Transfer Case Pop-ups Validation
    $("#adminAssignCase, #adminTransferRequest").validate({
        rules: {
            region: "required",
            physician: "required",
            assign_note: "required",
            notes: "required",
        },
        messages: {
            region: "Select at least one region.",
            physician:
                "Select Physician Whom you want to assign these case to.",
            assign_note: "Provide Note for the assigned case.",
            notes: "Enter Transfer Note for these case.",
        },
        errorPlacement: function (error, element) {
            let errorBox = $("<div class='text-danger'></div>");
            errorBox.append(error);
            element.closest(".form-floating").append(errorBox);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
    $("#adminAssignCaseBtn, #adminTransferRequestBtn").click(function () {
        if ($("#adminAssignCase, #adminTransferRequest").valid()) {
            $("#adminAssignCase, #adminTransferRequest").submit();
        }
    });

    // Admin Block Case Pop-Up Validation
    $("#adminBlockCase").validate({
        rules: {
            block_reason: "required",
        },
        messages: {
            block_reason: "Provide reason for Blocking the Case!",
        },
        errorPlacement: function (error, element) {
            let errorBox = $("<div class='text-danger'></div>");
            errorBox.append(error);
            element.closest(".form-floating").append(errorBox);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
    $("#adminBlockCaseBtn").click(function () {
        if ($("#adminBlockCase").valid()) {
            $("#adminBlockCase").submit();
        }
    });

    // Patient Cancel Agreement Pop-up
    $("#cancelAgreementPatient").validate({
        rules: {
            cancelReason: "required",
        },
        messages: {
            cancelReason: "Provide reason for Cancelling the Agreement!",
        },
        errorPlacement: function (error, element) {
            let errorBox = $("<div class='text-danger'></div>");
            errorBox.append(error);
            element.closest(".form-floating").append(errorBox);
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
    $("#cancelAgreementPatientBtn").click(function () {
        if ($("#cancelAgreementPatient").valid()) {
            $("#cancelAgreementPatient").submit();
        }
    });

    // Provider Profile Email pop-up for requesting changes in profile
    $("#profileEditMailForm").validate({
        rules: {
            message: "required",
        },
        messages: {
            message: "Specify the changes you want to make in your profile",
        },
        errorPlacement: function (error, element) {
            let errorBox = $("<div class='text-danger'></div>");
            errorBox.append(error);
            element.closest(".form-floating").append(errorBox);
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
    $("#profileEditMailFormBtn").click(function () {
        if ($("#profileEditMailForm").valid()) {
            $("#profileEditMailForm").submit();
        }
    });

    // Encounter Form Client Side Validation
    $("#providerEncounterForm, #adminEncounterForm").validate({
        rules: {
            first_name: "required",
            mobile: {
                phoneUS: true,
                required: false,
            },
            email: {
                required: true,
                email: true,
            },
        },
        messages: {
            first_name: "First Name field is required",
            mobile: {
                phoneUS: "Phone Number should have valid format",
            },
            email: {
                required: "Email Field is required",
                email: "Please provide a valid Email Address",
            },
        },
        errorPlacement: function (error, element) {
            let errorBox = $("<div class='text-danger'></div>");
            errorBox.append(error);
            element.closest(".form-floating").append(errorBox);
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
    $("#providerEncounterFormBtn, #adminEncounterFormBtn").click(function () {
        if ($("#providerEncounterForm, #adminEncounterForm").valid()) {
            $("#providerEncounterForm, #adminEncounterForm").submit();
        }
    });
});
