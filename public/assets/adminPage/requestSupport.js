
// ** This code is for client side validation in send request supoort message

$(document).ready(function () {
       $.validator.addMethod(
           "requestSupportMessage",
           function (value, element) {
               const regex = /^[a-zA-Z ,_-]+?$/; // Allows letters, spaces, punctuation
               return this.optional(element) || regex.test(value.trim());
           },
           "Please enter alphabets,comma,underscore In Request Support Message."
       );
    
    $("#requestDTYSupportForm").validate({
        rules: {
            contact_msg: {
                required: true,
                minlength: 5,
                maxlength: 200,
                requestSupportMessage: true,
            },
        },
        messages: {
            contact_msg: {
                required: "Please enter a message",
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


    $(".requestDTYClose").click(function () {
        $(".pop-up .pop-up-request-support").empty()
        $("#requestDTYSupportForm").trigger("reset");
        $("#requestDTYSupportForm").validate().resetForm();
        $(".pop-up form .form-control").removeClass(
            "is-valid"
        );
        $(".pop-up form .form-control").removeClass(
            "is-invalid"
        );
    });

});
