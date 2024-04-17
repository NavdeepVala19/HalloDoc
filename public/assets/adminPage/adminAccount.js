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
        "state",
        function (value, element) {
            return value.match(/^[a-zA-Z ,_-]+?$/);
        },
        "Please enter a valid state name."
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
        "Please enter a valid address2."
    );

    $.validator.addMethod(
        "address1",
        function (value, element) {
            return value.match(/^[a-zA-Z0-9-, ]+$/);
        },
        "Please enter a valid address1."
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
                emailAddress: true,
            },
            confirm_email: {
                required: true,
                emailAddress: true,
            },
            phone_number: {
                required: true,
                phoneUS: true,
            },
            alt_mobile: {
                required: true,
                phoneUS: true,
            },
            address1: {
                required: true,
                minlength: 2,
                maxlength: 30,
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
            },
            phone_number: {
                required: "Please enter a mobile number",
                phoneUS: "Please enter valid phone number format....",
            },
            alt_mobile: {
                required: "Please enter a mobile number",
                phoneUS: "Please enter valid phone number format....",
            },
            address1: {
                required: "Please enter a address1",
            },
            city: {
                required: "Please enter a city",
            },
            address2: {
                required: "Please enter a address2",
            },
            state: {
                required: "Please select state",
            },
            zipcode: {
                required: "Please enter a zipcode",
            },
            role: {
                required: "Please select at least one role",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("text-danger");
            element.closest("#form-floating").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
});
