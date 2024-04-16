$(document).ready(function () {
    // ** This code is for client side validation in business form
    
    
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
           "business",
           function (value, element) {
               return value.match(/^[a-zA-Z]+$/);
           },
           "Please enter a valid business/property name."
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
        "case",
        function (value, element) {
            // If the field is empty, consider it valid
            if (value === "") {
                return true;
            }
            // If a value is entered, check if it's a non-negative number
            return !isNaN(value) && value >= 0;
        },
        "Please enter a valid case number."
    );

        $.validator.addMethod(
            "diseaseSymptoms",
            function (value, element) {
                const regex = /^[a-zA-Z ,_-]+?$/; // Allows letters, spaces, punctuation
                return this.optional(element) || regex.test(value.trim());
            },
            "Please enter valid symptoms."
        );

    $("#patientRequestForm").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersFirstName: true,
            },
            date_of_birth: {
                required: true,
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
            business_first_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersFirstName: true,
            },
            business_last_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersLastName: true,
            },
            business_mobile: {
                required: true,
                phoneUS: true,
            },
            business_email: {
                required: true,
                emailAddress: true,
            },
            business_property_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
                business: true,
            },
            room: {
                minlength: 0,
                nonNegativeOptional: true,
            },
            case_number: {
                case: true,
            },
            symptoms: {
                diseaseSymptoms: true,
                maxlength: 200,
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
            business_first_name: {
                required: "Please enter a firstname between 3 and 15 character",
            },
            business_last_name: {
                required: "Please enter a lastname between 3 and 15 character",
            },
            business_mobile: {
                required: "Please enter a mobile number",
                phoneUS: "Please enter valid phone number format....",
            },
            business_email: {
                required:
                    "Please enter a valid email format (e.g., user@example.com).",
            },
            business_property_name: {
                required: "Please enter a business/property name",
            },
            symptoms: {
                diseaseSymptoms: "Please enter valid symptoms.",
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

})