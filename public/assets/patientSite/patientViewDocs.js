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

    $("#patientUploadDocs").validate({
        rules: {           
            document: {
                required:true,
                customFile: true,
            },
        },
        messages: {         
            document: {
                required:"Please select document",
                customFile: "Please select file type of '.jpg' , '.png' , '.pdf', '.doc' ",
            },
        },
        errorElement: span,
        errorPlacement: function (error, element) {
            error.addClass("text-danger");
            element.closest(".custom-file-input").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
    });

});
