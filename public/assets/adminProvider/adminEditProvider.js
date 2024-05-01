$(document).ready(function () {
    // Create Request Upload file, show file name in label
    $("#file-upload-request").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        if (fileName) {
            $(".file-label").text(fileName);
        } else {
            $(".file-label").text("Select File");
        }
    });

    // *code for showing view button in edit provider page as per document is uploaded or not
    $("#view-btn1").each(function (i, el) {
        var isChecked = $(el).closest("tr").find(".checkbox1").is(":checked");
        if (isChecked == false) {
            $(el).hide();
        } else {
            $(el).show();
        }
    });

    $("#view-btn2").each(function (i, el) {
        var isChecked = $(el).closest("tr").find(".checkbox2").is(":checked");

        if (isChecked == false) {
            $(el).hide();
        } else {
            $(el).show();
        }
    });

    $("#view-btn3").each(function (i, el) {
        var isChecked = $(el).closest("tr").find(".checkbox3").is(":checked");
        if (isChecked == false) {
            $(el).hide();
        } else {
            $(el).show();
        }
    });

    // **** This code is for sending msg through pop-up to sendMailToContactProvider Function in adminProvider Page ****

    $(".contact-btn").on("click", function () {
        let id = $(this).data("id");
        const url = `/admin-send-msg-provider/${id}`;
        $("#ContactProviderForm").attr("action", url);
        $(".provider_id").val(id);
    });

    // ** Fetching regions from regions table and display on providers listing *
    $.ajax({
        url: "/admin-new",
        type: "GET",
        success: function (data) {
            data.forEach(function (region) {
                $("#listing-region-admin-provider").append(
                    '<option value="' +
                        region.id +
                        '">' +
                        region.region_name +
                        "</option>"
                );
            });
        },
        error: function (error) {
            console.error(error);
        },
    });

    // * This code is for enabling field in adminProviderEdit Page *
    $("#provider-credentials-edit-btn").click(function () {
        $(".provider-username-field").removeAttr("disabled");
        $("#provider-status").removeAttr("disabled");
        $("#provider_role").removeAttr("disabled");

        $("#providerAccSaveBtn").show();
        $("#providerAccCancelBtn").show();
        $("#provider-credentials-edit-btn").hide();
        $("#provider-reset-password-btn").hide();
    });

    $("#providerAccCancelBtn").click(function () {
        $(".provider-username-field").attr("disabled", true);
        $("#provider-status").attr("disabled", true);
        $("#provider_role").attr("disabled", true);
        $(".provider-password-field").attr("disabled", true);

        $("#providerAccSaveBtn").hide();
        $("#providerAccCancelBtn").hide();
        $("#provider-reset-password-btn").show();
        $("#provider-credentials-edit-btn").show();
    });

    $("#provider-reset-password-btn").click(function () {
        $(".provider-password-field").removeAttr("disabled");

        $("#providerAccSaveBtn").show();
        $("#providerAccCancelBtn").show();
        $("#provider-reset-password-btn").hide();
        $("#provider-credentials-edit-btn").hide();
    });

    $("#provider-info-btn").click(function () {
        $(".provider-firstname").removeAttr("disabled");
        $(".provider-lastname").removeAttr("disabled");
        $(".provider-email").removeAttr("disabled");
        $("#telephone").removeAttr("disabled");
        $(".provider-license").removeAttr("disabled");
        $(".provider-npi").removeAttr("disabled");
        $(".provider-alt-email").removeAttr("disabled");

        $("#providerInfoSaveBtn").show();
        $("#providerInfoCancelBtn").show();
        $("#provider-info-btn").hide();
    });

    $("#providerInfoCancelBtn").click(function () {
        $(".provider-firstname").attr("disabled", true);
        $(".provider-lastname").attr("disabled", true);
        $(".provider-email").attr("disabled", true);
        $("#telephone").attr("disabled", true);
        $(".provider-license").attr("disabled", true);
        $(".provider-npi").attr("disabled", true);
        $(".provider-alt-email").attr("disabled", true);

        $("#providerInfoSaveBtn").hide();
        $("#providerInfoCancelBtn").hide();
        $("#provider-info-btn").show();
    });

    $("#provider-bill-edit-btn").click(function () {
        $(".provider-bill-add1").removeAttr("disabled");
        $(".provider-bill-add2").removeAttr("disabled");
        $(".provider-bill-city").removeAttr("disabled");
        $(".provider-bill-zip").removeAttr("disabled");
        $(".alt-phone-provider").removeAttr("disabled");
        $(".listing-state").removeAttr("disabled");

        $("#providerMailSaveBtn").show();
        $("#providerMailCancelBtn").show();
        $("#provider-bill-edit-btn").hide();
    });

    $("#providerMailCancelBtn").click(function () {
        $(".provider-bill-add1").attr("disabled", true);
        $(".provider-bill-add2").attr("disabled", true);
        $(".provider-bill-city").attr("disabled", true);
        $(".provider-bill-zip").attr("disabled", true);
        $(".alt-phone-provider").attr("disabled", true);
        $(".listing-state").attr("disabled", true);

        $("#providerMailSaveBtn").hide();
        $("#providerMailCancelBtn").hide();
        $("#provider-bill-edit-btn").show();
        console.log("here");
    });

    $("#provider-profile-edit-btn").click(function () {
        $(".business-name").removeAttr("disabled");
        $(".business-web").removeAttr("disabled");
        $(".admin-notes").removeAttr("disabled");
        $("#file-upload-request").removeAttr("disabled");

        $("#providerProfileSaveBtn").show();
        $("#providerProfileCancelBtn").show();
        $("#provider-profile-edit-btn").hide();
    });

    $("#providerProfileCancelBtn").click(function () {
        $(".business-name").attr("disabled", true);
        $(".business-web").attr("disabled", true);
        $(".admin-notes").attr("disabled", true);
        $("#file-upload-request").attr("disabled", true);

        $("#providerProfileSaveBtn").hide();
        $("#providerProfileCancelBtn").hide();
        $("#provider-profile-edit-btn").show();
    });

    // ******

    // ** Fetching regions from regions table and display in edit provider page**
    $.ajax({
        url: "/admin-new",
        type: "GET",
        success: function (data) {
            data.forEach(function (region) {
                var current_value = $(".listing-state").val();
                if (current_value) {
                    if (region.id != current_value) {
                        $(".listing-state").append(
                            '<option value="' +
                                region.id +
                                '">' +
                                region.region_name +
                                "</option>"
                        );
                    }
                } else {
                    $(".listing-state").append(
                        '<option value="' +
                            region.id +
                            '">' +
                            region.region_name +
                            "</option>"
                    );
                }
            });
        },
        error: function (error) {
            console.error(error);
        },
    });

    // * Fetching role from role table display in edit provider page*
    $.ajax({
        url: "/admin-provider/role",
        type: "GET",
        success: function (data) {
            data.forEach(function (role) {
                var currentRoleValue = $("#provider_role").val();
                if (currentRoleValue) {
                    if (role.id != currentRoleValue) {
                        $("#provider_role").append(
                            '<option value="' +
                                role.id +
                                '" class="role_name" >' +
                                role.name +
                                "</option>"
                        );
                    }
                } else {
                    $("#provider_role").append(
                        '<option value="' +
                            role.id +
                            '" class="role_name" >' +
                            role.name +
                            "</option>"
                    );
                }
            });
        },
        error: function (error) {
            console.error(error);
        },
    });
    // ***

    // * check/uncheck  checkbox in adminProviderlisting
    $(".contact-btn[id]").each(function (i, el) {
        // console.log(el);
        var isChecked = $(el).closest("tr").find(".checkbox1").is(":checked");
        // console.log(isChecked);
        if (isChecked == true) {
            $(el).attr("disabled", "true");
        } else {
            $(el).removeAttr("disabled");
        }
    });

    // This code is for enable/disable contact button as per checkbox
    $(document).on("change", ".checkbox1", function (e) {
        var token = $('meta[name="csrf-token"]').attr("content");
        var checkbox = $(this);

        var stopNotificationsCheckId = checkbox.attr("id").split("_")[1];
        var is_notifications = checkbox.prop("checked") ? 1 : 0; // Ternary operator to set is_notify

        $.ajax({
            url: "/admin-providers/stopNotification",
            type: "POST",
            data: {
                stopNotificationsCheckId: stopNotificationsCheckId,
                is_notifications: is_notifications,
                _token: token,
            },
            success: function (response) {
                var contactBtn = $("#contact_btn_" + stopNotificationsCheckId);
                if (is_notifications == 1) {
                    contactBtn.prop("disabled", "disabled");
                } else {
                    contactBtn.removeAttr("disabled");
                }
            },
            error: function (error) {
                console.error("Error updating stop notifications:", error);
            },
        });
    });



    $(document).on("change", ".checkbox2", function (e) {
        var token = $('meta[name="csrf-token"]').attr("content");
        var checkbox = $(this);

        var stopNotificationsCheckId = checkbox.attr("id").split("_")[1];
        var is_notifications = checkbox.prop("checked") ? 1 : 0; // Ternary operator to set is_notify

        $.ajax({
            url: "/admin-providers/stopNotification/mobile",
            type: "POST",
            data: {
                stopNotificationsCheckId: stopNotificationsCheckId,
                is_notifications: is_notifications,
                _token: token,
            },
            success: function (response) {
                var contactButton = $(
                    "#contact_button_" + stopNotificationsCheckId
                );
                if (is_notifications == 1) {
                    contactButton.prop("disabled", "disabled");
                } else {
                    contactButton.removeAttr("disabled");
                }
            },
            error: function (error) {
                console.error("Error updating stop notifications:", error);
            },
        });
    });


    //***  This code is showing contact your provider pop-up ****

    $(document).on("click", ".contact-btn", function () {
        $(".new-provider-pop-up").show();
        $(".overlay").show();
    });
    // ****

    // ****This code is for show independent contractor agreement *****

    $("#independent_contractor").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        if (fileName) {
            $("#Contractor").text(fileName);
        } else {
            $("#Contractor").text("");
        }
    });

    // ****

    // **** This code is for show provider background photo name *****

    $("#background-input").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        if (fileName) {
            $("#Background").text(fileName);
        } else {
            $("#Background").text("");
        }
    });

    // ****

    // ***** This code is for show provider HIPAA Compliance photo name *****

    $("#hipaa-input").change(function () {
        var fileName = $(this).val().split("\\").pop();
        if (fileName) {
            $("#HIPAA").text(fileName);
        } else {
            $("#HIPAA").text("");
        }
    });

    // ****

    // *****This code is for show provider Non-disclosure Agreement photo name ****

    $("#non-disclosure-input").change(function () {
        var fileName = $(this).val().split("\\").pop();
        if (fileName) {
            $(".non-disclosure").text(fileName);
        } else {
            $(".non-disclosure").text("");
        }
    });

    // ****

    // *** This code is for validation in contact provider pop-up
    $.validator.addMethod(
        "contactMsg",
        function (value, element) {
            const regex = /^[a-zA-Z ,_-]+?$/; // Allows letters, spaces, punctuation
            return this.optional(element) || regex.test(value.trim());
        },
        "Please enter alphabets in Contact Message."
    );

    $("#ContactProviderForm").validate({
        rules: {
            contact_msg: {
                required: true,
                minlength: 2,
                maxlength: 100,
                contactMsg: true,
            },
        },
        messages: {
            contact_msg: {
                required: "Please enter a message",
                contactMsg: "Please enter alphabets in Contact Message.",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("text-danger");
            element.closest(".form-floating").append(error);
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

    $(".hide-popup-btn").click(function () {
        $(".pop-up .contact_provider_msg").empty();
        $("#ContactProviderForm").trigger("reset");
        $("#ContactProviderForm").validate().resetForm();
        $(".pop-up form .form-control").removeClass("is-valid");
        $(".pop-up form .form-control").removeClass("is-invalid");
    });

    //** client side validation in adminProviderCreateForm

    $.validator.addMethod(
        "city",
        function (value, element) {
            return /^[a-zA-Z ]+?$/.test(value);
        },
        "Please enter a valid city name with alphabets."
    );

    $.validator.addMethod(
        "state",
        function (value, element) {
            return /^[a-zA-Z ]+?$/.test(value);
        },
        "Please enter a valid state name with alphabets."
    );

    $.validator.addMethod(
        "businessname",
        function (value, element) {
            return value.match(/^[a-zA-Z ,_-]+?$/);
        },
        "Please enter alphabets in business name."
    );

    $.validator.addMethod(
        "webUrl",
        function (value, element) {
            const requiredPrefix = "https://www.";
            if (!value.startsWith(requiredPrefix)) {
                return false;
            }

            // Remaining validation using the original regex (optional)
            const urlRegex =
                /^((https?:\/\/)?(www\.)?([a-zA-Z0-9\-_]+\.)+[a-zA-Z]{2,}|[a-zA-Z0-9]+\.[a-z]{2,})(\/[\w\.-]*)*(\?\S*)?$/;
            return urlRegex.test(value.substring(requiredPrefix.length));
        },
        "Please enter a valid business website URL starting with https://www."
    );

    $.validator.addMethod(
        "address2",
        function (value, element) {
            return value.match(/^[a-zA-Z ,_-]+?$/);
        },
        "Please enter alphabets and space in address2."
    );

    $.validator.addMethod(
        "zipcode",
        function (value, element) {
            return value.length == 6 && /\d/.test(value);
        },
        "Please enter positive number with 6 digits"
    );

    $.validator.addMethod(
        "lettersUserName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only letters for your User name."
    );
    $.validator.addMethod(
        "lettersFirstName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only letters for your first name."
    );

    $.validator.addMethod(
        "emailAddress",
        function (email, element) {
            return (
                this.optional(element) ||
                email.match(/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/)
            );
        },
        "Please enter a valid email (format: alphanum@alpha.domain)."
    );

    $.validator.addMethod(
        "lettersLastName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only letters for your Last name."
    );

    $.validator.addMethod(
        "diseaseSymptoms",
        function (value, element) {
            const regex = match(/^[a-zA-Z ,_-]+?$/); // Allows letters, spaces, punctuation
            return this.optional(element) || regex.test(value.trim());
        },
        "Please enter valid symptoms."
    );

    $.validator.addMethod(
        "roleCheck",
        function (value, element) {
            return value !== "";
        },
        "Please select role."
    );

    $.validator.addMethod(
        "stateCheck",
        function (value, element) {
            return value !== "";
        },
        "Please select a state."
    );

    $.validator.addMethod(
        "atLeastOneChecked",
        function (value, element, options) {
            // Target the checkbox group using the provided name or selector
            const checkboxGroup = $(
                options.group || `[name="${element.name}"]`
            );

            // Check if at least one checkbox is checked within the group
            return checkboxGroup.filter(":checked").length > 0;
        },
        "Please select at least one region."
    );

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

    $.validator.addMethod(
        "address1",
        function (value, element) {
            return value.match(/^[a-zA-Z0-9\s,_-]+?$/);
        },
        "Please enter a only alphabets numbers,comma,underscore,dash,in address1 . "
    );

    $.validator.addMethod(
        "medicalLicense",
        function (value, element) {
            // Regular expression for 10 digits only (no negative signs)
            const regex = /^\d{10}$/;
            return regex.test(value);
        },
        "Please enter a valid 10-digit medical license number."
    );

    $.validator.addMethod(
        "npiNumber",
        function (value, element) {
            // Regular expression for 10 digits only (no negative signs)
            const regex = /^\d{10}$/;
            return regex.test(value);
        },
        "Please enter a valid 10-digit NPI number."
    );

    $.validator.addMethod(
        "phoneIndia",
        function (value, element) {
            return this.optional(element) || iti.isValidNumber();
        },
        "Please enter a valid phone number."
    );

    $.validator.addMethod(
        "password",
        function (email, element) {
            return this.optional(element) || email.match(/^\S(.*\S)?$/);
        },
        "Please enter a valid password"
    );

    $.validator.addMethod(
        "adminNotes",
        function (value, element) {
            const regex = /^[a-zA-Z0-9 \-_.,/]+$/; // Allows letters, spaces, punctuation ,numbers, hyphens, underscores, commas, and forward slashes
            return this.optional(element) || regex.test(value.trim());
        },
        "Please enter alphabets,numbers, hyphens, underscores,fullstop, commas, and forward slashes in admin notes."
    );

    $("#createAdminProvider").validate({
        ignore: [],
        rules: {
            role: {
                required: true,
                roleCheck: true,
            },
            user_name: {
                required: true,
                minlength: 3,
                maxlength: 40,
                lettersUserName: true,
            },
            "region_id[]": {
                atLeastOneChecked: true,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 50,
                password: true,
            },
            first_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersFirstName: true,
            },
            last_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersLastName: true,
            },
            email: {
                required: true,
                minlength: 2,
                maxlength: 40,
                emailAddress: true,
            },
            phone_number: {
                required: true,
                phoneIndia: true,
            },
            phone_number_alt: {
                required: true,
                minlength: 10,
                maxlength: 10,
            },
            medical_license: {
                required: true,
                medicalLicense: true,
            },
            npi_number: {
                required: true,
                npiNumber: true,
            },
            address1: {
                required: true,
                minlength: 2,
                maxlength: 50,
                address1: true,
            },
            address2: {
                required: true,
                minlength: 2,
                maxlength: 30,
                address2: true,
            },
            city: {
                required: true,
                minlength: 2,
                maxlength: 30,
                city: true,
            },
            select_state: {
                required: true,
                stateCheck: true,
            },
            zip: {
                required: true,
                zipcode: true,
            },
            phone_number_alt: {
                required: true,
                minlength: 10,
                maxlength: 10,
            },
            business_name: {
                required: true,
                minlength: 3,
                maxlength: 30,
                businessname: true,
            },
            business_website: {
                required: true,
                minlength: 10,
                maxlength: 50,
                webUrl: true,
            },
            admin_notes: {
                required: true,
                minlength: 5,
                maxlength: 200,
                adminNotes: true,
            },
            provider_photo: {
                customFile: true,
            },
            independent_contractor: {
                customFile: true,
            },
            background_doc: {
                customFile: true,
            },
            hipaa_docs: {
                customFile: true,
            },
            non_disclosure_doc: {
                customFile: true,
            },
        },
        messages: {
            role: {
                required: "Please select role",
                roleCheck: "Please select role",
            },
            user_name: {
                required: "Please enter a username",
            },
            password: {
                required: "Please enter a password",
            },
            first_name: {
                required: "Please enter a first name",
                lettersFirstName:
                    "Please enter only letters for your first name.",
            },
            last_name: {
                required: "Please enter a last name",
                lettersLastName:
                    "Please enter only letters for your Last name.",
            },
            email: {
                required: "Please enter a valid email",
                emailAddress:
                    "Please enter a valid email (format: alphanum@alpha.domain).",
            },
            phone_number: {
                required: "Please enter a valid phone number",
                phoneIndia: "Please enter a valid phone number.",
            },
            medical_license: {
                required: "Please enter a valid medical license",
                medicalLicense:
                    "Please enter a valid 10-digit medical license number.",
            },
            npi_number: {
                required: "Please enter a valid npi number",
                npiNumber: "Please enter a valid 10-digit NPI number.",
            },
            address1: {
                required: "Please enter a valid address1",
                address1:
                    "Please enter a only alphabets numbers,comma,underscore,dash,in address1 . ",
            },
            address2: {
                required: "Please enter a valid address2",
                address2: "Please enter alphabets and space in address2.",
            },
            city: {
                required: "Please enter a city",
                city: "Please enter alpbabets in city name.",
            },
            state: {
                required: "Please enter a state",
                state: "Please enter alpbabets in state name.",
            },
            select_state: {
                required: "Please select state",
                stateCheck: true,
            },
            zip: {
                required: "Please enter a valid zipcode",
                min: "Please enter positive number with 6 digits",
                zipcode: "Please enter positive number with 6 digits",
            },
            phone_number_alt: {
                required: "Please enter a valid alternate phone number",
                min: "Please enter a 10 digit positive number in alternate phone number.",
                minlength: "Please enter exactly 10 digits in phone number",
                maxlength: "Please enter exactly 10 digits in phone number",
            },
            business_name: {
                required: "Please enter a valid business name",
                businessname: "Please enter alphabets in business name.",
            },
            business_website: {
                required: "Please enter a valid business website",
            },
            provider_photo: {
                customFile:
                    "Please select a valid file (JPG, PNG, PDF, DOC) with a size less than 2MB.",
            },
            admin_notes: {
                required: "Please enter a valid Admin Notes",
                adminNotes:
                    "Please enter alphabets,numbers, hyphens, underscores,fullstop, commas, and forward slashes in admin notes.",
            },
            independent_contractor: {
                customFile:
                    "The independent contractor field must be a (JPG, PNG, PDF, DOC) with a size less than 2MB.",
            },
            background_doc: {
                customFile:
                    "The background check field must be a (JPG, PNG, PDF, DOC) with a size less than 2MB..",
            },
            hipaa_docs: {
                customFile:
                    "The hipaa docs field must be a (JPG, PNG, PDF, DOC) with a size less than 2MB.",
            },
            non_disclosure_doc: {
                customFile:
                    "The non disclosure doc field must be a (JPG, PNG, PDF, DOC) with a size less than 2MB.",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("text-danger");
            element.closest(".provider-form").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
});

// *edit provider account information

$(document).ready(function () {
    $.validator.addMethod(
        "lettersUserName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only letters for your User name."
    );

    $.validator.addMethod(
        "password",
        function (email, element) {
            return this.optional(element) || email.match(/^\S(.*\S)?$/);
        },
        "Please enter a valid password"
    );

    $.validator.addMethod(
        "roleCheck",
        function (value, element) {
            return value !== "";
        },
        "Please select a role."
    );

    $.validator.addMethod(
        "statusCheck",
        function (value, element) {
            return value !== "";
        },
        "Please select a status."
    );

    $("#adminEditProviderForm1").validate({
        rules: {
            user_name: {
                required: true,
                minlength: 3,
                maxlength: 60,
                lettersUserName: true,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 50,
                password: true,
            },
            status_type: {
                required: true,
                statusCheck: true,
            },
            role: {
                required: true,
                roleCheck: true,
            },
        },
        messages: {
            user_name: {
                required: "Please enter a valid username",
            },
            password: {
                required: "Please enter a password",
            },
            status_type: {
                required: "Please Select Status",
                statusCheck: "Please Select Status",
            },
            role: {
                required: "Please select role",
                roleCheck: "Please select role",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("text-danger");
            element.closest(".provider-edit-form").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
});

// * edit physician information
$(document).ready(function () {
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

    $.validator.addMethod(
        "lettersFirstName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only letters for your first name."
    );

    $.validator.addMethod(
        "emailAddress",
        function (email, element) {
            return (
                this.optional(element) ||
                email.match(/^([a-zA-Z0-9._%+-]+@[a-zA-Z]+\.[a-zA-Z]{2,})$/)
            );
        },
        "Please enter a valid email (format: alphanum@alpha.domain)."
    );

    $.validator.addMethod(
        "lettersLastName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only letters for your Last name."
    );

    $.validator.addMethod(
        "phoneIndia",
        function (value, element) {
            return this.optional(element) || iti.isValidNumber();
        },
        "Please enter a valid phone number."
    );

    $.validator.addMethod(
        "medicalLicense",
        function (value, element) {
            // Regular expression for 10 digits only (no negative signs)
            const regex = /^\d{10}$/;
            return regex.test(value);
        },
        "Please enter a valid 10-digit medical license number."
    );

    $.validator.addMethod(
        "npiNumber",
        function (value, element) {
            // Regular expression for 10 digits only (no negative signs)
            const regex = /^\d{10}$/;
            return regex.test(value);
        },
        "Please enter a valid 10-digit NPI number."
    );

    $("#adminEditProviderForm2").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersFirstName: true,
            },
            last_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersLastName: true,
            },
            email: {
                required: true,
                minlength: 2,
                maxlength: 40,
                emailAddress: true,
            },
            phone_number: {
                required: true,
                phoneIndia: true,
            },
            medical_license: {
                required: true,
                medicalLicense: true,
            },
            npi_number: {
                required: true,
                npiNumber: true,
            },
        },
        messages: {
            first_name: {
                required: "Please enter a valid first_name",
            },
            last_name: {
                required: "Please enter a valid last_name",
            },
            email: {
                required: "Please enter a valid email",
            },
            phone_number: {
                required: "Please enter a valid phone_number",
            },
            medical_license: {
                required: "Please enter a valid medical_license",
                medicalLicense:
                    "Please enter a valid 10-digit medical license number.",
            },
            npi_number: {
                required: "Please enter a valid npi_number",
                npiNumber: "Please enter a valid 10-digit NPI number.",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("text-danger");
            element.closest(".provider-edit-form").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
});

// ** edit providers mailing and billing information
$(document).ready(function () {
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

    $.validator.addMethod(
        "city",
        function (value, element) {
            return value.match(/^[a-zA-Z ]+?$/);
        },
        "Please enter alphabets in city name."
    );

    $.validator.addMethod(
        "state",
        function (value, element) {
            return value.match(/^[a-zA-Z ]+?$/);
        },
        "Please enter alphabets in state name."
    );

    $.validator.addMethod(
        "zipcode",
        function (value, element) {
            return value.length == 6 && /\d/.test(value);
        },
        "Please enter a valid zipcode."
    );

    $.validator.addMethod(
        "address1",
        function (value, element) {
            return value.match(/^[a-zA-Z0-9\s,_-]+?$/);
        },
        "Please enter a only alphabets numbers ,space,-,_in address1 . "
    );

    $.validator.addMethod(
        "stateCheck",
        function (value, element) {
            return value !== "";
        },
        "Please select a state."
    );

    $.validator.addMethod(
        "phoneIndia",
        function (value, element) {
            return this.optional(element) || iti.isValidNumber();
        },
        "Please enter a valid phone number."
    );

    $.validator.addMethod(
        "address2",
        function (value, element) {
            return value.match(/^[a-zA-Z ,_-]+?$/);
        },
        "Please enter alphabets and space in address2."
    );

    $("#adminEditProviderForm3").validate({
        rules: {
            address1: {
                required: true,
                minlength: 2,
                maxlength: 50,
                address1: true,
            },
            address2: {
                required: true,
                minlength: 2,
                maxlength: 30,
                address2: true,
            },
            city: {
                required: true,
                minlength: 2,
                maxlength: 30,
                city: true,
            },
            zip: {
                required: true,
                zipcode: true,
            },
            alt_phone_number: {
                required: true,
                minlength: 10,
                maxlength: 10,
            },
            regions: {
                required: true,
                stateCheck: true,
            },
        },
        messages: {
            address1: {
                required: "Please enter a valid address1",
                address1:
                    "Please enter a only alphabets numbers,comma,underscore,dash,in address1 . ",
            },
            address2: {
                required: "Please enter a valid address2",
                address2: "Please enter alphabets and space in address2.",
            },
            city: {
                required: "Please enter a city",
                city: "Please enter alpbabets in city name.",
            },
            regions: {
                required: "Please enter a state",
                stateCheck: "Please select a state.",
            },
            zip: {
                required: "Please enter a valid zipcode",
                min: "Please enter positive number with 6 digits",
            },
            alt_phone_number: {
                required: "Please enter a valid alt_phone_number",
                min: "Please enter a 10 digit positive number in alternate phone number.",
                minlength: "Please enter exactly 10 digits in phone number",
                maxlength: "Please enter exactly 10 digits in phone number",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("text-danger");
            element.closest(".provider-edit-form").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
});

// * edit provider profile

$(document).ready(function () {
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

    $.validator.addMethod(
        "businessname",
        function (value, element) {
            return value.match(/^[a-zA-Z ,_-]+?$/);
        },
        "Please enter a valid business name."
    );

    $.validator.addMethod(
        "webUrl",
        function (value, element) {
            const requiredPrefix = "https://www.";
            if (!value.startsWith(requiredPrefix)) {
                return false;
            }

            // Remaining validation using the original regex (optional)
            const urlRegex =
                /^((https?:\/\/)?(www\.)?([a-zA-Z0-9\-_]+\.)+[a-zA-Z]{2,}|[a-zA-Z0-9]+\.[a-z]{2,})(\/[\w\.-]*)*(\?\S*)?$/;
            return urlRegex.test(value.substring(requiredPrefix.length));
        },
        "Please enter a valid business website URL starting with https://www."
    );

    $.validator.addMethod(
        "adminNotes",
        function (value, element) {
            const regex = /^[a-zA-Z0-9 \-_.,/]+$/; // Allows letters, spaces, punctuation ,numbers, hyphens, underscores, commas, and forward slashes
            return this.optional(element) || regex.test(value.trim());
        },
        "Please enter alphabets,numbers,hyphens,underscores,commas,fullstop and forward slashes in admin notes."
    );

    $("#adminEditProviderForm4").validate({
        ignore: [],
        rules: {
            business_name: {
                required: true,
                minlength: 3,
                maxlength: 30,
                businessname: true,
            },
            business_website: {
                required: false,
                minlength: 10,
                maxlength: 40,
                webUrl: true,
            },
            admin_notes: {
                required: true,
                minlength: 5,
                maxlength: 200,
                adminNotes: true,
            },
            provider_photo: {
                customFile: true,
            },
        },
        messages: {
            business_name: {
                required: "Please enter a valid business_name",
            },
            business_website: {
                required: "Please enter a valid business_website",
            },
            admin_notes: {
                required: "Please enter a valid Admin_Notes",
                adminNotes:
                    "Please enter alphabets,numbers, hyphens, underscores,fullstop, commas, and forward slashes in admin notes.",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("text-danger");
            element.closest(".provider-edit-form").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
});

// * edit onboarding information
$(document).ready(function () {
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

    $("#adminEditProviderForm5").validate({
        ignore: [],
        rules: {
            independent_contractor: {
                customFile: true,
            },
            background_doc: {
                customFile: true,
            },
            hipaa_docs: {
                customFile: true,
            },
            non_disclosure_doc: {
                customFile: true,
            },
        },
        messages: {
            independent_contractor: {
                customFile:
                    "The independent contractor field must be a (JPG, PNG, PDF, DOC) with a size less than 2MB.",
            },
            background_doc: {
                customFile:
                    "The background check field must be a (JPG, PNG, PDF, DOC) with a size less than 2MB.",
            },
            hipaa_docs: {
                customFile:
                    "The hipaa docs field must be a (JPG, PNG, PDF, DOC) with a size less than 2MB.",
            },
            non_disclosure_doc: {
                customFile:
                    "The non disclosure doc field must be a (JPG, PNG, PDF, DOC) with a size less than 2MB.",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("text-danger");
            element.closest(".provider-edit-form").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
});

// * filter provider according to regions
function fetchPaginatedResults(selectedId, page) {
    var token = $('meta[name="csrf-token"]').attr("content");

    $.ajax({
        url: "/admin-providers/regionsFiltering?page=" + page,
        type: "POST",
        dataType: "json",
        data: {
            selectedId: selectedId,
            _token: token,
        },
        success: function (response) {
            $("#adminProviderData").html(response.html); // Update results area
        },
    });
}

$(document).on("click", ".pagination .page-link", function (event) {
    event.preventDefault();

    var page;
    page = $(this).text();

    if (page === "›") {
        // Get the <li> element with the class "active"
        var activeListItem = $(".pagination .page-item.active");

        // Get the next sibling of the active <li> element
        var nextSibling = activeListItem.next();

        // Check if the next sibling exists and does not have the "active" class
        if (nextSibling.length && !nextSibling.hasClass("active")) {
            // Get the value of the next sibling
            var page = nextSibling.first(".page-link").text();
        } else {
            console.log("There is no next sibling without the 'active' class.");
        }
    } else if (page === "‹") {
        // Get the <li> element with the class "active"
        var activeListItem = $(".pagination .page-item.active");

        // Get the next sibling of the active <li> element
        var prevSibling = activeListItem.prev();

        // Check if the next sibling exists and does not have the "active" class
        if (prevSibling.length && !prevSibling.hasClass("active")) {
            // Get the value of the next sibling
            var page = prevSibling.first(".page-link").text();
        } else {
            console.log("There is no next sibling without the 'active' class.");
        }
    }

    var selectedId = $("#listing-region-admin-provider").val();
    fetchPaginatedResults(selectedId, page);
});

$("#listing-region-admin-provider").on("change", function (event) {
    event.preventDefault();

    var selectedId = $(this).val();
    fetchPaginatedResults(selectedId, 1);
});

function fetchPaginatedResultsMobileView(selectedId, page) {
    var token = $('meta[name="csrf-token"]').attr("content");

    $.ajax({
        url: "/admin-providers-regionsFiltering-mobile?page=" + page,
        type: "POST",
        dataType: "json",
        data: {
            selectedId: selectedId,
            _token: token,
        },
        success: function (response) {
            $(".mobile-listing").html(response.html); // Update results area

            $(".main-section").click(function () {
                // Target the next sibling .more-info element specifically
                $(this).next(".details").toggleClass("active");

                $(".details")
                    .not($(this).next(".details"))
                    .removeClass("active");
            });
        },
    });
}

$(document).on("click", ".pagination .page-link", function (event) {
    event.preventDefault();
    var page;
    page = $(this).text();

    if (page === "›") {
        // Get the <li> element with the class "active"
        var activeListItem = $(".pagination .page-item.active");

        // Get the next sibling of the active <li> element
        var nextSibling = activeListItem.next();

        // Check if the next sibling exists and does not have the "active" class
        if (nextSibling.length && !nextSibling.hasClass("active")) {
            // Get the value of the next sibling
            var page = nextSibling.first(".page-link").text();
        } else {
            console.log("There is no next sibling without the 'active' class.");
        }
    } else if (page === "‹") {
        // Get the <li> element with the class "active"
        var activeListItem = $(".pagination .page-item.active");

        // Get the next sibling of the active <li> element
        var prevSibling = activeListItem.prev();

        // Check if the next sibling exists and does not have the "active" class
        if (prevSibling.length && !prevSibling.hasClass("active")) {
            // Get the value of the next sibling
            var page = prevSibling.first(".page-link").text();
        } else {
            console.log("There is no next sibling without the 'active' class.");
        }
    }

    var selectedId = $("#listing-region-admin-provider").val();
    fetchPaginatedResultsMobileView(selectedId, page);
});

$("#listing-region-admin-provider").on("change", function (event) {
    event.preventDefault();
    var selectedId = $(this).val();
    fetchPaginatedResultsMobileView(selectedId, 1);
});
