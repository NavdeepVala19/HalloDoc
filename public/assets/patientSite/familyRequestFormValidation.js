// ** This code is for client side validation in all family/friend form

$(document).ready(function () {
    $.validator.addMethod(
        "lettersFirstName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only letters for first name."
    );

    $.validator.addMethod(
        "lettersLastName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only letters for Last name."
    );

    $.validator.addMethod(
        "relation",
        function (value, element) {
            return (
                this.optional(element) ||
                /^[a-zA-Z]+(?:-[a-zA-Z]+)*$/.test(value)
            );
        },
        "Please enter only letters and dash for your relation."
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
        "phoneIndia",
        function (value, element) {
            return this.optional(element) || iti.isValidNumber();
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
            return /^[a-zA-Z ]+?$/.test(value);
        },
        "Please enter a valid city name with alphabets."
    );

    $.validator.addMethod(
        "state",
        function (value, element) {
            return /^[a-zA-Z ]+?$/.test(value);
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
        "diseaseSymptoms",
        function (value, element) {
            const regex = /^[a-zA-Z0-9 \-_.,()/]+$/; // Allows letters, spaces,numbers,parentheses,comma,frwd slash,fullstop
            return this.optional(element) || regex.test(value.trim());
        },
        "Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers."
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
        "street",
        function (value, element) {
            return value.match(/^[a-zA-Z0-9\s,_-]+?$/);
        },
        "Please enter alphabets,dash,underscore,space,comma and numbers in street name. "
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
                  var allowedExtensions = [
                      "jpg",
                      "jpeg",
                      "png",
                      "pdf",
                      "doc",
                      "docx",
                  ];

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
                minlength: 10,
                maxlength: 10,
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
                lettersFirstName: true,
            },
            room: {
                min: 0,
                max: 1000,
                nonNegativeOptional: true,
            },
            family_last_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersLastName: true,
            },
            family_phone_number: {
                required: true,
                phoneIndia: true,
            },
            family_email: {
                required: true,
                minlength: 2,
                maxlength: 40,
                emailAddress: true,
            },
            family_relation: {
                required: true,
                minlength: 3,
                maxlength: 15,
                relation: true,
            },
            symptoms: {
                required: false,
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
                required: "Please enter a firstname between 3 and 15 character",
            },
            last_name: {
                required: "Please enter a lastname between 3 and 15 character",
            },
            date_of_birth: {
                required: "Please enter a date of birth",
                dateRange:
                    "Date of Birth should be between " +
                    new Date("1900-01-01").toDateString() +
                    " and " +
                    new Date().toDateString(),
            },
            phone_number: {
                required: "Please enter a mobile number",
                min: "Please enter a 10 digit positive number in phone number.",
                minlength: "Please enter exactly 10 digits in phone number",
                maxlength: "Please enter exactly 10 digits in phone number",
            },
            street: {
                required: "Please enter a street",
                street: "Please enter alphabets,dash,underscore,space,comma and numbers in street name. ",
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
                min: "Please enter positive number with 6 digits",
                zipcode: "Please enter a 6 digit positive number in zipcode.",
            },
            family_first_name: {
                required: "Please enter a firstname between 3 and 15 character",
            },
            family_last_name: {
                required: "Please enter a lastname between 3 and 15 character",
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
                    "Please select a valid file (JPG, PNG, PDF, DOC) with a size less than 2MB. ",
            },
            room: {
                nonNegativeOptional: "Please enter a valid room number.",
            },
            symptoms: {
                diseaseSymptoms:
                    "Please enter valid symptoms. Symptoms should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.",
                maxlength: "Symptoms details cannot exceed 200 characters.", // Optional: Message for exceeding limit
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
});
