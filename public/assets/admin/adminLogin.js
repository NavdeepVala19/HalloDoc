// ** This code is for client side validation in admin login,reset and update password

$(document).ready(function () {
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
        "password",
        function (email, element) {
            return this.optional(element) || email.match(/^\S(.*\S)?$/);
        },
        "Please enter a valid password"
    );

    $("#adminLogin").validate({
        rules: {
            email: {
                required: true,
                minlength: 2,
                maxlength: 40,
                emailAddress: true,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 20,
                password: true,
            },
            new_password: {
                required: true,
                minlength: 8,
                maxlength: 20,
                password: true,
            },
            confirm_password: {
                required: true,
                equalTo: "#exampleInputPassword1",
            },
        },
        messages: {
            email: {
                required:
                    "Please enter a email",
                emailAddress:
                    "Please enter a valid email (format: alphanum@alpha.domain).",
            },
            password: {
                required: "Please enter a password.",
                minlength: "Password must be at least 8 characters long.",
                maxlength: "Password cannot exceed 30 characters.",
                password: "Please enter a valid password",
            },
            new_password: {
                required: "Please enter a password.",
                minlength: "Password must be at least 8 characters long.",
                maxlength: "Password cannot exceed 30 characters.",
                password: "Please enter a valid password",
            },
            confirm_password: {
                required: "Please confirm your password.",
                equalTo: "Passwords do not match.",
            },
        },

        errorPlacement: function (error, element) {
            let errorDiv = $('<div class="text-danger"></div>');
            errorDiv.append(error);
            element.closest("#adminLog").append(errorDiv);
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
});
