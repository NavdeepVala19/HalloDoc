// ** This code is for client side validation in conceirge form

$(document).ready(function () {
    $.validator.addMethod(
        "lettersFirstName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only Alphabets of your first name."
    );

    $.validator.addMethod(
        "lettersLastName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only Alphabets of your Last name."
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
        "hotel",
        function (value, element) {
            return value.match(/^[a-zA-Z0-9 &\-_.,]+$/);
        },
        "Please enter alphabets,number,dash,underscore,ampersand,fullstop,comma in hotel/property name."
    );

    $.validator.addMethod(
        "zipcode",
        function (value, element) {
            return value.length == 6 && /\d/.test(value);
        },
        "Please enter a positive 6 digit zipcode."
    );

    $.validator.addMethod(
        "street",
        function (value, element) {
            return value.match(/^[a-zA-Z0-9\s,_-]+?$/);
        },
        "Please enter alphabets,dash,underscore,space,comma and numbers in street name. "
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
        "phoneIndia",
        function (value, element) {
            return this.optional(element) || iti.isValidNumber();
        },
        "Please enter a valid phone number."
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
            symptoms: {
                required: false,
                diseaseSymptoms: true,
                minlength: 5,
                maxlength: 200,
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
            room: {
                min: 1,
                max: 1000,
                nonNegativeOptional: true,
            },
            concierge_first_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersFirstName: true,
            },
            concierge_last_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersLastName: true,
            },
            concierge_mobile: {
                required: true,
                phoneIndia: "Please enter valid phone number format....",
            },
            concierge_email: {
                required: true,
                minlength: 2,
                maxlength: 40,
                emailAddress: true,
            },
            concierge_hotel_name: {
                required: true,
                minlength: 2,
                maxlength: 50,
                hotel: true,
            },
            concierge_street: {
                required: true,
                minlength: 2,
                maxlength: 50,
                street: true,
            },
            concierge_city: {
                required: true,
                minlength: 2,
                maxlength: 30,
                city: true,
            },
            concierge_state: {
                required: true,
                minlength: 2,
                maxlength: 30,
                state: true,
            },
            concierge_zip_code: {
                required: true,
                zipcode: true,
            },
        },
        messages: {
            email: {
                required: "Please enter email of patient",
            },
            first_name: {
                required: "Please enter firstname of patient",
                lettersFirstName:
                    "Please enter only Alphabets of first name of patient.",
            },
            last_name: {
                required: "Please enter lastname of patient",
                lettersLastName:
                    "Please enter only Alphabets of Last name of Patient.",
            },
            date_of_birth: {
                required: "Please enter date of birth of patient",
                dateRange:
                    "Date of Birth should be between " +
                    new Date("1900-01-01").toDateString() +
                    " and " +
                    new Date().toDateString(),
            },
            phone_number: {
                required: "Please enter  mobile number of patient",
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
                zipcode: "Please enter a positive 6 digit zipcode",
            },
            symptoms: {
                maxlength: "Symptoms details cannot exceed 200 characters.", // Optional: Message for exceeding limit
            },
            room: {
                min: "Please enter room number greater than 0",
                nonNegativeOptional: "Please enter a valid room number.",
            },
            concierge_first_name: {
                required: "Please enter your firstname",
                lettersFirstName:
                    "Please enter only Alphabets of your first name.",
            },
            concierge_mobile: {
                required: "Please enter your mobile number",
                phoneIndia: "Please enter valid phone number format....",
            },
            concierge_email: {
                required: "Please enter your email",
            },
            concierge_last_name: {
                required: "Please enter your lastname ",
                lettersLastName:
                    "Please enter only Alphabets of your Last name.",
            },
            concierge_hotel_name: {
                required: "Please enter a hotel name",
            },
            concierge_street: {
                required: "Please enter a street",
            },
            concierge_city: {
                required: "Please enter a city",
            },
            concierge_state: {
                required: "Please enter a state",
            },
            concierge_zip_code: {
                required: "Please enter a zipcode",
                min: "Please enter positive number with 6 digits",
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
