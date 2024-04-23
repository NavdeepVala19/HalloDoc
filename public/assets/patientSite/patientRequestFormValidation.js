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
        "phoneIndia",
        function (value, element) {
            return this.optional(element) || iti.isValidNumber();
        },
        "Please enter a valid phone number."
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
        "street",
        function (value, element) {
            return value.match(/^[a-zA-Z0-9\s,_-]+?$/);
        },
        "Please enter a only alphabets and numbers in street name. "
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
            return /^[a-zA-Z\s,.-]+$/.test(value);
        },
        "Please enter a valid city name with alphabets."
    );

    $.validator.addMethod(
        "state",
        function (value, element) {
            return /^[a-zA-Z\s,.-]+$/.test(value);
        },
        "Please enter a valid state name with alphabets."
    );

    $.validator.addMethod(
        "zipcode",
        function (value, element) {
            return value.length == 6 && /\d/.test(value);
        },
        "Please enter a 6 digit positive number in zipcode."
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
        "Please enter alphabets in symptoms."
    );

    // Date Validation (params array will hold minimum and maximum date)
    $.validator.addMethod(
        "dateRange",
        function (value, element, params) {
            if (!value) {
                // Check if the field is empty
                return true; // Allow empty field
            }
            // Parse the entered date and minimum/maximum dates
            var enteredDate = new Date(value);
            var minDate = new Date(params[0]); // First parameter in params array is minimum date
            var maxDate = new Date(); // Use current date as maximum date

            if (params[1]) {
                maxDate = new Date(params[1]); // Second parameter in params array is maximum date
            }
            // Check if entered date is within the allowed range (inclusive)
            return enteredDate >= minDate && enteredDate <= maxDate;
        },
        "Please enter a date between {0} and {1}."
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
        ignore: [],
        rules: {
            first_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersFirstName: true,
            },
            date_of_birth: {
                required: true,
                dateRange: [new Date("1900-01-01").toDateString()],
            },
            email: {
                required: true,
                minlength: 2,
                maxlength: 40,
                emailAddress: true,
            },
            last_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersLastName: true,
            },
            phone_number: {
                required: true,
                phoneIndia: true,
            },
            street: {
                required: true,
                minlength: 2,
                maxlength: 50,
                street: true,
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
                maxlength: 50,
                state: true,
            },
            zipcode: {
                required: true,
                zipcode: true,
            },
            room: {
                min: 0,
                max: 1000,
                nonNegativeOptional: true,
            },
            symptoms: {
                diseaseSymptoms: true,
                minlength: 5,
                maxlength: 200,
            },
            docs: {
                customFile: true,
            },
        },
        messages: {
            email: {
                required:
                    "Please enter a valid email format (e.g., user@example.com).",
                emailAddress:
                    "Please enter a valid email (format: alphanum@alpha.domain).",
            },
            first_name: {
                required:
                    "Please enter a firstname between 3 and 15 character.",
            },
            last_name: {
                required: "Please enter a lastname between 3 and 15 character.",
            },
            date_of_birth: {
                required: "Please enter a date of birth.",
                dateRange:
                    "Date of Birth should be between " +
                    new Date("1900-01-01").toDateString() +
                    " and " +
                    new Date().toDateString(),
            },
            phone_number: {
                required: "Please enter a mobile number",
                phoneIndia: "Please enter valid phone number format....",
            },
            street: {
                required: "Please enter a street",
            },
            city: {
                required: "Please enter a city",
                city: "Please enter alpbabets in city name.",
            },
            state: {
                required: "Please enter a state",
                state: "Please enter alpbabets in state name.",
            },
            zipcode: {
                required: "Please enter a zipcode",
                min: "Please enter a 6 digit positive number in zipcode.",
            },
            room: {
                nonNegativeOptional: "Please enter a valid room number.",
            },
            symptoms: {
                diseaseSymptoms: "Please enter valid symptoms.",
                maxlength: "Symptoms details cannot exceed 200 characters.", // Optional: Message for exceeding limit
            },
            docs: {
                customFile:
                    "Please select a valid file (JPG, PNG, PDF, DOC) with a size less than 2MB. ",
            },
        },
        errorPlacement: function (error, element) {
            let errorDiv = $('<div class="text-danger"></div>');
            errorDiv.append(error);
            element.closest("#form-floating").append(errorDiv);
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
