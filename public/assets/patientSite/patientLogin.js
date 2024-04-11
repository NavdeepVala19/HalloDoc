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

    $("#patientLogin").validate({
        rules: {
            email: {
                required: true,
                emailAddress: true,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 20,
                password: true,
            },
        },
        messages: {
            email: {
                required:
                    "Please enter a valid email format (e.g., user@example.com).",
                emailAddress:
                    "Please enter a valid email (format: alphanum@alpha.domain).",
            },
            password: {
                required: "Please enter a password.",
                minlength: "Password must be at least 8 characters long.",
                maxlength: "Password cannot exceed 30 characters.",
                password: "Please enter a valid password",
            },
        },
        errorPlacement: function (error, element) {
            error.addClass("text-danger");
            element.closest(".patientLogin").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });
    console.log("here");
});
