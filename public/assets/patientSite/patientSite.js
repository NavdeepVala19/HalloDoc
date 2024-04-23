$(document).ready(function () {
    // Create Request Upload file, show file name in label
    $("#file-upload-request").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        if (fileName) {
            $(".file-label").text(fileName);
        } else {
            $(".file-label").text("Select File");
        }
    });

    // **** This code is for create a new request in patient dashboard page  *****
    $(".create-btn").click(function () {
        $(".new-request").show();
        $(".overlay").show();
    });

    $(".create-new-request-btn").click(function () {
        $(".new-request-create").show();
        $(".overlay").show();
    });

    $(".hide-popup-btn").click(function () {
        $(".new-request-create").hide();
        $(".overlay").hide();
    });

    //  *********

    // *** This code is for create new request pop-up  ****

    $(".btn-someone").click(function () {
        $(this).toggleClass("btn-active");
        $(".btn-me").removeClass("btn-active");
    });

    $(".btn-me").click(function () {
        $(this).toggleClass("btn-active");
        $(".btn-someone").removeClass("btn-active");
    });

    $(".continue-btn").click(function () {
        if ($(".btn-me").hasClass("btn-active")) {
            $(window).attr("location", "/createPatientRequests");
        } else if ($(".btn-someone").hasClass("btn-active")) {
            $(window).attr("location", "/createSomeoneRequests");
        } else {
            alert('please select "Me" or "SomeOne Else"');
        }
    });

    //  ****

    // **** This code is for showing input password and hide it when click on eye icon ****

    $(".person-eye").click(function () {
        const passwordField = $("#exampleInputPassword1");
        if (passwordField.prop("type") === "password") {
            passwordField.prop("type", "text");
            $(this).removeClass("bi-eye-fill").addClass("bi-eye-slash-fill");
        } else {
            passwordField.prop("type", "password");
            $(this).removeClass("bi-eye-slash-fill").addClass("bi-eye-fill");
        }
    });

    $(".person-eye-two").click(function () {
        const confirmpasswordField = $("#exampleInputPassword2");
        if (confirmpasswordField.prop("type") === "password") {
            confirmpasswordField.prop("type", "text");
            $(this).removeClass("bi-eye-fill").addClass("bi-eye-slash-fill");
        } else {
            confirmpasswordField.prop("type", "password");
            $(this).removeClass("bi-eye-slash-fill").addClass("bi-eye-fill");
        }
    });

    // ** This code is for client side validation of patientProfileEdit

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
            return /^[a-zA-Z ]+?$/.test(value);
        },
        "Please enter a valid city name with alphabets."
    );

    $.validator.addMethod(
        "street",
        function (value, element) {
            return value.match(/^[a-zA-Z0-9\s,_-]+?$/);
        },
        "Please enter a only alphabets and numbers in street name. "
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

    $("#patientProfileEditForm").validate({
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
                required: true,
                dateRange: [
                    new Date("1900-01-01").toDateString(),
                    new Date().toDateString(),
                ],
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
                required: true,
                zipcode: true,
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
            phone_number: {
                required: "Please enter a mobile number",
                phoneIndia: "Please enter valid phone number format....",
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

// **** This code is for patient view documents checkboxes ****

$(".master-checkbox").on("click", function () {
    if ($(this).is(":checked", true)) {
        $(".child-checkbox").prop("checked", true);
    } else {
        $(".child-checkbox").prop("checked", false);
    }
});

// ***

// **** This code is for show file name********
//
$(".file-input").change(function (e) {
    const filename = e.target.files[0].name;
    $("#demo").text(filename);
});

// ****

// **** This code is for patientDashboard accordion menu ******

var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}

// View Uploads File Upload Functionality
$("#file-upload").on("change", function () {
    var fileName = $(this).val().split("\\").pop();
    $(".upload-label").text(fileName);
});
