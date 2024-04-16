$(document).ready(function () {
    
    $.validator.addMethod(
        "lettersFirstName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only letters for your first name."
    );

    $.validator.addMethod(
        "lettersLastName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only letters for your Last name."
    );

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
        "nonNegativeOptional",
        function (value, element) {
            // If the field is empty, consider it valid
            if (value === "") {
                return true;
            }
            // If a value is entered, check if it's a non-negative number
            return !isNaN(value) && value >= 0;
        },
        "Please enter a valid room number."
    );

      $.validator.addMethod(
          "diseaseSymptoms",
          function (value, element) {
              const regex = /^[a-zA-Z ,_-]+?$/; // Allows letters, spaces, punctuation
              return this.optional(element) || regex.test(value.trim());
          },
          "Please enter valid symptoms."
      );

     $.validator.addMethod(
         "relation",
         function (value, element) {
             return this.optional(element) || /^[a-zA-Z]+$/.test(value);
         },
         "Please enter only letters."
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

    $("#patientSomeOneRequest").validate({
        ignore: [],
        rules: {
            first_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersFirstName: true,
            },
            email: {
                required: true,
                emailAddress: true,
            },
            last_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersLastName: true,
            },
            date_of_birth: {
                required: true,
            },
            phone_number: {
                required: true,
                phoneUS: true,
            },
            street: {
                required: true,
                minlength: 2,
                maxlength: 30,
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
            room: {
                minlength: 0,
                nonNegativeOptional: true,
            },
            symptoms: {
                diseaseSymptoms: true,
                maxlength: 200,
            },
            docs: {
                customFile: true,
            },
            relation: {
                relation: true,
            },
        },
        messages: {
            email: {
                required:
                    "Please enter a valid email format (e.g., user@example.com).",
            },
            first_name: {
                required: "Please enter a firstname between 3 and 15 character",
            },
            last_name: {
                required: "Please enter a lastname between 3 and 15 character",
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
            room: {
                nonNegativeOptional: "Please enter a valid room number.",
            },
            patient_note: {
                diseaseSymptoms: "Please enter valid symptoms.",
                maxlength: "Symptoms details cannot exceed 255 characters.", // Optional: Message for exceeding limit
            },
            docs: {
                customFile:
                    "Please select file type of '.jpg' , '.png' , '.pdf', '.doc' ",
            },
            relation: {
                relation: "Please enter only letters",
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
        submitHandler: function (form) {
            $(".loader").fadeIn("slow"); // Show spinner on valid submission
            form.submit(); // Submit the form
        },
    });
})