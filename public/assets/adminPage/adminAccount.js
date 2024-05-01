// ** This code is for client side validation in admin create account 

$(document).ready(function () {
    // **** Fetching regions from regions table ****
    $.ajax({
        url: "/admin-account-state",
        type: "GET",
        success: function (data) {
            data.forEach(function (region) {
                $("#listing_state_admin_account").append(
                    '<option value="' +
                        region.id +
                        '" class="state-name" >' +
                        region.region_name +
                        "</option>"
                );
            });
        },
        error: function (error) {
            console.error(error);
        },
    });

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
        "password",
        function (email, element) {
            return this.optional(element) || email.match(/^\S(.*\S)?$/);
        },
        "Please enter a valid password"
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
        "roleCheck",
        function (value, element) {
            return value !== "";
        },
        "Please select a role."
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
        "address2",
        function (value, element) {
            return value.match(/^[a-zA-Z ,_-]+?$/);
        },
        "Please enter a alphabets in address2."
    );

    $.validator.addMethod(
        "address1",
        function (value, element) {
            return value.match(/^[a-zA-Z0-9\s,_-]+?$/);
        },
        "Please enter a valid address1."
    );

    $.validator.addMethod(
        "phoneIndia",
        function (value, element) {
            return this.optional(element) || iti.isValidNumber();
        },
        "Please enter a valid phone number."
    );

    $.validator.addMethod(
        "street",
        function (value, element) {
            return value.match(/^[a-zA-Z0-9\s,_-]+?$/);
        },
        "Please enter alphabets,dash,underscore,space and numbers in address1. "
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

    $("#createAdminAccountForm").validate({
        rules: {
            role: {
                required: true,
                roleCheck: true,
            },
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
            confirm_email: {
                required: true,
                equalTo: ".email",
            },
            phone_number: {
                required: true,
                phoneIndia: true,
            },
            alt_mobile: {
                required: true,
                maxlength: 10,
                minlength: 10,
            },
            address1: {
                required: true,
                minlength: 2,
                maxlength: 50,
                street: true,
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
            state: {
                stateCheck: true,
                required: true,
            },
            zip: {
                required: true,
                zipcode: true,
            },
            state: {
                required: true,
            },
            role: {
                required: true,
            },
            date_of_birth: {
                required: true,
                dateRange: [new Date("1900-01-01").toDateString()],
            },

            "region_id[]": {
                atLeastOneChecked: true,
                required: true,
            },
        },
        messages: {
            role: {
                required: "Please select the role",
            },
            user_name: {
                required: "Please enter a username",
            },
            password: {
                required: "Please enter a password",
            },
            first_name: {
                required: "Please enter a firstname between 3 and 15 character",
            },
            last_name: {
                required: "Please enter a lastname between 3 and 15 character",
            },
            email: {
                required:
                    "Please enter a valid email format (e.g., user@example.com).",
            },
            confirm_email: {
                required:
                    "Please enter a valid email format (e.g., user@example.com).",
                equalTo: "Confirm email and email both must be same",
            },
            phone_number: {
                required: "Please enter a mobile number",
                phoneIndia: "Please enter valid phone number format....",
            },
            alt_mobile: {
                required: "Please enter a mobile number",
                min: "Please enter a 10 digit positive number in alternate phone number.",
                minlength: "Please enter exactly 10 digits in phone number",
                maxlength: "Please enter exactly 10 digits in phone number",
            },
            address1: {
                required: "Please enter a address1",
                street: "Please enter alphabets,dash,underscore,comma,space and numbers in address1. ",
            },
            address2: {
                required: "Please enter a address2",
            },
            city: {
                required: "Please enter a city",
                city: "Please enter alpbabets in city name.",
            },
            state: {
                required: "Please enter a state",
                state: "Please enter alpbabets in state name.",
            },
            zipcode: {
                required: "Please enter a zipcode",
                min: "Please enter positive 6 digits zipcode",
            },
            role: {
                required: "Please select at least one role",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("text-danger");
            element.closest(".errorMsg").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
});
