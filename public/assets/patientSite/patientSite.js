$(document).ready(function () {
    // **** This code is for create a new request in patient dashboard page  *****
    $(".create-btn").click(function () {
        $(".new-request").show();
        $(".overlay").show();
    });

    $(".create-new-request-btn").click(function () {
        $(".new-request-create").show();
        $(".overlay").show();
    });

    // $(".hide-popup-btn").click(function () {
    //     $(".new-request-create").hide();
    //     $(".overlay").hide();
    // });

    //  ********************************************************************************************

    // ************************** This code is for create new request pop-up  ***********************

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

    //  ********************************************************************************************

    // **** This code is for hiding pop-up button in family/concierge/business page ****

    $(".submit-valid-details-ok-btn").click(function () {
        $(".submit-valid-details").show();
        $(".overlay").show();
    });

    //  ********************************************************************************************
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
});
// ***************************************************************************************

// **** This code is for patient view documents checkboxes ****

$(".master-checkbox").on("click", function () {
    if ($(this).is(":checked", true)) {
        $(".child-checkbox").prop("checked", true);
    } else {
        $(".child-checkbox").prop("checked", false);
    }
});


var isChecked = $('.master-checkbox').is(":checked");
console.log(isChecked);
if (isChecked == false) {
    $('#docs_download').attr('disabled')
} else {
    $('#docs_download').removeAttr('disabled')
}


// *********************************************************

// **** This code is for show file name********
//
$(".file-input").change(function (e) {
    const filename = e.target.files[0].name;
    $("#demo").text(filename);
});

// ********************************************
// **** This code is for file uploading in view document and requests pages ****

function openFileSelection() {
    document.getElementById("fileInput").click();
}
//   ****************************************************************************

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
//   ****************************************************************************
// ***************** Fetching regions from regions table ******************
$.ajax({
    url: "/admin-new",
    type: "GET",
    success: function (data) {

        data.forEach(function (region) {
            $(".listing-region").append(
                '<option value="' + region.id + '">' + region.region_name + "</option>"
            );
        });
    },
    error: function (error) {
        console.error(error);
    },

});

$(document).ready(function () {
    $("#back-btn").click(function () {
        localStorage.setItem("popupShown", "true");
    });

    $("#cancel-btn").click(function () {
        localStorage.setItem("popupShown", "true");
    });

    // Check if the popup was already shown
    if (localStorage.getItem("popupShown") == "false") {
        // Show the popup if not shown before
        $("#validDetailsPopup").hide();
        $(".overlay").hide();
    } else {
        $("#validDetailsPopup").show();
        $(".overlay").show();
    }

    // Attach an event listener to the "OK" button in the popup
    $("#closePopupBtn").on("click", function () {
        // When clicked, hide the popup
        $("#validDetailsPopup").hide();
        $(".overlay").hide();

        // And set a flag in localStorage indicating the popup was shown
        localStorage.setItem("popupShown", "false");
    });
});

// ** This code is for client side validation

