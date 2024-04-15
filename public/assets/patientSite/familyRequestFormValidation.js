$(document).ready(function () {

     // ** This code is for client side validation in all family/friend form

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
            family_first_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },

            family_last_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            family_phone_number: {
                required: true,
                phoneUS: "Please enter valid phone number format....",
            },
            family_email: {
                required: true,
                email: true,
            },
            family_relation: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            docs: {
                customFile: true,
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
            family_first_name: {
                required: "Please enter a firstname between 2 and 30 character",
            },
            family_last_name: {
                required: "Please enter a lastname between 2 and 30 character",
            },
            family_phone_number: {
                required: "Please enter a mobile number",
                phoneUS: "Please enter valid phone number format....",
            },
            family_relation: {
                required: "Please enter a relation ",
            },
            family_email: {
                required:
                    "Please enter a valid email format (e.g., user@example.com).",
            },
            docs: {
                customFile:
                    "Please select file type of '.jpg' , '.png' , '.pdf', '.doc' ",
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