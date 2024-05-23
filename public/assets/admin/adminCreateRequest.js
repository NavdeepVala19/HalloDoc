// ** This code is for client side validation in admin create request on behalf of patient

$(document).ready(function () {
    $.validator.addMethod(
        "lettersFirstName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only Alphabets for first name of patient."
    );

    $.validator.addMethod(
        "lettersLastName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only Alphabets for last name of patient."
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
            return (
                this.optional(element) ||
                (value.length == 6 && /\d/.test(value))
            );
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
            const regex = /^[a-zA-Z0-9 \-_.,()]+$/; //  Allows letters, spaces,numbers,parentheses,comma,frwd slash,fullstop
            return this.optional(element) || regex.test(value.trim());
        },
        "Please enter valid notes. notes should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers."
    );

    $.validator.addMethod(
        "phoneIndia",
        function (value, element) {
            return this.optional(element) || iti.isValidNumber();
        },
        "Please enter a valid phone number."
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
        "street",
        function (value, element) {
            return value.match(/^[a-zA-Z0-9\s,_-]+?$/);
        },
        "only alphabets,dash,underscore,space,comma and numbers are allow in street name. "
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

    $("#adminCreateRequestForm").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersFirstName: true,
            },
            email: {
                required: true,
                minlength: 2,
                maxlength: 40,
                emailAddress: true,
            },
            date_of_birth: {
                required: false,
                dateRange: [new Date("1900-01-01").toDateString()],
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
                maxlength: 30,
                state: true,
            },
            zipcode: {
                zipcode: true,
            },
            room: {
                min: 1,
                max: 1000,
                nonNegativeOptional: true,
            },
            adminNote: {
                diseaseSymptoms: true,
                maxlength: 200,
            },
        },
        messages: {
            email: {
                required:
                    "Please enter email of patient",
                emailAddress:
                    "Please enter a valid email (format: alphanum@alpha.domain).",
            },
            first_name: {
                required: "Please enter firstname of patient",
            },
            last_name: {
                required: "Please enter lastname of patient",
            },
            phone_number: {
                required: "Please enter mobile number of patient",
                phoneIndia: "Please enter valid phone number format....",
            },
            street: {
                required: "Please enter a street",
                street: "only alphabets,dash,underscore,space,comma and numbers are allow in street name. ",
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
                min: "Please enter 6 digits positive zipcode",
            },
            room: {
                min: "Please enter room number greater than 0",
                nonNegativeOptional: "Please enter a valid room number.",
            },
            adminNote: {
                diseaseSymptoms:
                    "Please enter valid notes. notes should only contain alphabets,comma,dash,underscore,parentheses,fullstop and numbers.",
                maxlength: "notes details cannot exceed 200 characters.", // Optional: Message for exceeding limit
            },
            date_of_birth: {
                dateRange:
                    "Date of Birth should be between " +
                    new Date("1900-01-01").toDateString() +
                    " and " +
                    new Date().toDateString(),
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
});