$(document).ready(function () {

    $.validator.addMethod("phoneUS", function (phone_number, element) {
        return this.optional(element) || phone_number.match(/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/);
    }, "Please enter a valid phone number.");


    $.validator.addMethod("city", function (value, element) {
        return value.match(/^[a-zA-Z ,_-]+?$/);
    }, "Please enter a valid city name.");


    $.validator.addMethod("state", function (value, element) {
        return value.match(/^[a-zA-Z ,_-]+?$/);
    }, "Please enter a valid state name.");


    $.validator.addMethod("zipcode", function (value, element) {
        return value.length == 6 && /\d/.test(value);
    }, "Please enter a valid zipcode.");


    $('#patientProfileEditForm').validate({
        rules: {
            first_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            email: {
                required: true,
                email: true,
            },
            last_name: {
                required: true,
                minlength: 2,
                maxlength: 30
            },
            phone_number: {
                required: true,
                phoneUS: true
            },
            street: {
                required: true,
                minlength: 2,
                maxlength: 100
            },
            city: {
                required: true,
                minlength: 2,
                maxlength: 40,
                city: true
            },
            state: {
                required: true,
                minlength: 2,
                maxlength: 30,
                state: true
            },
            zipcode: {
                required: true,
                zipcode: true
            },
        },
        messages: {
            email: {
                required: "Please enter a valid email format (e.g., user@example.com).",
            },
            first_name: {
                required: "Please enter a firstname between 2 and 30 character",
            },
            last_name: {
                required: "Please enter a lastname between 2 and 30 character",
            },
            phone_number: {
                required: "Please enter a mobile number",
                phoneUS: "Please enter valid phone number format...."
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
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('errorMsg');
            element.closest('.form-floating').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    });
});




// *** Client side validation of patient Request


$(document).ready(function () {

    $.validator.addMethod("phoneUS", function (phone_number, element) {
        return this.optional(element) || phone_number.match(/^(\+\d{1,3}[ \.-]?)?(\(?\d{2,5}\)?[ \.-]?){1,2}\d{4,10}$/);
    }, "Please enter a valid phone number.");


    $.validator.addMethod("city", function (value, element) {
        return value.match(/^[a-zA-Z ,_-]+?$/);
    }, "Please enter a valid city name.");


    $.validator.addMethod("state", function (value, element) {
        return value.match(/^[a-zA-Z ,_-]+?$/);
    }, "Please enter a valid state name.");


    $.validator.addMethod("zipcode", function (value, element) {
        return value.length == 6 && /\d/.test(value);
    }, "Please enter a valid zipcode.");


    $('#patientRequestForm').validate({
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
                maxlength: 30
            },
            phone_number: {
                required: true,
                phoneUS: true
            },
            street: {
                required: true,
                minlength: 2,
                maxlength: 100
            },
            city: {
                required: true,
                minlength: 2,
                maxlength: 30,
                city: true
            },
            state: {
                required: true,
                minlength: 2,
                maxlength: 30,
                state: true
            },
            zipcode: {
                required: true,
                zipcode: true
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
                phoneUS: "Please enter valid phone number format...."
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
            concierge_first_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            concierge_last_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            concierge_mobile: {
                required: true,
                phoneUS: "Please enter valid phone number format...."
            },
            concierge_email: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            concierge_hotel_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            concierge_street: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            concierge_city: {
                required: true,
                minlength: 2,
                maxlength: 30,
                city: true
            },
            concierge_state: {
                required: true,
                minlength: 2,
                maxlength: 30,
                state: true
            },
            concierge_zip_code: {
                required: true,
                zipcode: true
            },
            business_first_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            business_last_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
            business_mobile: {
                required: true,
                phoneUS: true
            },
            business_email: {
                required: true,
                email: true
            },
            business_property_name: {
                required: true,
                minlength: 2,
                maxlength: 30,
            },
        },
        messages: {
            email: {
                required: "Please enter a valid email format (e.g., user@example.com).",
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
                phoneUS: "Please enter valid phone number format...."
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
                phoneUS: "Please enter valid phone number format...."
            },
            family_relation: {
                required: "Please enter a relation ",
            },
            family_email: {
                required: "Please enter a valid email format (e.g., user@example.com).",
            },
            concierge_first_name: {
                required: "Please enter a firstname between 2 and 30 character",
            },
            concierge_mobile: {
                required: "Please enter a mobile number",
                phoneUS: "Please enter valid phone number format...."
            },
            concierge_email: {
                required: "Please enter a valid email format (e.g., user@example.com).",
            },
            concierge_last_name: {
                required: "Please enter a lastname between 2 and 30 character",
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
            },
            business_first_name: {
                required: "Please enter a firstname between 2 and 30 character",
            },
            business_last_name: {
                required: "Please enter a lastname between 2 and 30 character",
            },
            business_mobile: {
                required: "Please enter a mobile number",
                phoneUS: "Please enter valid phone number format...."
            },
            business_email: {
                required: "Please enter a valid email format (e.g., user@example.com).",
            },
            business_property_name: {
                required: "Please enter a business/property name",
            },

        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('errorMsg');
            element.closest('.patient').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass('is-valid');
        }
    });
});
