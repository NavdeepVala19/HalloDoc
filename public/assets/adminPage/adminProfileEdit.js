

$(document).ready(function () {
    $.validator.addMethod("phoneUS", function (phone_number, element) {
        return this.optional(element) || phone_number.match(/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/);
    }, "Please enter a valid phone number.");

    $.validator.addMethod("city", function (value, element) {
        return value.match(/^[a-zA-Z ,_-]+?$/);
    }, "Please enter a valid city name.");

    $.validator.addMethod("zipcode", function (value, element) {
        return value.length == 6 && /\d/.test(value);
    }, "Please enter a valid zipcode.");

    $('#adminEditProfileForm1').validate({
        rules: {
            password: {
                required: true,
                minlength: 3,
                maxlength: 50
            },
        },
        message: {
            password: {
                required: "Please enter a valid password",
            },
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
    })

    $('#adminEditProfileForm2').validate({
        rules: {
            first_name: {
                required: true,
                minlength: 3,
                maxlength: 50
            },
            last_name: {
                required: true,
                minlength: 3,
                maxlength: 50
            },
            email: {
                required: true,
                minlength: 3,
                maxlength: 50
            },
            confirm_email : {
                required: true,
                minlength: 3,
                maxlength: 50
            },
            phone_number: {
                required: true,
                phoneUS:true
            },
        },
        message: {
            first_name: {
                required: "Please enter a firstname",
            },
            last_name: {
                required: "Please enter a lastname",
            },
            email: {
                required: "Please enter a email",
            },
            confirm_email: {
                required: "Please enter a confirm email",
            },
            phone_number: {
                required: "Please enter a valid phone_number",
            },
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
    })


    $('#adminEditProfileForm3').validate({
        rules: {
            address1: {
                required: true,
                minlength: 3,
                maxlength: 50
            },
            address2: {
                required: true,
                minlength: 3,
                maxlength: 50
            },
            city: {
                required: true,
                city:true
            },
            zip : {
                required: true,
                zipcode:true
            },
            alt_mobile: {
                required: true,
                phoneUS: true
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
            alt_mobile: {
                required: "Please enter a valid alt_phone_number",
            },
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
    })
})