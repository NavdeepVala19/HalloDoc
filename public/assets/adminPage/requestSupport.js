$(document).ready(function () {
    $('#requestDTYSupportForm').validate({
        rules: {
            contact_msg: {
                required: true,
            },       
        },
        messages: {
            contact_msg: {
                required: "Please enter a message",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('text-danger');
            element.closest('.form-floating').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    });
    
    $("#requestDTYSupportForm").click(function () {
        if ($("#requestDTYSupportForm").valid()) {
            $("#requestDTYSupportForm").submit();
        }
    });
  
});