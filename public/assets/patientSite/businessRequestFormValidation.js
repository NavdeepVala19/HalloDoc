$(document).ready(function () {
     // ** This code is for client side validation in business form

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

    $("#patientRequestForm").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            date_of_birth: {
                required: true,
            },
            email: {
                required: true,
                email: true,
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
            street: {
                required: true,
                minlength: 2,
                maxlength: 100,
            },
            city: {
                required: true,
                minlength: 2,
                maxlength: 30,
                city: true,
            },
            state: {
                required: true,
                minlength: 2,
                maxlength: 30,
                state: true,
            },
            zipcode: {
                required: true,
                zipcode: true,
            },
            business_first_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            business_last_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            business_mobile: {
                required: true,
                phoneUS: true,
            },
            business_email: {
                required: true,
                email: true,
            },
            business_property_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
        },
        messages: {
            email: {
                required:
                    "Please enter a valid email format (e.g., user@example.com).",
            },
            first_name: {
                required: "Please enter a firstname between 2 and 30 character",
            },
            last_name: {
                required: "Please enter a lastname between 2 and 30 character",
            },
            date_of_birth: {
                required: "Please enter a date of birth",
            },
            phone_number: {
                required: "Please enter a mobile number",
                phoneUS: "Please enter valid phone number format....",
            },
            street: {
                required: "Please enter a street",
            },
            city: {
                required: "Please enter a city",
            },
            state: {
                required: "Please enter a state",
            },
            zipcode: {
                required: "Please enter a zipcode",
            },
            business_first_name: {
                required: "Please enter a firstname between 2 and 30 character",
            },
            business_last_name: {
                required: "Please enter a lastname between 2 and 30 character",
            },
            business_mobile: {
                required: "Please enter a mobile number",
                phoneUS: "Please enter valid phone number format....",
            },
            business_email: {
                required: "Please enter a valid email format (e.g., user@example.com).",
            },
            business_property_name: {
                required: "Please enter a business/property name",
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
    });

})