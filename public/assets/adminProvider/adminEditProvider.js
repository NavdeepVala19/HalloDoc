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

    // **** This code is for sending throug pop-up to sendMailToContactProvider Function in adminProvider Page ****

    $(".contact-btn").on("click", function () {
        let id = $(this).data("id");
        const url = `/admin/provider/${id}`;
        $("#ContactProviderForm").attr("action", url);
        $(".provider_id").val(id);
    });

    // ********************************************************************************

    // **** This code is for enabling field in adminProviderEdit Page ****
    $("#provider-credentials-edit-btn").click(function () {
        $(".provider-username-field").removeAttr("disabled");
        $("#provider-status").removeAttr("disabled");
        $("#provider-role").removeAttr("disabled");

        $("#providerAccSaveBtn").show();
        $("#providerAccCancelBtn").show();
        $("#provider-credentials-edit-btn").hide();
        $("#provider-reset-password-btn").hide();
    });

    $("#providerAccCancelBtn").click(function () {
        $(".provider-username-field").attr("disabled");
        $("#provider-status").attr("disabled");
        $("#provider-role").attr("disabled");

        $("#providerAccSaveBtn").hide();
        $("#providerAccCancelBtn").hide();
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
        $(".provider-firstname").attr("disabled");
        $(".provider-lastname").attr("disabled");
        $(".provider-email").attr("disabled");
        $("#telephone").attr("disabled");
        $(".provider-license").attr("disabled");
        $(".provider-npi").attr("disabled");
        $(".provider-alt-email").attr("disabled");

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
        $(".listing-region").removeAttr("disabled");

        $("#providerMailSaveBtn").show();
        $("#providerMailCancelBtn").show();
        $("#provider-bill-edit-btn").hide();
    });

    $("#providerMailCancelBtn").click(function () {
        $(".provider-bill-add1").attr("disabled");
        $(".provider-bill-add2").attr("disabled");
        $(".provider-bill-city").attr("disabled");
        $(".provider-bill-zip").attr("disabled");
        $(".alt-phone-provider").attr("disabled");
        $(".listing-region").attr("disabled");

        $("#providerMailSaveBtn").hide();
        $("#providerMailCancelBtn").hide();
        $("#provider-bill-edit-btn").show();
    });

    $("#provider-profile-edit-btn").click(function () {
        $(".business-name").removeAttr("disabled");
        $(".business-web").removeAttr("disabled");
        $(".admin-notes").removeAttr("disabled");

        $("#providerProfileSaveBtn").show();
        $("#providerProfileCancelBtn").show();
        $("#provider-profile-edit-btn").hide();
    });

    $("#provider-profile-edit-btn").click(function () {
        $(".business-name").removeAttr("disabled");
        $(".business-web").removeAttr("disabled");
        $(".admin-notes").removeAttr("disabled");

        $("#providerProfileSaveBtn").show();
        $("#providerProfileCancelBtn").show();
        $("#provider-profile-edit-btn").hide();
    });

    $("#providerProfileCancelBtn").click(function () {
        $(".business-name").removeAttr("disabled");
        $(".business-web").removeAttr("disabled");
        $(".admin-notes").removeAttr("disabled");

        $("#providerProfileSaveBtn").hid();
        $("#providerProfileCancelBtn").hide();
        $("#provider-profile-edit-btn").show();
    });

    // ******

    // ***** Fetching regions from regions table *****
    $.ajax({
        url: "/admin-new",
        type: "GET",
        success: function (data) {
            data.forEach(function (region) {
                $("#listing-region-admin-provider").append(
                    '<option value="' +
                        region.id +
                        '" class="regions-name" >' +
                        region.region_name +
                        "</option>"
                );
            });
        },
        error: function (error) {
            console.error(error);
        },
    });
    // ********



    // **** Fetching role from role table ****
    
    $.ajax({
        url: "/admin-provider/role",
        type: "POST",
        success: function (data) {
            data.forEach(function (role) {
                $("#provider-role").append(
                    '<option value="' +
                        role.id +
                        '" class="role_name" >' +
                        role.name +
                        "</option>"
                );
            });
        },
        error: function (error) {
            console.error(error);
        },
    });
    // ******

    // **** Filtering Data according to selected region from dropdown button in adminProvider Page ****

    // $('#listing-region-admin-provider').on('change', function () {
    //     var token = $('meta[name="csrf-token"]').attr('content')
    //     var selectedId = $(this).val();

    //     $.ajax({
    //         url: "/admin/providers/regionsFiltering",
    //         type: "POST",
    //         dataType: 'json',
    //         data: {
    //             regionId: selectedId,
    //             "_token": token
    //         },
    //         success: function (data) {
    //             $('#all-providers-data').html(data.html)
    //         },
    //         error: function (error) {
    //             console.error(error);
    //         }
    //     });
    // })

    // ******

    $(".contact-btn[id]").each(function (i, el) {
        var isChecked = $(el).closest("tr").find(".checkbox1").is(":checked");

        if (isChecked) {
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

    //***  This code is showing contact your provider pop-up ****

    $(document).on("click", ".contact-btn", function () {
        $(".new-provider-pop-up").show();
        $(".overlay").show();
    });
    // ****

    // *** This code is for show provider photo name ***

    $(".file-input-provider_photo").change(function (e) {
        const filename = e.target.files[0].name;
        $("#provider_photo").text(filename);
    });

    // ***

    // ****This code is for show independent contractor agreement *****

    $("#independent_contractor").change(function (e) {
        const filename = e.target.files[0].name;
        $("#Contractor").text(filename);
    });

    // ****

    // **** This code is for show provider background photo name *****

    $("#background-input").change(function (e) {
        const filename = e.target.files[0].name;
        $("#Background").text(filename);
    });

    // ****

    // ***** This code is for show provider HIPAA Compliance photo name *****

    $("#hipaa-input").change(function (e) {
        const filename = e.target.files[0].name;
        $("#HIPAA").text(filename);
    });

    // ****

    // *****This code is for show provider Non-disclosure Agreement photo name ****

    $("#non-disclosure-input").change(function (e) {
        const filename = e.target.files[0].name;
        $(".non-disclosure").text(filename);
    });

    // ****

    // *** This code is for show provider License  Agreement photo name ***

    $("#license-input").change(function (e) {
        const filename = e.target.files[0].name;
        $(".license").text(filename);
    });

    // ****

    // *** This code is for validation in contact provider pop-up

    $("#ContactProviderForm").validate({
        rules: {
            contact_msg: {
                required: true,
                minlength: 2,
                maxlength: 100,
            },
        },
        messages: {
            contact_msg: {
                required: "Please enter a message",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("errorMsg");
            element.closest(".form-floating").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });

    // client side validation in adminProviderCreateForm

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
            return value.match(/^[a-zA-Z ,_-]+?$/);
        },
        "Please enter a valid city name."
    );

    $.validator.addMethod(
        "businessname",
        function (value, element) {
            return value.match(/^[a-zA-Z ,_-]+?$/);
        },
        "Please enter a valid business name."
    );

    $.validator.addMethod(
        "address2",
        function (value, element) {
            return value.match(/^[a-zA-Z ,_-]+?$/);
        },
        "Please enter a valid address2."
    );

    
     $.validator.addMethod(
         "address1",
         function (value, element) {
             return value.match(/^[a-zA-Z0-9-, ]+$/);
         },
         "Please enter a valid address1."
     );

    $.validator.addMethod(
        "zipcode",
        function (value, element) {
            return value.length == 6 && /\d/.test(value);
        },
        "Please enter a valid zipcode."
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
          "password",
          function (email, element) {
              return this.optional(element) || email.match(/^\S(.*\S)?$/);
          },
          "Please enter a valid password"
      );

    $("#createAdminProvider").validate({
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
                maxlength: 20,
                password:true,
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
                emailAddress: true,
            },
            phone_number: {
                required: true,
                phoneUS: true,
            },
            medical_license: {
                required: true,
                minlength: 3,
                maxlength: 20,
            },
            npi_number: {
                required: true,
                minlength: 3,
                maxlength: 30,
            },
            address1: {
                required: true,
                minlength: 2,
                maxlength: 30,
                address1:true
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
                phoneUS: true,
            },
            select_state: {
                required: true,
            },
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
            },
            admin_notes: {
                required: false,
                minlength: 5,
                maxlength: 100,
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
        message: {
            user_name: {
                required: "Please enter a valid username",
            },
            password: {
                required: "Please enter a password",
            },
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
            },
            npi_number: {
                required: "Please enter a valid npi_number",
            },
            address1: {
                required: "Please enter a valid address1",
            },
            address2: {
                required: "Please enter a valid address2",
            },
            city: {
                required: "Please enter a valid city",
            },
            zip: {
                required: "Please enter a valid zipcode",
            },
            alt_phone_number: {
                required: "Please enter a valid alt_phone_number",
            },
            business_name: {
                required: "Please enter a valid business_name",
            },
            business_website: {
                required: "Please enter a valid business_website",
            },
            admin_notes: {
                required: "Please enter a valid Admin_Notes",
            },
            select_state: {
                required: "Please select the state",
            },
            independent_contractor: {
                customFile:
                    "The independent contractor field must be a file of type: jpg, png, jpeg, pdf, doc.",
            },
            background_doc: {
                customFile:
                    "The background check field must be a file of type: jpg, png, jpeg, pdf, doc.",
            },
            hipaa_docs: {
                customFile:
                    "The hipaa docs field must be a file of type: jpg, png, jpeg, pdf, doc.",
            },
            non_disclosure_doc: {
                customFile:
                    "The non disclosure doc field must be a file of type: jpg, png, jpeg, pdf, doc.",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("errorMsg");
            element.closest(".form-floating").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
});

// $(document).ready(function () {
//     const fetch_data = (regions, page) => {
//         $.ajax({
//             url: "/admin/providers/regionsFiltering?page="+page,
//             type: "POST",
//             success: function (data) {
//                 $('#adminProviderData').html(data.html)
//             },
//             error: function (error) {
//                 console.error(error);
//             }
//         })
//     }

//     $('#listing-region-admin-provider').on('change',function (e) {
//         e.preventDefault();
//         var token = $('meta[name="csrf-token"]').attr('content')
//         var selectedId = $(this).val();
//         var page = $('#hidden_page').val();
//         fetch_data(page, regions);
//     });

//     $('#listing-region-admin-provider').on('click', '.pager a', function (event) {
//         event.preventDefault();
//         var token = $('meta[name="csrf-token"]').attr('content')
//         var selectedId = $(this).val();
//         var page = $(this).attr('href').split('page=')[1];
//         $('#hidden_page').val(page);
//         fetch_data(page, regions);
//     });
// })

// $('#listing-region-admin-provider').on('change', function () {
//     var token = $('meta[name="csrf-token"]').attr('content')
//     var selectedId = $(this).val();

//     $.ajax({
//         url: "/admin/providers/regionsFiltering",
//         type: "POST",
//         dataType: 'json',
//         data: {
//             regionId: selectedId,
//             "_token": token
//         },
//         success: function (data) {
//             $('#adminProviderData').html(data.html)
//         },
//         error: function (error) {
//             console.error(error);
//         }
//     });
// })

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
                maxlength: 20,
                password: true,
            },
        },
        message: {
            user_name: {
                required: "Please enter a valid username",
            },
            password: {
                required: "Please enter a password",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("errorMsg");
            element.closest(".form-floating").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
});

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
                emailAddress: true,
            },
            phone_number: {
                required: true,
                phoneUS: true,
            },
            medical_license: {
                required: true,
                minlength: 3,
                maxlength: 20,
            },
            npi_number: {
                required: true,
                minlength: 3,
                maxlength: 30,
            },
        },
        message: {
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
            },
            npi_number: {
                required: "Please enter a valid npi_number",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("errorMsg");
            element.closest(".form-floating").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
});

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
            return value.match(/^[a-zA-Z ,_-]+?$/);
        },
        "Please enter a valid city name."
    );

    $.validator.addMethod(
        "zipcode",
        function (value, element) {
            return value.length == 6 && /\d/.test(value);
        },
        "Please enter a valid zipcode."
    );

     $.validator.addMethod(
         "address2",
         function (value, element) {
             return value.match(/^[a-zA-Z ,_-]+?$/);
         },
         "Please enter a valid address2."
     );

     $.validator.addMethod(
         "address1",
         function (value, element) {
             return value.match(/^[a-zA-Z0-9-, ]+$/);
         },
         "Please enter a valid address1."
     );
    
    $("#adminEditProviderForm3").validate({
        rules: {
            address1: {
                required: true,
                minlength: 2,
                maxlength: 30,
                address1:true,
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
                phoneUS: true,
            },
            select_state: {
                required: true,
            },
        },
        message: {
            address1: {
                required: "Please enter a valid address1",
            },
            address2: {
                required: "Please enter a valid address2",
            },
            city: {
                required: "Please enter a valid city",
            },
            zip: {
                required: "Please enter a valid zipcode",
            },
            alt_phone_number: {
                required: "Please enter a valid alt_phone_number",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("errorMsg");
            element.closest(".form-floating").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
});

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

    $("#adminEditProviderForm4").validate({
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
            },
            admin_notes: {
                required: false,
                minlength: 5,
                maxlength: 100,
            },
            provider_photo: {
                customFile: true,
            },
        },
        message: {
            business_name: {
                required: "Please enter a valid business_name",
            },
            business_website: {
                required: "Please enter a valid business_website",
            },
            admin_notes: {
                required: "Please enter a valid Admin_Notes",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("errorMsg");
            element.closest(".form-floating").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });

});


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
        message: {
            independent_contractor: {
                customFile:
                    "The independent contractor field must be a file of type: jpg, png, jpeg, pdf, doc.",
            },
            background_doc: {
                customFile:
                    "The background check field must be a file of type: jpg, png, jpeg, pdf, doc.",
            },
            hipaa_docs: {
                customFile:
                    "The hipaa docs field must be a file of type: jpg, png, jpeg, pdf, doc.",
            },
            non_disclosure_doc: {
                customFile:
                    "The non disclosure doc field must be a file of type: jpg, png, jpeg, pdf, doc.",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("errorMsg");
            element.closest(".form-floating").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
});


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
    var page = $(this).text();
    // console.log(page);

    if (page === "›") {
        console.log("here");
        var page = $(".pagination li.active span").text();
        console.log(page);
    }

    var selectedId = $("#listing-region-admin-provider").val();
    fetchPaginatedResult(selectedId, page);
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
    var page = $(this).text();
    var selectedId = $("#listing-region-admin-provider").val();
    fetchPaginatedResultsMobileView(selectedId, page);
});

$("#listing-region-admin-provider").on("change", function (event) {
    event.preventDefault();
    var selectedId = $(this).val();
    fetchPaginatedResultsMobileView(selectedId, 1);
});


