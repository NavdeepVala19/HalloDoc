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
// $(document).ready(function () {

//     $('#patientProfileEditForm').on('submit', function (e) {

//         var focusSet = false;    // this variable is for set and remove focus on input fields

// ** Set email validation
// if (!$('.email').val()) {
//     if ($(".email").next(".validation").length == 0) // only add if not added
//     {
//         $(".email").after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter email address</div>");
//     }
//     e.preventDefault(); // prevent form from POST to server
//     $('.email').focus();
//     focusSet = true;
// } else {
//     $(".email").next(".validation").remove(); // remove it
// }

// var emailAddress = $('.email').val();

// if (emailAddress.length < 2 || emailAddress.length > 30) {
//     if ($(".email").next(".validation").length == 0) {
//         $(".email").after("<div class='validation' style='color:red;margin-bottom: 20px;'>Email must be between 2 and 30 characters and sholud be </div>");
//     }
//     if (!focusSet) {  // Focus on the first failing field
//         $('.email').focus();
//         focusSet = true;
//     }
//     e.preventDefault();
// } else {
//     $(".email").next(".validation").remove();
// }

//         // ** Set firstname validation
//         if (!$('.first_name').val()) {
//             if ($(".first_name").next(".validation").length == 0) // only add if not added
//             {
//                 $(".first_name").after("<div class='validation' style='color:red;margin-bottom: 20px;'>Firstname must be between 2 and 30 characters</div>");
//             }
//             e.preventDefault(); // prevent form from POST to server
//             $('.first_name').focus();
//             focusSet = true;
//         } else {
//             $(".first_name").next(".validation").remove(); // remove it
//         }

//         let firstName = $('.firstname').val();
//         console.log(firstName);

//         if (firstName.length < 2 || firstName.length > 30) {
//             if ($(".firstname").next(".validation").length == 0) {
//                 $(".firstname").after("<div class='validation' style='color:red;margin-bottom: 20px;'>Firstname must be between 2 and 30 characters</div>");
//             }
//             if (!focusSet) {  // Focus on the first failing field
//                 $('.firstname').focus();
//                 focusSet = true;
//             }
//             e.preventDefault();
//         } else {
//             $(".firstname").next(".validation").remove();
//         }

//         // ** Set lastname validation
//         if (!$('.last_name').val()) {
//             if ($(".last_name").next(".validation").length == 0) // only add if not added
//             {
//                 $(".last_name").after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter last_name</div>");
//             }
//             e.preventDefault(); // prevent form from POST to server
//             $('.last_name').focus();
//             focusSet = true;
//         } else {
//             $(".last_name").next(".validation").remove(); // remove it
//         }

//         var lastName = $('.last_name').val();

//         if (lastName.length < 2 || lastName.length > 30) {
//             if ($(".last_name").next(".validation").length == 0) {
//                 $(".last_name").after("<div class='validation' style='color:red;margin-bottom: 20px;'>Lastname must be between 2 and 30 characters</div>");
//             }
//             if (!focusSet) {  // Focus on the first failing field
//                 $('.last_name').focus();
//                 focusSet = true;
//             }
//             e.preventDefault();
//         } else {
//             $(".last_name").next(".validation").remove();
//         }

//         // ** Set date of birth validation
//         if (!$('.date_of_birth').val()) {
//             if ($(".date_of_birth").next(".validation").length == 0) // only add if not added
//             {
//                 $(".date_of_birth").after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter date of birth</div>");
//             }
//             e.preventDefault(); // prevent form from POST to server
//             $('.date_of_birth').focus();
//             focusSet = true;
//         } else {
//             $(".date_of_birth").next(".validation").remove(); // remove it
//         }

//         // ** Set phone number validation
//         if (!$('.phone_number').val()) {
//             if ($(".phone_number").next(".validation").length == 0) // only add if not added
//             {
//                 $(".phone_number").after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter phone_number</div>");
//             }
//             e.preventDefault(); // prevent form from POST to server
//             $('.phone_number').focus();
//             focusSet = true;
//         } else {
//             $(".phone_number").next(".validation").remove(); // remove it
//         }

//         // ** Set street validation
//         if (!$('.street').val()) {
//             if ($(".street").next(".validation").length == 0) // only add if not added
//             {
//                 $(".street").after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter street</div>");
//             }
//             e.preventDefault(); // prevent form from POST to server
//             $('.street').focus();
//             focusSet = true;
//         } else {
//             $(".street").next(".validation").remove(); // remove it
//         }

//         // ** Set city validation
//         if (!$('.city').val()) {
//             if ($(".city").next(".validation").length == 0) // only add if not added
//             {
//                 $(".city").after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter city</div>");
//             }
//             e.preventDefault(); // prevent form from POST to server
//             $('.city').focus();
//             focusSet = true;
//         } else {
//             $(".city").next(".validation").remove(); // remove it
//         }

//         // ** Set state validation
//         if (!$('.state').val()) {
//             if ($(".state").next(".validation").length == 0) // only add if not added
//             {
//                 $(".state").after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter state</div>");
//             }
//             e.preventDefault(); // prevent form from POST to server
//             $('.state').focus();
//             focusSet = true;
//         } else {
//             $(".state").next(".validation").remove(); // remove it
//         }

//         // ** Set zipcode validation
//         if (!$('.zipcode').val()) {
//             if ($(".zipcode").next(".validation").length == 0) // only add if not added
//             {
//                 $(".zipcode").after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter zipcode</div>");
//             }
//             e.preventDefault(); // prevent form from POST to server
//             $('.zipcode').focus();
//             focusSet = true;
//         } else {
//             $(".zipcode").next(".validation").remove(); // remove it
//         }

//     })
// })
