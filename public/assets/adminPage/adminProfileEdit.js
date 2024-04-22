$(document).ready(function () {

    //* enable and disable the adminprofile page fields 
        $("#admin-info-cancel-btn").on("click", function () {
            $(".admin_first_name").attr("disabled", true);
            $(".admin_last_name").attr("disabled", true);
            $(".admin_email").attr("disabled", true);
            $(".admin_confirm_email").attr("disabled", true);
            $(".phone").attr("disabled", true);

            $("#adminEditBtn1").show();
            $(".admin-info-btns").hide();
        });

        $("#admin-mail-cancel-btn").on("click", function () {
            $(".admin_add1").attr("disabled",true);
            $(".admin_add2").attr("disabled", true);
            $(".city").attr("disabled", true);
            $(".admin_state").attr("disabled", true);
            $(".admin_zipcode").attr("disabled", true);
            $(".admin_alt_phone").attr("disabled", true);

            $("#adminEditBtn2").show();
            $(".admin-mail-info-btns").hide();
        });

        $("#adminAccEditBtn").on("click", function () {
            $(".admin_user_name").removeAttr("disabled");
            $("#status-select").removeAttr("disabled");
            $("#listing_role_admin_Account").removeAttr("disabled");

            $("#adminAccEditBtn").hide();
            $(".admin-acc-btns").show();
        });

        $("#adminEditBtn1").on("click", function () {
            $(".admin_first_name").removeAttr("disabled");
            $(".admin_last_name").removeAttr("disabled");
            $(".admin_email").removeAttr("disabled");
            $(".admin_confirm_email").removeAttr("disabled");
            $(".phone").removeAttr("disabled");

            $("#adminEditBtn1").hide();
            $(".admin-info-btns").show();
        });

        $("#adminEditBtn2").on("click", function () {
            $(".admin_add1").removeAttr("disabled");
            $(".admin_add2").removeAttr("disabled");
            $(".city").removeAttr("disabled");
            $(".admin_state").removeAttr("disabled");
            $(".admin_zipcode").removeAttr("disabled");
            $(".admin_alt_phone").removeAttr("disabled");
            $("#listing_state_admin_account").removeAttr("disabled");

            $("#adminEditBtn2").hide();
            $(".admin-mail-info-btns").show();
        });


    
    // * client side validation of admin profile edit
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
        "Please enter a valid zipcode."
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
        "lettersFirstName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only letters for your first name."
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
        "Please enter a only alphabets and numbers in street name. "
    );

    
    $.validator.addMethod(
        "lettersLastName",
        function (value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/.test(value);
        },
        "Please enter only letters for your Last name."
    );

    $("#adminEditProfileForm1").validate({
        rules: {
            password: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
        },
        messages: {
            password: {
                required: "Please enter a valid password",
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

       $.validator.addMethod(
           "phoneIndia",
           function (value, element) {
               return this.optional(element) || iti.isValidNumber();
           },
           "Please enter a valid phone number."
       );
    
    $("#adminEditProfileForm2").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersFirstName: true,
            },
            last_name: {
                required: true,
                minlength: 3,
                maxlength: 15,
                lettersLastName: true,
            },
            email: {
                required: true,
                minlength: 2,
                maxlength: 40,
                emailAddress: true,
            },
            confirm_email: {
                required: true,
                minlength: 3,
                maxlength: 20,
                emailAddress: true,
            },
            phone_number: {
                required: true,
                phoneIndia: true,
            },
        },
        messages: {
            first_name: {
                required: "Please enter a firstname",
            },
            last_name: {
                required: "Please enter a lastname",
            },
            email: {
                required: "Please enter a email",
            },
            confirm_email: {
                required: "Please enter a confirm email",
            },
            phone_number: {
                required: "Please enter a valid phone_number",
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

    $("#adminEditProfileForm3").validate({
        rules: {
            address1: {
                required: true,
                minlength: 2,
                maxlength: 50,
                street: true,
            },
            address2: {
                required: true,
                minlength: 3,
                maxlength: 30,
                state: true,
            },
            city: {
                required: true,
                city: true,
            },
            zip: {
                required: true,
                zipcode: true,
            },
            alt_mobile: {
                required: true,
                phoneUS: true,
            },
        },
        messages: {
            address1: {
                required: "Please enter a valid address1",
            },
            address2: {
                required: "Please enter a valid address2",
            },
            city: {
                required: "Please enter a city",
                city: "Please enter alpbabets in city name.",
            },
            state: {
                required: "Please enter a state",
                state: "Please enter alpbabets in state name.",
            },
            zip: {
                required: "Please enter a valid zipcode",
                min: "Please enter positive number with 6 digits",
            },
            alt_mobile: {
                required: "Please enter a valid alt_phone_number",
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
});
