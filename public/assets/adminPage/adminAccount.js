$(document).ready(function () {

    $.validator.addMethod("phoneUS", function (phone_number, element) {
        return this.optional(element) || phone_number.match(/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/);
    }, "Please enter a valid phone number.");

    $.validator.addMethod("city", function (value, element) {
        return value.match(/^[a-zA-Z ,_-]+?$/);
    }, "Please enter a valid city name.");

    $.validator.addMethod("state", function (value, element) {
        return value.match(/^[a-zA-Z ,_-]+?$/);
    }, "Please enter a valid state name.");

    $.validator.addMethod("zipcode", function (value, element) {
        return value.length == 6 && /\d/.test(value);
    }, "Please enter a valid zipcode.");

    $('#createAdminAccountForm').validate({
        rules: {
            user_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            password: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            first_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            last_name: {
                required: true,
                minlength: 2,
                maxlength: 30
            },
            email: {
                required: true,
                email: true,
            },
            confirm_email: {
                required: true,
                email: true,
            },
            phone_number: {
                required: true,
                phoneUS: true
            },
            alt_mobile: {
                required: true,
                phoneUS: true
            },
            address1: {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            address2: {
                required: true,
                minlength: 2,
                maxlength: 50,
            },
            city: {
                required: true,
                minlength: 2,
                maxlength: 30,
                city: true
            },
            zip: {
                required: true,
                zipcode: true
            },
            role: {
                required: true,
            }
        },
        messages: {
            first_name: {
                required: "Please enter a firstname between 2 and 30 character",
            },
            last_name: {
                required: "Please enter a lastname between 2 and 30 character",
            },
            email: {
                required: "Please enter a valid email format (e.g., user@example.com).",
            },
            confirm_email: {
                required: "Please enter a valid email format (e.g., user@example.com).",
            },
            phone_number: {
                required: "Please enter a mobile number",
                phoneUS: "Please enter valid phone number format...."
            },
            alt_mobile: {
                required: "Please enter a mobile number",
                phoneUS: "Please enter valid phone number format...."
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
            zipcode: {
                required: "Please enter a zipcode",
            },
            role: {
                required: "Please select at least one role",
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('errorMsg');
            element.closest('.form-floating').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    });
    console.log("here");
});