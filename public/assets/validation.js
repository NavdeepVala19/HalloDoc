$(document).ready(function () {
    // $.validator.addMethod(
    //     "mobileValidation",
    //     function (value, element) {
    //         if (element.intlTelInput("isValidNumber")) {
    //             console.log("valide");
    //             return true;
    //         }
    //         // $(element).intlTelInput("isValidNumber");
    //     },
    //     "Please enter a valid phone number."
    // );

    $.validator.addMethod(
        "mobileValidation",
        function (value, element, params) {
            var telInput = $(element).intlTelInput("isValidNumber");
            console.log(telInput);
            return telInput && intlTelInputUtils.isValidNumber(telInput);
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

    // Only Letters(alpabets allowed), no numeric value will be allowed
    $.validator.addMethod(
        "lettersonly",
        function (value, element) {
            return this.optional(element) || /^[a-z]+$/i.test(value);
        },
        "Letters only please"
    );

    // Email Validation method
    $.validator.addMethod(
        "email",
        function (value, element) {
            var regex =
                /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/;
            return this.optional(element) || regex.test(value);
        },
        "Please enter a valid email address (alphanumeric characters, periods, common symbols, and @ followed by a domain name)"
    );

    // Date Validation (params array will hold minimum and maximum date)
    $.validator.addMethod(
        "dateRange",
        function (value, element, params) {
            if (!value) {
                // Check if the field is empty
                return true; // Allow empty field
            }
            // Parse the entered date and minimum/maximum dates
            var enteredDate = new Date(value);
            var minDate = new Date(params[0]); // First parameter in params array is minimum date
            var maxDate = new Date(params[1]); // Second parameter in params array is maximum date

            // Check if entered date is within the allowed range (inclusive)
            return enteredDate >= minDate && enteredDate <= maxDate;
        },
        "Please enter a date between {0} and {1}."
    );

    // ------------- Common Rules and Message functions repeated uses: ------------------
    // First Name and Last Name Rules and messages
    function nameRules(fieldName) {
        return {
            required: true,
            minlength: 3,
            maxlength: 15,
            lettersonly: true,
            normalizer: function (value) {
                return $.trim(value);
            },
        };
    }

    function nameMessages(fieldName) {
        return {
            required: `Please enter ${fieldName}.`,
            minlength: `${fieldName} should have at least 3 characters`,
            maxlength: `${fieldName} should not have more than 15 characters`,
            lettersonly: `Only alphabets allowed`,
        };
    }

    // Email Validation Rule and Message
    function emailRules(fieldName) {
        return {
            required: true,
            email: true,
        };
    }
    function emailMessages(fieldName) {
        return {
            required: "Please enter email address",
            email: "Please enter a valid email address {ex. a@b.c}",
        };
    }
    // Mobile Number Rule and Message
    function mobileRules(fieldName) {
        return {
            required: true,
            minlength: 12,
            maxlength: 12,
            // mobileValidation: true,

            // minlength: function (value, element) {
            //     // Remove spaces before counting length
            //     const trimmedValue = value.replace(/\s/g, "");
            //     return trimmedValue.length === 10;
            // },
            // maxlength: function (value, element) {
            //     // Remove spaces before counting length
            //     const trimmedValue = value.replace(/\s/g, "");
            //     return trimmedValue.length === 10;
            // },
        };
    }
    function mobileMessages(fieldName) {
        return {
            required: "Please enter phone number",
            minlength: "Phone number should have exact 10 digits",
            maxlength: "Phone number should have exact 10 digits",
            // mobileValidation: "Please enter a valid phone number",
        };
    }
    // TextArea Rule and Message
    function noteRules(fieldName) {
        return {
            required: true,
            minlength: 5,
            maxlength: 200,
        };
    }
    function noteMessages(fieldName) {
        return {
            required: `${fieldName} field is required`,
            minlength: `${fieldName} field should have atleast 5 characters`,
            maxlength: `${fieldName} field should not have more than 200 characters`,
        };
    }

    // Provider Create Request Client Side Validation
    $("#providerCreateRequestForm").validate({
        rules: {
            first_name: nameRules("First name"),
            last_name: nameRules("Last name"),
            phone_number: mobileRules(),
            email: emailRules(),
            dob: {
                required: false,
                dateRange: [
                    new Date("1900-01-01").toDateString(),
                    new Date().toDateString(),
                ],
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
            first_name: nameMessages("First name"),
            last_name: nameMessages("Last name"),
            phone_number: mobileMessages(),
            email: emailMessages(),
            dob: {
                date: "Date should have an proper format",
            },
            street: "Please enter your street",
            city: "Please enter your city",
            state: "Please enter your state",
        },
        errorPlacement: function (error, element) {
            var errorDiv = $('<div class="text-danger"></div>');
            errorDiv.append(error);
            element.closest(".form-floating").append(errorDiv);
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
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
            reason: noteRules(),
        },
        messages: {
            case_tag: "Select A Case Tag To Cancel the Case",
            reason: noteMessages("Notes"),
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
            first_name: nameRules("First name"),
            last_name: nameRules("Last name"),
            phone_number: mobileRules(),
            email: emailRules(),
        },
        messages: {
            first_name: nameMessages("First name"),
            last_name: nameMessages("Last name"),
            phone_number: mobileMessages(),
            email: emailMessages(),
        },
        errorPlacement: function (error, element) {
            var errorDiv = $('<div class="text-danger"></div>');
            errorDiv.append(error);
            element.closest(".form-floating").append(errorDiv);
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
    $("#adminSendLinkButton, .providerSendLinkButton").click(function () {
        if ($("#adminSendLinkForm, #providerSendLinkForm").valid()) {
            $("#adminSendLinkForm, #providerSendLinkForm").submit();
        }
    });

    // Provider and Admin Send Agreement Pop-Up Validation
    $("#providerSendAgreement, #adminSendAgreement").validate({
        rules: {
            phone_number: {
                required: true,
                phoneUS: true,
            },
            email: {
                required: true,
                email: true,
            },
        },
        messages: {
            phone_number: {
                required: "Enter Phone Number to Send Agreement",
                phoneUS: "Enter Phone Number in proper format",
            },
            email: {
                required: "Enter Email to Send Agreement.",
                email: "Your email address must be in the format of name@domain.com",
            },
        },
        errorPlacement: function (error, element) {
            let errorDiv = $("<div class='text-danger'></div>");
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
    $("#providerSendAgreementBtn, #adminSendAgreementBtn").click(function () {
        if ($("#providerSendAgreement, #adminSendAgreement").valid()) {
            $("#providerSendAgreement, #adminSendAgreement").submit();
        }
    });

    // Provider Transfer Request
    $("#providerTransferCase").validate({
        rules: {
            notes: noteRules(),
        },
        messages: {
            notes: noteMessages("Transfer Note"),
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
            assign_note: noteRules(),
            notes: noteRules(),
        },
        messages: {
            region: "Select at least one region.",
            physician:
                "Select physician whom you want to assign these case to.",
            assign_note: noteMessages("Assign case note"),
            notes: noteMessages("Transfer note"),
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
    $("#adminAssignCaseBtn, #adminTransferRequestBtn").click(function () {
        if ($("#adminAssignCase, #adminTransferRequest").valid()) {
            $("#adminAssignCase, #adminTransferRequest").submit();
        }
    });

    // Admin Block Case Pop-Up Validation
    $("#adminBlockCase").validate({
        rules: {
            block_reason: noteRules(),
        },
        messages: {
            block_reason: noteMessages("Block reason"),
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
    $("#adminBlockCaseBtn").click(function () {
        if ($("#adminBlockCase").valid()) {
            $("#adminBlockCase").submit();
        }
    });

    // Patient Cancel Agreement Pop-up
    $("#cancelAgreementPatient").validate({
        rules: {
            cancelReason: noteRules(),
        },
        messages: {
            cancelReason: noteMessages("Cancel reason"),
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
            message: noteRules(),
        },
        messages: {
            message: noteMessages("Message"),
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
            first_name: nameRules("First Name"),
            last_name: nameRules("Last Name"),
            location: {
                required: true,
                minlength: 5,
                maxlength: 50,
            },
            date_of_birth: {
                required: true,
                dateRange: [
                    new Date("1900-01-01").toDateString(),
                    new Date().toDateString(),
                ],
            },
            service_date: {
                required: true,
                dateRange: [
                    new Date("2024-01-01").toDateString(),
                    new Date().toDateString(),
                ],
            },
            mobile: {
                required: true,
                mobileValidation: true,
                // minlength: 12,
                // maxlength: 12,
            },
            email: emailRules(),
            present_illness_history: {
                required: false,
                minlength: 5,
                maxlength: 200,
            },
            medical_history: {
                required: false,
                minlength: 5,
                maxlength: 200,
            },
            medications: {
                required: false,
                minlength: 5,
                maxlength: 200,
            },
            allergies: {
                required: true,
                minlength: 5,
                maxlength: 200,
            },
            temperature: {
                required: false,
                min: -50,
                max: 50,
            },
            heart_rate: {
                required: false,
                min: 30,
                max: 220,
            },
            repository_rate: {
                required: false,
                min: 12,
                max: 40,
            },
            sis_BP: {
                required: false,
                min: 40,
                max: 250,
            },
            dia_BP: {
                required: false,
                min: 40,
                max: 150,
            },
            oxygen: {
                required: false,
                min: 70,
                max: 100,
            },
            pain: {
                required: false,
                minlength: 5,
                maxlength: 50,
            },
            heent: {
                required: false,
                minlength: 5,
                maxlength: 200,
            },
            cv: {
                required: false,
                minlength: 5,
                maxlength: 200,
            },
            chest: {
                required: false,
                minlength: 5,
                maxlength: 200,
            },
            abd: {
                required: false,
                minlength: 5,
                maxlength: 200,
            },
            extr: {
                required: false,
                minlength: 5,
                maxlength: 200,
            },
            skin: {
                required: false,
                minlength: 5,
                maxlength: 200,
            },
            neuro: {
                required: false,
                minlength: 5,
                maxlength: 200,
            },
            other: {
                required: false,
                minlength: 5,
                maxlength: 200,
            },
            diagnosis: {
                required: false,
                minlength: 5,
                maxlength: 200,
            },
            treatment_plan: {
                required: true,
                minlength: 5,
                maxlength: 200,
            },
            medication_dispensed: {
                required: true,
                minlength: 5,
                maxlength: 200,
            },
            procedure: {
                required: true,
                minlength: 5,
                maxlength: 200,
            },
            followUp: {
                required: true,
                minlength: 5,
                maxlength: 200,
            },
        },
        messages: {
            first_name: nameMessages("First Name"),
            last_name: nameMessages("Last Name"),
            location: {
                required: "Please enter patient's whole address",
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 50 characters",
            },
            mobile: {
                required: "Please enter mobile number",
                min: "Mobile number should have 10 digits",
                max: "Mobile number should have 10 digits",
            },
            email: emailMessages("Email"),
            date_of_birth: {
                required: "Please enter date of birth",
            },
            service_date: {
                required: "Please enter service Date",
            },
            present_illness_history: {
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            medical_history: {
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            medications: {
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            allergies: {
                required: "Please Enter allergies of the patient",
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            temperature: {
                min: "Minimum value could be -50",
                max: "Maximum value could be 50",
            },
            heart_rate: {
                min: "Minimum value could be 30",
                max: "Maximum value could be 220",
            },
            repository_rate: {
                min: "Minimum value could be 12",
                max: "Maximum value could be 40",
            },
            sis_BP: {
                min: "Minimum value could be 40",
                max: "Maximum value could be 250",
            },
            dia_BP: {
                min: "Minimum value could be 40",
                max: "Maximum value could be 150",
            },
            oxygen: {
                min: "Minimum value could be 70",
                max: "Maximum value could be 100",
            },
            pain: {
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 50 characters",
            },
            heent: {
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            cv: {
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            chest: {
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            abd: {
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            extr: {
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            skin: {
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            neuro: {
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            other: {
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            diagnosis: {
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            treatment_plan: {
                required: "Please enter a treatment plan for the patient",
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            medication_dispensed: {
                required:
                    "Enter medications which were dispensed during patient visits.",
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            procedure: {
                required:
                    "enter procedures from which patients must pass through.",
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
            },
            followUp: {
                required:
                    "Enter follow-up which should be taken by the patient.",
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters",
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

    // Close Case Page Client Side Validation for phone-number and email
    // Provider and Admin Send Agreement Pop-Up Validation
    $("#closeCase").validate({
        rules: {
            phone_number: mobileRules(),
            email: emailRules(),
        },
        messages: {
            phone_number: mobileMessages("Phone number"),
            email: emailMessages("Email"),
        },
        errorPlacement: function (error, element) {
            let errorBox = $("<span class='text-danger'></span>");
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
    $("#saveCloseCase").click(function () {
        if ($("#closeCase").valid()) {
            $("#closeCase").submit();
        } else {
            $(".default-buttons").hide();
            $(".new-buttons").show();
        }
    });
    // Send Order Validation
    $("#adminSendOrderForm, #providerSendOrderForm").validate({
        rules: {
            profession: {
                required: true,
            },
            vendor_id: {
                required: true,
            },
            business_contact: {
                required: true,
            },
            fax_number: {
                required: true,
            },
            prescription: {
                required: false,
                minlength: 5,
                maxlength: 200,
            },
            email: emailRules(),
        },
        messages: {
            profession: {
                required: "Select Profession to get Vendors option",
            },
            vendor_id: {
                required: "Select Particular Vendor to have it's details",
            },
            business_contact: {
                required: "Enter Business Contact to send Order",
            },
            fax_number: {
                required: "Enter Fax number to send order",
            },
            prescription: {
                minlength: "Minimum length should be 5 characters",
                maxlength: "Maximum length should be 200 characters only",
            },
            email: emailMessages("Email"),
        },
        // errorElement: "span",
        errorPlacement: function (error, element) {
            let errorBox = $("<span class='text-danger'></span>");
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
    $("#adminSendOrderSubmit, #providerSendOrderSubmit").click(function () {
        if ($("#adminSendOrderForm, #providerSendOrderForm").valid()) {
            $("#adminSendOrderForm, #providerSendOrderForm").submit();
        }
    });

    // ------------ PROVIDER SCHEDULING -----------
    // Validation added for shiftEndTime to be greater than shiftStartTime
    $.validator.addMethod(
        "greaterThan",
        function (value, element, params) {
            var startTime = $("#floatingInput2").val();
            if (value < startTime) {
                return false;
            }
            var startTime = $(params).val();
            return value > startTime;
        },
        "Shift End Time must be greater than Shift Start Time."
    );
    //  Validate Provider Scheduling Edit Shift Pop-up
    $("#providerEditShiftForm, #adminEditShiftForm").validate({
        rules: {
            shiftDate: {
                required: true,
            },
            shiftTimeStart: {
                required: true,
            },
            shiftTimeEnd: {
                required: true,
                greaterThan: "#floatingInput2", // ShiftStartTime input element
            },
        },
        messages: {
            shiftDate: {
                required: "Shift Date is required to create shift",
            },
            shiftTimeStart: {
                required: "Shift Start Time is required",
            },
            shiftTimeEnd: {
                required: "Shift End Time is required.",
                greaterThan:
                    "Shift End Time must be greater than Shift Start Time.",
            },
        },
        // errorElement: "span",
        errorPlacement: function (error, element) {
            let errorBox = $("<span class='text-danger'></span>");
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
    $("#saveProviderEditShiftBtn").click(function () {
        if ($("#providerEditShiftForm, #adminEditShiftForm").valid()) {
            $("#providerEditShiftForm, #adminEditShiftForm").submit();
        }
    });

    // ----------- ADMIN SCHEDULING ---------
    $("#adminAddShiftForm, #providerAddShiftForm").validate({
        rules: {
            region: {
                required: true,
            },
            physician: {
                required: true,
            },
            shiftDate: {
                required: true,
            },
            shiftStartTime: {
                required: true,
            },
            shiftEndTime: {
                required: true,
                greaterThan: "#floatingInput2", // ShiftStartTime input element
            },
            // Add a custom validation rule for checkbox selection
            "checkbox[]": {
                required: {
                    depends: function (element) {
                        return $(".repeat-switch").is(":checked");
                    },
                },
            },
        },
        messages: {
            region: {
                required: "Select Region to filter physician",
            },
            physician: {
                required: "Select Physician to create a Shift",
            },
            shiftDate: {
                required: "Shift Date is required to create shift",
            },
            shiftTimeStart: {
                required: "Shift Start Time is required",
            },
            shiftTimeEnd: {
                required: "Shift End Time is required.",
                greaterThan:
                    "Shift End Time must be greater than Shift Start Time.",
            },
            "checkbox[]": {
                required: "Please select at least one day for repeat.",
            },
        },
        // errorElement: "span",
        errorPlacement: function (error, element) {
            let errorBox = $("<span class='text-danger'></span>");
            errorBox.append(error);

            if (element.is(":checkbox")) {
                element.closest(".checkboxes-section").before(errorBox);
            } else {
                element.closest(".form-floating").append(errorBox);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
    $("#adminAddShiftBtn").click(function () {
        if ($("#adminAddShiftForm, #providerAddShiftForm").valid()) {
            $("#adminAddShiftForm, #providerAddShiftForm").submit();
        }
    });

    // Admin View Notes (Store note form validation)
    $("#adminNoteForm, #providerNoteForm").validate({
        rules: {
            admin_note: noteRules(),
            physician_note: noteRules(),
        },
        messages: {
            admin_note: noteMessages("Admin Note"),
            physician_note: noteMessages("Physician Note"),
        },
        errorPlacement: function (error, element) {
            let errorDiv = $("<span class='text-danger'></span>");
            errorDiv.append(error);
            element.closest(".form-floating").append(errorDiv);
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        submitHandler: function (form) {
            form.submit();
        },
    });
});
