$(document).ready(function () {
    // Add custom validation method for phone number
    $.validator.addMethod(
        "phoneIndia",
        function (value, element) {
            return this.optional(element) || iti.isValidNumber();
        },
        "Please enter a valid phone number."
    );

    // Only Letters(alpabets allowed), no numeric value will be allowed
    $.validator.addMethod(
        "lettersonly",
        function (value, element) {
            return this.optional(element) || /^[a-z]+$/i.test(value);
        },
        "Please enter alphabets only (characters and numbers not allowed)"
    );

    // Email Validation method
    $.validator.addMethod(
        "email",
        function (value, element) {
            // var regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            var regex = /^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/;
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
            var maxDate = new Date(); // Use current date as maximum date

            if (params[1]) {
                maxDate = new Date(params[1]); // Second parameter in params array is maximum date
            }
            // Check if entered date is within the allowed range (inclusive)
            return enteredDate >= minDate && enteredDate <= maxDate;
        },
        "Please enter a date between {0} and {1}."
    );
    // Notes (Text-area) validations
    $.validator.addMethod(
        "notes",
        function (value, element) {
            const regex = /^[a-zA-Z0-9 ,_-]+?$/; // Allows letters, spaces, punctuation
            return this.optional(element) || regex.test(value.trim());
        },
        "Only alpabets, numbers and ,-_ are allowed."
    );

    // Only alphabets, Numbers & spaces allowed
    $.validator.addMethod(
        "lettersNum",
        function (value, element) {
            const regex = /^[a-zA-Z0-9 ]+?$/; // Allows letters, spaces, & numbers
            return this.optional(element) || regex.test(value.trim());
        },
        "Only alpabets, Numbers and spaces are allowed."
    );

    // Only alphabets & spaces allowed
    $.validator.addMethod(
        "alphaSpace",
        function (value, element) {
            const regex = /^[a-zA-Z ]+?$/; // Allows letters, spaces, & numbers
            return this.optional(element) || regex.test(value.trim());
        },
        "Only alpabets and spaces are allowed."
    );

    // Only alphabets, Numbers and (,_-) allowed
    $.validator.addMethod(
        "alphaNumChar",
        function (value, element) {
            const regex = /^[a-zA-Z0-9 ,_.-]+?$/;
            return this.optional(element) || regex.test(value.trim());
        },
        "Only alphabets, Numbers and ,_-. allowed"
    );

    $.validator.addMethod(
        "customDigitValidation",
        function (value, element) {
            const regex = /^[1-9][0-9]*$/;
            return this.optional(element) || regex.test(value.trim()); // Regex for digits only
        },
        "Special characters are not allowed, Only numbers allowed"
    );

    // Encounter Form : Service date should be greater than date of birth
    $.validator.addMethod(
        "serviceDate",
        function (value, element, params) {
            const dob = $(params).val();
            if (dob > value) {
                return false;
            }
            return value;
        },
        "Date of Service should be greater than date of birth."
    );

    // date should not be greater than today's date
    $.validator.addMethod(
        "maxCurrentDate",
        function (value, element, params) {
            const date = new Date().toISOString().split("T")[0];
            if (date < value) {
                return false;
            }
            return value;
        },
        "Date of Service should not be greater than today's date."
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
            maxlength: 50,
            email: true,
        };
    }
    function emailMessages(fieldName) {
        return {
            required: "Please enter email address",
            maxlength:
                "Email should not be longer than 50 characters (including local and domain part)",
            email: "Please enter a valid email address {ex. a@b.cd}",
        };
    }
    // Mobile Number Rule and Message
    function mobileRules(fieldName) {
        return {
            required: true,
            phoneIndia: true,
        };
    }
    function mobileMessages(fieldName) {
        return {
            required: "Please enter phone number",
        };
    }
    // TextArea Rule and Message
    function noteRules(fieldName) {
        return {
            required: true,
            minlength: 5,
            maxlength: 200,
            alphaNumChar: true,
        };
    }
    function noteMessages(fieldName) {
        return {
            required: `${fieldName} field is required`,
            minlength: `${fieldName} field should have atleast 5 characters`,
            maxlength: `${fieldName} field should not have more than 200 characters`,
        };
    }

    // Admin/Provider send mail Pop-Up Validation
    $("#sendMailForm").validate({
        rules: {
            message: {
                required: true,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
        },
        messages: {
            message: {
                required: "Please enter a message to send to patient.",
                minlength: "Minimum 5 characters are needed.",
                maxlength: "Maximum 200 characters are allowed.",
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
        submitHandler: function (form) {
            $(".loader").fadeIn("slow"); // Show spinner on valid submission
            form.submit();
        },
    });

    // Provider Create Request Client Side Validation
    $("#providerCreateRequestForm").validate({
        rules: {
            first_name: nameRules("First name"),
            last_name: nameRules("Last name"),
            phone_number: mobileRules(),
            email: emailRules(),
            dob: {
                required: false,
                dateRange: [new Date("1900-01-01").toDateString()],
            },
            street: {
                required: true,
                minlength: 3,
                maxlength: 30,
                alphaNumChar: true,
            },
            city: {
                required: true,
                minlength: 5,
                maxlength: 30,
                alphaSpace: true,
            },
            state: {
                required: true,
                minlength: 5,
                maxlength: 30,
                alphaSpace: true,
            },
            zip: {
                required: false,
                digits: true,
                minlength: 6,
                maxlength: 6,
                customDigitValidation: true,
            },
            note: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
        },
        messages: {
            first_name: nameMessages("First name"),
            last_name: nameMessages("Last name"),
            phone_number: mobileMessages(),
            email: emailMessages(),
            dob: {
                dateRange:
                    "Date of Birth should be between " +
                    new Date("1900-01-01").toDateString() +
                    " and " +
                    new Date().toDateString(),
            },
            street: {
                required: "Please enter street",
                minlength: "Minimum 3 characters are required",
                maxlength: "Maximum 30 characters are allowed",
            },
            city: {
                required: "Please enter city",
                minlength: "Minimum 5 alphabets are required",
                maxlength: "Maximum 30 alphabets are allowed",
            },
            state: {
                required: "Please enter state",
                minlength: "Minimum 5 characters are required",
                maxlength: "Maximum 30 characters are allowed",
            },
            zip: {
                min: "Please enter positive number with 6 digits",
                minlength: "Zip code should have minimum 6 digits",
                maxlength: "Zip code should have maximum 6 digits",
            },
            note: {
                minlength: "Minimum 5 characters are required",
                maxlength: "Maximum 200 characters are allowed",
            },
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
        submitHandler: function (form) {
            $(".loader").fadeIn("slow"); // Show spinner on valid submission
            form.submit(); // Submit the form
        },
    });

    // Admin Cancel Case Pop-Up Client Side Validation
    $("#cancelCaseForm").validate({
        rules: {
            case_tag: "required",
            reason: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
        },
        messages: {
            case_tag: "Reason for cancellation is required",
            reason: {
                minlength: "Minimum 5 characters are needed.",
                maxlength: "Maximum 200 characters are allowed.",
            },
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
        submitHandler: function (form) {
            form.submit();
        },
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
        submitHandler: function (form) {
            $(".loader").fadeIn("slow"); // Show spinner on valid submission
            form.submit(); // Submit the form
        },
    });

    // Provider and Admin Send Agreement Pop-Up Validation
    $("#providerSendAgreement, #adminSendAgreement").validate({
        rules: {
            phone_number: mobileRules(),
            email: {
                required: true,
                email: true,
            },
        },
        messages: {
            phone_number: mobileMessages(),
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
        submitHandler: function (form) {
            $(".loader").fadeIn("slow"); // Show spinner on valid submission
            form.submit(); // Submit the form
        },
    });

    // Provider Transfer Request
    $("#providerTransferCase").validate({
        rules: {
            notes: {
                required: true,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
        },
        messages: {
            notes: {
                required: "Please enter transfer note for admin.",
                minlength: "Minimum 5 characters are needed.",
                maxlength: "Maximum 200 characters are allowed.",
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
        submitHandler: function (form) {
            form.submit();
        },
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
            region: "Select any one region to filter physicians.",
            physician: "Select physician to assign these case.",
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
        submitHandler: function (form) {
            form.submit();
        },
    });

    // Admin Block Case Pop-Up Validation
    $("#adminBlockCase").validate({
        rules: {
            block_reason: {
                required: true,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
        },
        messages: {
            block_reason: {
                required: "Please enter reason for blocking request.",
                minlength: "Minimum 5 characters are needed.",
                maxlength: "Maximum 200 characters are allowed.",
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
        submitHandler: function (form) {
            form.submit();
        },
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
        submitHandler: function (form) {
            form.submit();
        },
        submitHandler: function (form) {
            form.submit();
        },
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
        submitHandler: function (form) {
            $(".loader").fadeIn("slow"); // Show spinner on valid submission
            form.submit(); // Submit the form
        },
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
                alphaNumChar: true,
            },
            date_of_birth: {
                required: true,
                dateRange: [new Date("1900-01-01").toDateString()],
            },
            service_date: {
                required: true,
                serviceDate: "#floatingInput4",
                maxCurrentDate: true,
            },
            mobile: mobileRules(),
            email: emailRules(),
            present_illness_history: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            medical_history: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            medications: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            allergies: {
                required: true,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
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
                alphaSpace: true,
            },
            heent: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            cv: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            chest: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            abd: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            extr: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            skin: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            neuro: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            other: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            diagnosis: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            treatment_plan: {
                required: true,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            medication_dispensed: {
                required: true,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            procedure: {
                required: true,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            followUp: {
                required: true,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
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
            mobile: mobileMessages(),
            email: emailMessages("Email"),
            date_of_birth: {
                required: "Please enter date of birth",
                dateRange:
                    "Date of Birth should be between " +
                    new Date("1900-01-01").toDateString() +
                    " and " +
                    new Date().toDateString(),
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
                min: "Temperature should be between -50 and 50",
                max: "Temperature should be between -50 and 50",
            },
            heart_rate: {
                min: "Heart Rate value should be between 30 and 220",
                max: "Heart Rate value should be between 30 and 220",
            },
            repository_rate: {
                min: "Repository Rate should be between 12 and 40",
                max: "Repository Rate should be between 12 and 40",
            },
            sis_BP: {
                min: "Systolic BP should be between 40 and 250",
                max: "Systolic BP should be between 40 and 250",
            },
            dia_BP: {
                min: "Diastolic BP should be between 40 and 250",
                max: "Diastolic BP should be between 40 and 250",
            },
            oxygen: {
                min: "Oxygen value should be between 70 and 100",
                max: "Oxygen value should be between 70 and 100",
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
        submitHandler: function (form) {
            form.submit();
        },
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
                minlength: 4,
                maxlength: 8,
            },
            prescription: {
                required: false,
                minlength: 5,
                maxlength: 200,
                alphaNumChar: true,
            },
            email: emailRules(),
        },
        messages: {
            profession: {
                required: "Select Profession to get Business/Vendors option",
            },
            vendor_id: {
                required: "Select Particular Business/Vendor to have it's details",
            },
            business_contact: {
                required: "Enter Business Contact to send Order",
            },
            fax_number: {
                required: "Enter Fax number to send order",
                minlength: "Minimum 4 digits required",
                maxlength: "Maximum 8 digits allowed",
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
        submitHandler: function (form) {
            form.submit();
        },
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

    // File Upload Validations
    $.validator.addMethod(
        "customFile",
        function (value, element, param) {
            // Check if a file is selected
            if (element.files.length === 0) {
                return true; // Allow if no file is selected (optional)
            }

            // Get the file extension
            var extension = element.files[0].name
                .split(".")
                .pop()
                .toLowerCase();

            // Allowed extensions
            var allowedExtensions = ["jpg", "jpeg", "png", "pdf", "doc"];

            // Check extension
            if ($.inArray(extension, allowedExtensions) === -1) {
                return false; // Invalid extension
            }

            // Check file size (2MB in bytes)
            var maxSize = 2 * 1024 * 1024;
            if (element.files[0].size > maxSize) {
                return false; // File size too large
            }

            return true; // Valid file
        },
        "Please select a valid file (JPG, PNG, PDF, DOC) with a size less than 2MB."
    );

    $(
        "#adminViewUploadsForm, #providerViewUploadsForm, #concludeCareForm"
    ).validate({
        ignore: [],
        rules: {
            document: {
                required: true,
                customFile: true,
            },
        },
        messages: {
            document: {
                required: "Please Select file to upload",
            },
        },
        errorPlacement: function (error, element) {
            element.closest(".custom-file-input").find(".text-danger").remove();
            let errorDiv = $("<div class='text-danger'></div>");
            errorDiv.append(error);
            element.closest(".custom-file-input").append(errorDiv);
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        submitHandler: function (form) {
            $(".loader").fadeIn("slow"); // Show spinner on valid submission
            form.submit(); // Submit the form
        },
    });

    $("#concludeCareNotes").validate({
        rules: {
            providerNotes: {
                required: false,
                notes: true,
                minlength: 5,
                maxlength: 200,
            },
        },
        messages: {
            providerNotes: {
                minlength: "Minimum 5 characters are required",
                maxlength: "Maximum 200 characters allowed",
            },
        },
        errorPlacement: function (error, element) {
            let errorDiv = $("<div class='text-danger'></div>");
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
            form.submit(); // Submit the form
        },
    });

    $(
        "#adminViewUploadOperationsForm, #providerViewUploadOperationsForm"
    ).validate({
        ignore: [],
        rules: {
            "selected[]": {
                required: {
                    depends: function (element) {
                        $("#sendMailBtn").click(function () {
                            console.log("Send Mail");
                        });
                        // return (
                        //     $(element).closest("form").find('button[value="send_mail"]').val() === "send_mail"
                        // );
                    },
                },
            },
        },
        messages: {
            "selected[]": {
                required:
                    "Please select atleast one document to send mail(Attachment)",
            },
        },
        errorPlacement: function (error, element) {
            let errorDiv = $("<div class='text-danger'></div>");
            errorDiv.append(error);
            $("#error-container").append(errorDiv);
        },
        submitHandler: function (form) {
            // $(".loader").fadeIn("slow"); // Show spinner on valid submission
            form.submit(); // Submit the form
        },
    });

    // Add Business page validation
    $("#addBusinessForm, #updateBusinessForm").validate({
        rules: {
            business_name: {
                required: true,
                minlength: 5,
                maxlength: 20,
                lettersonly: true,
            },
            profession: {
                required: true,
            },
            fax_number: {
                required: true,
                minlength: 4,
                maxlength: 8,
            },
            mobile: mobileRules(),
            email: emailRules(),
            business_contact: {
                required: true,
                minlength: 10,
                maxlength: 10,
            },
            street: {
                required: true,
                minlength: 3,
                maxlength: 25,
                alphaNumChar: true,
            },
            city: {
                required: true,
                minlength: 3,
                maxlength: 25,
                alphaSpace: true,
            },
            state: {
                required: true,
                minlength: 3,
                maxlength: 25,
                alphaSpace: true,
            },
            zip: {
                required: true,
                minlength: 6,
                maxlength: 6,
            },
        },
        messages: {
            business_name: {
                required: "Business name is required",
                minlength: "Mininum 5 characters needed",
                maxlength: "Maximum 20 characters allowed",
            },
            profession: {
                required: "Please select profession",
            },
            fax_number: {
                required: "Fax Number is required",
                minlength: "Minimum 4 digits required",
                maxlength: "Maximum 8 digits allowed",
            },
            mobile: mobileMessages(),
            email: emailMessages(),
            business_contact: {
                required: "Business contact is required",
                minlength: "Business contact should have exactly 10 numbers",
                maxlength: "Business contact should have exactly 10 numbers",
            },
            street: {
                required: "Street is required",
                minlength: "Minimum 3 characters needed",
                maxlength: "Maximum 25 characters allowed",
            },
            city: {
                required: "City is required",
                minlength: "Minimum 3 characters needed",
                maxlength: "Maximum 25 characters allowed",
            },
            state: {
                required: "State is required",
                minlength: "Minimum 3 characters needed",
                maxlength: "Maximum 25 characters allowed",
            },
            zip: {
                required: "Zip is required",
                min: "Please enter positive number with 6 digits",
                minlength: "Minimum 6 digits are required",
                maxlength: "Maximum 6 digits are allowed",
            },
        },
        errorPlacement: function (error, element) {
            let errorDiv = $("<div class='text-danger'></div>");
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

    $("#addBusinessSaveBtn, #updateBusinessSaveBtn").click(function (e) {
        if ($("#addBusinessForm, #updateBusinessForm").valid()) {
            $("#addBusinessForm, #updateBusinessForm").submit();
        }
    });

    $("#createAccessForm, #editAccessForm").validate({
        rules: {
            role: {
                required: true,
                minlength: 3,
                maxlength: 20,
                lettersNum: true,
            },
            role_name: {
                required: true,
            },
            "menu_checkbox[]": {
                required: true,
            },
        },
        messages: {
            role: {
                required: "Please enter role name",
                minlength: "Minimum 3 characters required",
                maxlength: "Maximum 20 characters allowed",
            },
            role_name: {
                required: "Select Account Type",
            },
            "menu_checkbox[]": {
                required: "Select atleast one role to assign",
            },
        },
        errorPlacement: function (error, element) {
            let errorDiv = $("<div class='text-danger'></div>");
            errorDiv.append(error);
            element.closest(".form-floating, .menu-section").append(errorDiv);
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        submitHandler: function (form) {
            form.submit(); // Submit the form
        },
    });

    $("#adminEditCaseForm").validate({
        rules: {
            patient_notes: {
                required: false,
                minlength: 5,
                maxlength: 200,
                notes: true,
            },
            first_name: nameRules(),
            last_name: nameRules(),
            dob: {
                required: true,
                dateRange: [new Date("1900-01-01").toDateString()],
            },
            phone_number: {
                required: true,
                phoneIndia: true,
            },
            email: emailRules(),
        },
        messages: {
            patient_notes: {
                minlength: "Minimum 5 characters are required",
                maxlength: "Maximum 200 characters are allowed",
            },
            first_name: nameMessages("First Name"),
            last_name: nameMessages("Last Name"),
            dob: {
                required: "Date of birth is required",
                dateRange:
                    "Date of Birth should be between " +
                    new Date("1900-01-01").toDateString() +
                    " and " +
                    new Date().toDateString(),
            },
            phone_number: {
                required: "Phone number field can't be empty",
            },
            email: emailMessages(),
        },
        errorPlacement: function (error, element) {
            let errorDiv = $("<div class='text-danger'></div>");
            errorDiv.append(error);
            element.closest(".form-floating, .menu-section").append(errorDiv);
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        submitHandler: function (form) {
            form.submit(); // Submit the form
        },
    });

    $("#providerProfileForm").validate({
        rules: {
            password: {
                required: true,
                minlength: 8,
                maxlength: 100,
            },
        },
        messages: {
            password: {
                minlength: "Minimum 5 characters are required",
                maxlength: "Maximum 100 characters are allowed",
            },
        },
        errorPlacement: function (error, element) {
            let errorDiv = $("<div class='text-danger'></div>");
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
            form.submit(); // Submit the form
        },
    });

    // ------------ PROVIDER/ADMIN SCHEDULING -----------
    // Validation added for shiftEndTime to be greater than shiftStartTime
    $.validator.addMethod(
        "greaterThanStart",
        function (value, element, params) {
            var startTime = $(params).val();

            if (value < startTime) {
                return false;
            }
            return value > startTime;
        },
        "Shift End Time must be greater than Shift Start Time."
    );
    $.validator.addMethod(
        "minTime30End",
        function (value, element, params) {
            var startTime = $(params).val();

            var startTimeParts = startTime.split(":");
            var endTimeParts = value.split(":");
            var startTimeInMinutes =
                parseInt(startTimeParts[0]) * 60 + parseInt(startTimeParts[1]);
            var endTimeInMinutes =
                parseInt(endTimeParts[0]) * 60 + parseInt(endTimeParts[1]);

            if (endTimeInMinutes < startTimeInMinutes + 30) {
                return false;
            }
            return value > startTime;
        },
        "Minimum duration of shift allowed is for 30Minutes."
    );
    // Validation added for shiftStartTime to be always greater than or equal to current time
    $.validator.addMethod(
        "startTime",
        function (value, element, params) {
            const hours = new Date().getHours().toString().padStart(2, "0");
            const minutes = new Date().getMinutes().toString().padStart(2, "0");
            const currentTime = `${hours}:${minutes}`;

            if ($(params).val() != new Date().toISOString().split("T")[0]) {
                return value;
            }

            if (value < currentTime) {
                return false;
            }
            return value;
        },
        "Shift Start Time must be greater than current Time."
    );
    // ----------- ADMIN/PROVIDER SCHEDULING - EDIT SHIFT ---------
    //  Validate Provider Scheduling Edit Shift Pop-up
    $("#providerEditShiftForm, #adminEditShiftForm").validate({
        rules: {
            shiftDate: {
                required: true,
                dateRange: [
                    new Date().toDateString(),
                    new Date("2025-1-1").toDateString(),
                ],
            },
            shiftTimeStart: {
                required: true,
                startTime: ".shiftDate",
            },
            shiftTimeEnd: {
                required: true,
                greaterThanStart: "#startTime", // ShiftStartTime input element
                minTime30End: "#startTime",
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
                greaterThanStart:
                    "Shift End Time must be greater than Shift Start Time.",
            },
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
        submitHandler: function (form) {
            form.submit();
        },
    });

    // ----------- ADMIN/PROVIDER SCHEDULING - ADD NEW SHIFT ---------
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
                dateRange: [
                    new Date().toDateString(),
                    new Date("2025-1-1").toDateString(),
                ],
            },
            shiftStartTime: {
                required: true,
                startTime: ".shiftDate",
            },
            shiftEndTime: {
                required: true,
                greaterThanStart: "#floatingInput2", // ShiftStartTime input element
                minTime30End: "#floatingInput2",
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
            shiftStartTime: {
                required: "Shift Start Time is required",
            },
            shiftEndTime: {
                required: "Shift End Time is required.",
                greaterThanStart:
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
        submitHandler: function (form) {
            form.submit();
        },
    });
});
